<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Offer extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'offers';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'network_id',
        'redirect_link',
        'net_offer_id',
        'image',
        'status',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    public $appends = [
        'link_to_click',
        'link_to_lead',
    ];

    public function getLinkToClickAttribute()
    {
        return route('frontend.offer_camp').'?offer_id='.$this->id;
    }

    public function getLinkToLeadAttribute()
    {
        return route('frontend.offer_lead').'?offer_id='.$this->id.'&amount=&status=';
    }

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    public function devices()
    {
        return $this->belongsToMany(Device::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }



    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
