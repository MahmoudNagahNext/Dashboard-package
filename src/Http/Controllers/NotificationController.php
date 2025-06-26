<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    // TODO::corn job to delete very old notifications 
    
    /**
     * Get all notifications for the authenticated admin
     */
    public function index(): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $notifications = $admin->notifications()->paginate(10);
        
        return Response::json([
            'success' => true,
            'message' => "All Notification fetched successflly",
            'data' => $notifications
        ]);
    }

    /**
     * Get unread notifications for the authenticated admin
     */
    public function unread(): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $notifications = $admin->unreadNotifications()->paginate(10);
        
        return Response::json([
            'success' => true,
            'message' => "Unread Notification fetched successflly",
            'data' => $notifications
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(string $id): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $notification = $admin->notifications()->where('id', $id)->first();
        
        if (!$notification) {
            return Response::json([
                'success' => false,
                'message' => 'Notification not found.',
                'data' => []
            ], 422);
        }
        
        $notification->markAsRead();
        
        return Response::json([
            'success' => true,
            'message' => "Notification marked as read",
            'data' => []
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $admin->unreadNotifications->markAsRead();
        
        return Response::json([
            'success' => true,
            'message' => "All notifications marked as read",
            'data' => []
        ]);
    }

    /**
     * Delete a notification
     */
    public function delete(string $id): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $notification = $admin->notifications()->where('id', $id)->first();
        
        if (!$notification) {
            return Response::json([
                'success' => false,
                'message' => 'Notification not found.',
                'data' => []
            ], 422);
        }
        
        $notification->delete();
        
        return Response::json([
            'success' => true,
            'message' => "Notification deleted successflly",
            'data' => []
        ]);
    }
}