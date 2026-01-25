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
    Route::get('/properties', fn() => view('properties.index'))->name('properties.index');
    Route::get('/properties/create', fn() => view('properties.create'))->name('properties.create');
    Route::get('/properties/{id}', fn($id) => view('properties.show', ['propertyId' => $id]))->name('properties.show');
    Route::get('/properties/{id}/edit', fn($id) => view('properties.edit', ['propertyId' => $id]))->name('properties.edit');

    // Tenants
    Route::get('/tenants', fn() => view('tenants.index'))->name('tenants.index');
    Route::get('/tenants/create', fn() => view('tenants.create'))->name('tenants.create');
    Route::get('/tenants/{id}', fn($id) => view('tenants.show', ['tenantId' => $id]))->name('tenants.show');
    Route::get('/tenants/{id}/edit', fn($id) => view('tenants.edit', ['tenantId' => $id]))->name('tenants.edit');

    // Leases
    Route::get('/leases', fn() => view('leases.index'))->name('leases.index');
    Route::get('/leases/create', fn() => view('leases.create'))->name('leases.create');
    Route::get('/leases/{id}/edit', fn($id) => view('leases.edit', ['leaseId' => $id]))->name('leases.edit');

    // Payments
    Route::get('/payments', fn() => view('payments.index'))->name('payments.index');
    Route::get('/payments/create', fn() => view('payments.create'))->name('payments.create');
    Route::get('/payments/{id}/edit', fn($id) => view('payments.edit', ['paymentId' => $id]))->name('payments.edit');

    // Maintenance
    Route::get('/maintenance', fn() => view('maintenance.index'))->name('maintenance.index');
    Route::get('/maintenance/create', fn() => view('maintenance.create'))->name('maintenance.create');
    Route::get('/maintenance/{id}', fn($id) => view('maintenance.show', ['requestId' => $id]))->name('maintenance.show');
    Route::get('/maintenance/{id}/edit', fn($id) => view('maintenance.edit', ['requestId' => $id]))->name('maintenance.edit');

    // Vendors
    Route::get('/vendors', fn() => view('vendors.index'))->name('vendors.index');
    Route::get('/vendors/create', fn() => view('vendors.create'))->name('vendors.create');
    Route::get('/vendors/{id}/edit', fn($id) => view('vendors.edit', ['vendorId' => $id]))->name('vendors.edit');

    // Reports
    Route::get('/reports', fn() => view('reports.index'))->name('reports.index');
});

require __DIR__ . '/auth.php';