<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use nextdev\nextdashboard\Traits\ApiResponseTrait;

class NotificationController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get all notifications for the authenticated admin
     */
    public function index(): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $notifications = $admin->notifications()->paginate(10);
        
        return $this->successResponse($notifications);
    }

    /**
     * Get unread notifications for the authenticated admin
     */
    public function unread(): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $notifications = $admin->unreadNotifications()->paginate(10);
        
        return $this->successResponse($notifications);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(string $id): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $notification = $admin->notifications()->where('id', $id)->first();
        
        if (!$notification) {
            return $this->errorResponse('Notification not found', 404);
        }
        
        $notification->markAsRead();
        
        return $this->successResponse(null, 'Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $admin->unreadNotifications->markAsRead();
        
        return $this->successResponse(null, 'All notifications marked as read');
    }

    /**
     * Delete a notification
     */
    public function delete(string $id): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $notification = $admin->notifications()->where('id', $id)->first();
        
        if (!$notification) {
            return $this->errorResponse('Notification not found', 404);
        }
        
        $notification->delete();
        
        return $this->successResponse(null, 'Notification deleted');
    }
}