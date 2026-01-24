<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Properties
    Route::get('/properties', function () {
        return view('properties.index');
    })->name('properties.index');

    Route::get('/properties/create', function () {
        return view('properties.create');
    })->name('properties.create');

    Route::get('/properties/{property}', function ($property) {
        return view('properties.show', ['propertyId' => $property]);
    })->name('properties.show');

    Route::get('/properties/{property}/edit', function ($property) {
        return view('properties.edit', ['propertyId' => $property]);
    })->name('properties.edit');

    // Tenants
    Route::get('/tenants', function () {
        return view('tenants.index');
    })->name('tenants.index');

    Route::get('/tenants/create', function () {
        return view('tenants.create');
    })->name('tenants.create');

    Route::get('/tenants/{tenant}', function ($tenant) {
        return view('tenants.show', ['tenantId' => $tenant]);
    })->name('tenants.show');

    Route::get('/tenants/{tenant}/edit', function ($tenant) {
        return view('tenants.edit', ['tenantId' => $tenant]);
    })->name('tenants.edit');

    // Maintenance
    Route::get('/maintenance', function () {
        return view('maintenance.index');
    })->name('maintenance.index');

    Route::get('/maintenance/create', function () {
        return view('maintenance.create');
    })->name('maintenance.create');

    Route::get('/maintenance/{maintenanceRequest}', function ($maintenanceRequest) {
        return view('maintenance.show', ['requestId' => $maintenanceRequest]);
    })->name('maintenance.show');

    // Vendors
    Route::get('/vendors', function () {
        return view('vendors.index');
    })->name('vendors.index');

    Route::get('/vendors/create', function () {
        return view('vendors.create');
    })->name('vendors.create');

    Route::get('/vendors/{vendor}/edit', function ($vendor) {
        return view('vendors.edit', ['vendorId' => $vendor]);
    })->name('vendors.edit');

    // Maintenance
    Route::get('/maintenance', function () {
        return view('maintenance.index');
    })->name('maintenance.index');

    Route::get('/maintenance/create', function () {
        return view('maintenance.create');
    })->name('maintenance.create');

    Route::get('/maintenance/{id}', function ($id) {
        return view('maintenance.show', ['requestId' => $id]);
    })->name('maintenance.show');

    Route::get('/maintenance/{id}/edit', function ($id) {
        return view('maintenance.edit', ['requestId' => $id]);
    })->name('maintenance.edit');

    // Vendors
    Route::get('/vendors', function () {
        return view('vendors.index');
    })->name('vendors.index');

    Route::get('/vendors/create', function () {
        return view('vendors.create');
    })->name('vendors.create');

    Route::get('/vendors/{id}/edit', function ($id) {
        return view('vendors.edit', ['vendorId' => $id]);
    })->name('vendors.edit');
});

require __DIR__ . '/auth.php';