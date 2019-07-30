<?php

namespace App\Http\Controllers\Admin;

use App\Models\Network;
use App\Models\Offer;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OfferRequest as StoreRequest;
use App\Http\Requests\OfferRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class OfferCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OfferCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Offer');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/offer');
        $this->crud->setEntityNameStrings('offer', 'offers');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
        $this->crud->addColumns([
            [
                'name' => 'name',
                'label' => 'Tên'
            ],
            [
                // n-n relationship (with pivot table)
                'label' => "Network", // Table column heading
                'type' => "select",
                'name' => 'network_id', // the method that defines the relationship in your Model
                'entity' => 'network', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Models\Network", // foreign key model

            ],
            [
                'name' => 'redirect_link',
                'label' => 'Link Click'
            ],
            [
                'name' => 'net_offer_id',
                'label' => 'Net OfferId'
            ],
            [
                'name' => 'image',
                'type' => 'browser',
                'label' => 'Offer Image'
            ],
            [
                'name' => 'status',
                'type' => 'select_from_array',
                'options' => [1 => 'Active', 0 => 'Inactive'],
                'label' => 'Status'
            ],
            [
                // n-n relationship (with pivot table)
                'label' => "Devices", // Table column heading
                'type' => "select_multiple",
                'name' => 'devices', // the method that defines the relationship in your Model
                'entity' => 'devices', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Models\Device", // foreign key model

            ],
            [
                // n-n relationship (with pivot table)
                'label' => "Locations", // Table column heading
                'type' => "select_multiple",
                'name' => 'locations', // the method that defines the relationship in your Model
                'entity' => 'locations', // the method that defines the relationship in your Model
                'attribute' => "combine", // foreign key attribute that is shown to user
                'model' => "App\Models\Location", // foreign key model

            ],

            [
                'name' => 'link_to_click',
                'label' => 'Link to Click'
            ],

            [
                'name' => 'link_to_lead',
                'label' => 'Link to Lead'
            ],

        ]);

        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => 'Tên'
            ],
            [
                // n-n relationship (with pivot table)
                'label' => "Network", // Table column heading
                'type' => "select2",
                'name' => 'network_id', // the method that defines the relationship in your Model
                'entity' => 'network', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Models\Network", // foreign key model

            ],
            [
                'name' => 'redirect_link',
                'label' => 'Link Click'
            ],
            [
                'name' => 'net_offer_id',
                'label' => 'Net OfferId'
            ],
            [
                'name' => 'image',
                'type' => 'image',
                'label' => 'Offer Image'
            ],
            [
                'name' => 'status',
                'type' => 'select_from_array',
                'options' => [1 => 'Active', 0 => 'Inactive'],
                'label' => 'Status'
            ],
            [       // Select2Multiple = n-n relationship (with pivot table)
                'label' => "Devices",
                'type' => 'select2_multiple',
                'name' => 'devices', // the method that defines the relationship in your Model
                'entity' => 'devices', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "App\Models\Device", // foreign key model
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                // 'select_all' => true, // show Select All and Clear buttons?

            ],
            [
                // n-n relationship (with pivot table)
                'label' => "Locations", // Table column heading
                'type' => "select2_multiple",
                'name' => 'locations', // the method that defines the relationship in your Model
                'entity' => 'locations', // the method that defines the relationship in your Model
                'attribute' => "combine", // foreign key attribute that is shown to user
                'model' => "App\Models\Location", // foreign key model
                'pivot' => true,
            ],
        ]);

        $this->crud->orderBy('created_at', 'desc');
        $this->crud->allowAccess('show');
        $this->crud->enableExportButtons();

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'CHỌN THỜI GIAN'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'created_at', '>=', $dates->from.' 00:00:00');
                $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
            });

        $this->crud->addFilter([ // select2 filter
            'name' => 'status',
            'type' => 'select2',
            'label'=> 'Trạng thái'
        ], function() {
            return [1 => 'Active', 0 => 'Inactive'];
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'status', $value);
        });


        $this->crud->addFilter([ // select2 filter
            'name' => 'network_id',
            'type' => 'select2',
            'label'=> 'Network'
        ], function() {
            $networkIds = Offer::groupBy('network_id')->pluck('network_id')->all();
            return Network::whereIn('id', $networkIds)->pluck('name', 'id')->all();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'network_id', $value);
        });




        // add asterisk for fields that are required in OfferRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
