<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\NetworkRequest as StoreRequest;
use App\Http\Requests\NetworkRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class NetworkCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class NetworkCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Network');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/network');
        $this->crud->setEntityNameStrings('network', 'networks');

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
            ]
        ]);

        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => 'Tên'
            ]
        ]);

        $this->crud->addClause('orderBy', 'created_at', 'desc');

        // add asterisk for fields that are required in NetworkRequest
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
