<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    CRUD::resource('network', 'NetworkCrudController');
    CRUD::resource('offer', 'OfferCrudController');
    CRUD::resource('click', 'ClickCrudController');
    CRUD::resource('lead', 'LeadCrudController');
    CRUD::resource('device', 'DeviceCrudController');
    CRUD::resource('location', 'LocationController');
}); // this should be the absolute last line of this file
