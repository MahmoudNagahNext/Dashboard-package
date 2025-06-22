<?php

use Illuminate\Support\Facades\Route;
use nextdev\nextdashboard\Http\Controllers\AdminController;
use nextdev\nextdashboard\Http\Controllers\AuthController;
use nextdev\nextdashboard\Http\Controllers\DropDownsController;
use nextdev\nextdashboard\Http\Controllers\PermissionController;
use nextdev\nextdashboard\Http\Controllers\RoleController;
use nextdev\nextdashboard\Http\Controllers\RolesController;
use nextdev\nextdashboard\Http\Controllers\TicketCategoriesController;
use nextdev\nextdashboard\Http\Controllers\TicketController;

Route::prefix("dashboard")->group(function () {
    
    // Auth routes
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('login', 'login');

        Route::post('register', 'register');
        // Route::post('/forgot-password', 'sendResetLinkEmail');
        // Route::post('/reset-password', 'reset');
    });

    Route::middleware('auth:admin')->group( function () {

        // Admin management
        Route::apiResource('admins', AdminController::class);
        Route::post("/admins/bulk-delete", [AdminController::class, 'bulkDelete']);
        Route::post("/admins/{admin}/assign-role", [AdminController::class, 'assignRole']);

        // Tickets routes
        Route::prefix("tickets")->group(function () {
           
            // Ticket Categories resource
            Route::apiResource('categories', TicketCategoriesController::class);
            Route::post("/categories/bulk-delete", [TicketCategoriesController::class, 'bulkDelete']);
    
            Route::get('statuses', [DropDownsController::class, 'ticketStatuses']);
            Route::get('priorities', [DropDownsController::class, 'ticketPriorities']);
        
             // Tickets resource
             Route::apiResource('', TicketController::class)->parameters(['' => 'ticket']);
             Route::post("/bulk-delete", [TicketController::class, 'bulkDelete']);
        
        });

        // Roles & Permissions 
        Route::apiResource('/roles',RoleController::class);
        Route::GET("/permissions", [PermissionController::class, "index"]);
    });
});
