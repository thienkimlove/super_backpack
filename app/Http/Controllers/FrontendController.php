<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\Network;
use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class FrontendController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function link(Request $request)
    {
        $networkId = $request->input('network_id');

        if (!$networkId) {
            return response()->json([
                'error' => 'No input network_id!'
            ]);
        }

        $network = Network::find($networkId);

        if (!$network) {
            return response()->json([
                'error' => 'Invalid input network_id!'
            ]);
        }

        $latestOfferInNetwork = Offer::where('status', true)->where('network_id', $networkId)->latest('created_at')->first();

        if (!$latestOfferInNetwork) {
            return response()->json([
                'error' => 'No offer active for network!'
            ]);
        }

        return redirect(route('frontend.offer_camp').'?offer_id='.$latestOfferInNetwork->id);
    }

    public function getCurrentLocationByIp()
    {
        $ipLocation = request()->ip();
        $country = "";
        try {
            $getIp = geoip()->getLocation($ipLocation);
            $country = $getIp['country'];
            $country = preg_replace('/\s+/', '', $country);
            $country = strtolower($country);
        }  catch (\Exception $e) {

        }

        return $country;
    }

    public function camp(Request $request)
    {
        $offerId = $request->input('offer_id');

        if (!$offerId) {
            return response()->json([
                'error' => 'No offer Id!'
            ]);
        }

        $offer = Offer::find($offerId);

        if (!$offer) {
            return response()->json([
                'error' => 'No offer for this Id!'
            ]);
        }

        if ($offer->status == false) {
            return response()->json([
                'error' => 'Offer for this Id is inactive!'
            ]);
        }

        $ipLocation = $request->ip();

        try {
            $getIp = geoip()->getLocation($ipLocation);
            $isoCode = strtolower($getIp['iso_code']);
        }  catch (\Exception $e) {
            $isoCode = null;
        }

        if (!$isoCode) {
            return response()->json([
                'error' => 'Can not get location from ip!'
            ]);
        }

        $allowLocation = false;
        $allowDevice = false;
        $lowerLocations = "";
        foreach ($offer->locations as $location) {
            $lowerLocations .= ",".strtolower($location->code);
            if ($location == 'uk') {
                $lowerLocations .= ", gb";
            }
            if ($location == 'gb') {
                $lowerLocations .= ", uk";
            }
        }

        if (strpos($lowerLocations, $isoCode) !== FALSE) {
            $allowLocation = true;
        }

        if (!$allowLocation) {
            return response()->json([
                'error' => 'Location are not allow!',
                'isoCode' => $isoCode,
                'lowerLocations' => $lowerLocations
            ]);
        }

        $agent = new Agent();

        foreach ($offer->devices as $device) {
            if ($device->platform == 'ios' && $agent->isMobile() && $agent->isiOS()) {
                $allowDevice = true;
            }
            if ($device->platform == 'android' && $agent->isMobile() && $agent->isAndroidOS()) {
                $allowDevice = true;
            }

            if ($device->platform == 'all') {
                $allowDevice = true;
            }
        }

        if (!$allowDevice) {
            return response()->json([
                'error' => 'Device are not allow!',
                'device' => $offer->devices
            ]);
        }

        $hash_tag = (string) Str::uuid();
        $isInsert = false;
        DB::beginTransaction();

        try {
            Click::create([
                'hash_tag' => $hash_tag,
                'offer_id' => $offerId,
                'click_ip' => $request->ip(),
                'click_time' => Carbon::now()->toDateTimeString()
            ]);

            DB::commit();
            $isInsert = true;
        } catch (\Exception $e) {
            DB::rollBack();
        }

        if (!$isInsert) {
            return response()->json([
                'error' => 'Can not insert to DB!'
            ]);
        }

        //redirect.

        return redirect()->away($offer->redirect_link);


    }
}
