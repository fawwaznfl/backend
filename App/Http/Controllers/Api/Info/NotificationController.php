<?php

namespace App\Http\Controllers\Api\Info;

use App\Http\Controllers\Controller;
use App\Models\NotificationTarget;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $notifications = NotificationTarget::with('notification')
            ->where(function ($q) use ($user) {
                // Notifikasi personal
                $q->where('user_id', $user->id)
                // Notifikasi berdasarkan role (dashboard_type)
                ->orWhere(function ($q) use ($user) {
                    $q->where('role', $user->dashboard_type) 
                        ->where(function ($q) use ($user) {
                            $q->whereNull('company_id')
                            ->orWhere('company_id', $user->company_id);
                        });
                });
            })
            ->latest()
            ->get();

        return response()->json($notifications);
    }

    public function read($id)
    {
        $user = auth()->user();
        
        
        $target = NotificationTarget::where('id', $id)
            ->where(function ($q) use ($user) {
                // Bisa dibaca jika notif adalah milik user ini (personal)
                $q->where('user_id', $user->id)
                // ATAU notif untuk role ini (broadcast ke role)
                ->orWhere(function ($q) use ($user) {
                    $q->where('role', $user->dashboard_type)
                        ->where(function ($q) use ($user) {
                            $q->whereNull('company_id')
                            ->orWhere('company_id', $user->company_id);
                        });
                });
            })
            ->firstOrFail();

        $target->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['message' => 'Read']);
    }

    public function unreadCount()
    {
        $user = auth()->user();

        $count = NotificationTarget::where('is_read', false)
            ->where(function ($q) use ($user) {
                // Notifikasi personal
                $q->where('user_id', $user->id)
                // Notifikasi berdasarkan dashboard_type
                ->orWhere(function ($q) use ($user) {
                    $q->where('role', $user->dashboard_type)
                        ->where(function ($q) use ($user) {
                            $q->whereNull('company_id')
                            ->orWhere('company_id', $user->company_id);
                        });
                });
            })
            ->count();

        return response()->json([
            'unread' => $count
        ]);
    }

    public function markAllPersonalAsRead()
    {
        $user = auth()->user();
        
        // Hanya mark personal notifications (yang punya user_id)
        $updated = NotificationTarget::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'message' => 'All personal notifications marked as read',
            'updated' => $updated
        ]);
    }

    public function markAllAsRead()
    {
        $user = auth()->user();

        $updated = NotificationTarget::where('is_read', false)
            ->where(function ($q) use ($user) {
                // Personal
                $q->where('user_id', $user->id)
                // Role-based
                ->orWhere(function ($q) use ($user) {
                    $q->where('role', $user->dashboard_type)
                    ->where(function ($q) use ($user) {
                        $q->whereNull('company_id')
                            ->orWhere('company_id', $user->company_id);
                    });
                });
            })
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'message' => 'All notifications marked as read',
            'updated' => $updated
        ]);
    }

}