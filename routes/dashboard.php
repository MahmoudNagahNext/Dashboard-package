<?php

use Illuminate\Support\Facades\Route;
use nextdev\nextdashboard\Http\Controllers\AdminController;
use nextdev\nextdashboard\Http\Controllers\AuthController;
use nextdev\nextdashboard\Http\Controllers\DropDownsController;
use nextdev\nextdashboard\Http\Controllers\TicketCategoriesController;
use nextdev\nextdashboard\Http\Controllers\TicketController;

Route::group(["prefix"=> "dashboard"], function () {
    
    Route::group(['prefix' => 'auth', 'controller' => AuthController::class],function () {
        Route::post('login', 'login');
        // Route::post('register', 'register');

        Route::post('/forgot-password', 'sendResetLinkEmail');
        Route::post('/reset-password', 'reset');
    });

    Route::group(['middleware' => 'auth:admin'], function () {

        // Admin management
        Route::apiResource('admins', AdminController::class);

        // Ticket management
        Route::apiResource('tickets', TicketController::class);

        // Ticket category management
        Route::apiResource('tickets/categories', TicketCategoriesController::class);

        // Dropdown settings
        Route::group(function () {
            Route::get('tickets/statuses', [DropDownsController::class, 'ticketStatuies']);
            Route::get('tickets/priorities', [DropDownsController::class, 'ticketPriorities']);
        });

    });
    
});
