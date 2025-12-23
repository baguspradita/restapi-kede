<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $notifications = $request->user()->notifications()
                ->orderBy('created_at', 'desc')
                ->paginate(request('limit', 20));

            return $this->paginatedResponse($notifications, 'Notifications retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve notifications: ' . $e->getMessage(), [], 500);
        }
    }

    public function markAsRead(Request $request, $id)
    {
        try {
            $notification = $request->user()->notifications()->findOrFail($id);
            $notification->update(['is_read' => true]);

            return $this->successResponse($notification, 'Notification marked as read');

        } catch (\Exception $e) {
            return $this->notFoundResponse('Notification not found');
        }
    }

    public function markAllAsRead(Request $request)
    {
        try {
            $request->user()->notifications()
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return $this->successResponse(null, 'All notifications marked as read');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to mark notifications as read: ' . $e->getMessage(), [], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $notification = $request->user()->notifications()->findOrFail($id);
            $notification->delete();

            return $this->successResponse(null, 'Notification deleted successfully');

        } catch (\Exception $e) {
            return $this->notFoundResponse('Notification not found');
        }
    }
}
