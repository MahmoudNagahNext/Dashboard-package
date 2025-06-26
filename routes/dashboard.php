<?php

use Illuminate\Support\Facades\Route;
use nextdev\nextdashboard\Http\Controllers\AdminController;
use nextdev\nextdashboard\Http\Controllers\AuthController;
use nextdev\nextdashboard\Http\Controllers\SettingsController;
use nextdev\nextdashboard\Http\Controllers\ForgotPasswordController;
use nextdev\nextdashboard\Http\Controllers\PermissionController;
use nextdev\nextdashboard\Http\Controllers\RoleController;
use nextdev\nextdashboard\Http\Controllers\TicketCategoriesController;
use nextdev\nextdashboard\Http\Controllers\TicketController;
use nextdev\nextdashboard\Http\Controllers\TicketReplyController;
use nextdev\nextdashboard\Http\Controllers\NotificationController;
use nextdev\nextdashboard\Http\Middleware\HandleAuthorizationException;

Route::prefix("dashboard")
->middleware([HandleAuthorizationException::class])
->group(function () {
    
    // Auth routes
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
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


        Route::apiResource('roles', RoleController::class);

        Route::get('permissions', [PermissionController::class, 'index']);

        // Tickets routes
        Route::prefix("tickets")->group(function () {
           
            // Ticket Categories resource
            Route::apiResource('categories', TicketCategoriesController::class);
            Route::post("/categories/bulk-delete", [TicketCategoriesController::class, 'bulkDelete']);
     
            Route::get('statuses', [SettingsController::class, 'ticketStatuses']);
            Route::get('priorities', [SettingsController::class, 'ticketPriorities']);
        
            // Tickets resource
            Route::apiResource('', TicketController::class)->parameters(['' => 'ticket']);
            Route::post('{id}/attachments', [TicketController::class, 'addAttachments']);
            Route::delete('{id}/attachments', [TicketController::class, 'deleteAttachments']);
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
    // Add this inside the middleware('auth:admin') group
    
    // Notifications routes
    Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/unread', 'unread');
        Route::post('/{id}/read', 'markAsRead');
        Route::post('/read-all', 'markAllAsRead');
        Route::delete('/{id}', 'delete');
    });
});