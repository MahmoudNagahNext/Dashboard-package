<?php

use Illuminate\Support\Facades\Route;
use nextdev\nextdashboard\Http\Controllers\AdminController;
use nextdev\nextdashboard\Http\Controllers\AuthController;
use nextdev\nextdashboard\Http\Controllers\DropDownsController;
use nextdev\nextdashboard\Http\Controllers\ForgotPasswordController;
use nextdev\nextdashboard\Http\Controllers\TicketCategoriesController;
use nextdev\nextdashboard\Http\Controllers\TicketController;
use nextdev\nextdashboard\Http\Controllers\TicketReplyController;

Route::prefix("dashboard")->group(function () {
    
    // Auth routes
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
    });

    Route::controller(ForgotPasswordController::class)
    ->prefix('auth')
    ->group(function(){
        Route::post('/forgot-password', 'sendOtp');
        Route::post('/reset-password', 'resetPassword');
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
        
            // Ticket replies routes
            Route::prefix("{ticket}/replies")->group(function () {
                Route::get('/', [TicketReplyController::class, 'index']);
                Route::post('/', [TicketReplyController::class, 'store']);
                Route::get('/{reply}', [TicketReplyController::class, 'show']);
                Route::put('/{reply}', [TicketReplyController::class, 'update']);
                Route::delete('/{reply}', [TicketReplyController::class, 'delete']);
            });
        });
    });
});