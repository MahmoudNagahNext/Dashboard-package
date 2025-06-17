<?php

use Illuminate\Support\Facades\Route;
use nextdev\nextdashboard\Http\Controllers\AdminController;
use nextdev\nextdashboard\Http\Controllers\AuthController;
use nextdev\nextdashboard\Http\Controllers\DropDownsController;
use nextdev\nextdashboard\Http\Controllers\TicketCategoriesController;
use nextdev\nextdashboard\Http\Controllers\TicketController;

Route::prefix("dashboard")->group(function () {
    
    // Auth routes
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('login', 'login');

        // Route::post('register', 'register');
        // Route::post('/forgot-password', 'sendResetLinkEmail');
        // Route::post('/reset-password', 'reset');
    });

    Route::middleware('auth:admin')->group( function () {

        // Admin management
        Route::apiResource('admins', AdminController::class);

        // Tickets routes
        Route::prefix("tickets")->group(function () {
            // Tickets resource
            Route::apiResource('', TicketController::class)->parameters(['' => 'ticket']);

            // Ticket Categories resource
            Route::apiResource('categories', TicketCategoriesController::class);
    
            Route::get('statuses', [DropDownsController::class, 'ticketStatuies']);
            Route::get('priorities', [DropDownsController::class, 'ticketPriorities']);
        });
    });
});
