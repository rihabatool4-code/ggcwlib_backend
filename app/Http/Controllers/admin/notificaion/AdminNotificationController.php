<?php

namespace App\Http\Controllers\admin\notificaion;

use App\Http\Controllers\Controller;
use App\Models\general\notification\Lbnotification;
use App\Models\admin\Lbadmin;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function fetchAllNotifications(Request $request)
    {
        try {
            $notifications = Lbnotification::where(['lbadmin_id' => $request->lbadmin_id, 'for' => 'admin'])
                ->orderBy('created_at', 'desc')
                ->get();
            if ($notifications) {
                return response()->json(['success' => true, 'notifications' => $notifications]);
            } else {
                return response()->json(['success' => false, 'message' => 'No Record Found']);
            }
        } catch (\Exception $e) {
            return response()->json(['Error' => $e->getMessage()]);
        }
    }

    public function markAllAsRead(Request $request)
    {
        try {
            $notifications = Lbnotification::where(['lbadmin_id' => $request->lbadmin_id, 'status' => 'unread'])->update(['status' => 'read']);
            if ($notifications > 0) {
                return response()->json(['success' => true, 'message' => 'Notifications Marked As Read']);
            } else {
                return response()->json(['success' => false, 'message' => 'No Record Found']);
            }
        } catch (\Exception $e) {
            return response()->json(['Error' => $e->getMessage()]);
        }
    }

    /* ── Mark a single notification as read ── */
    public function markOneAsRead(Request $request)
    {
        try {
            $notification = Lbnotification::where(['id' => $request->id, 'lbadmin_id' => $request->lbadmin_id])->first();

            if (!$notification) {
                return response()->json(['success' => false, 'message' => 'Notification not found']);
            }

            $notification->status = 'read';
            $notification->save();

            return response()->json(['success' => true, 'message' => 'Notification Marked As Read']);
        } catch (\Exception $e) {
            return response()->json(['Error' => $e->getMessage()]);
        }
    }

    /* ── Delete a single notification ── */
    public function deleteNotification(Request $request)
    {
        try {
            $notification = Lbnotification::where(['id' => $request->id, 'lbadmin_id' => $request->lbadmin_id])->first();

            if (!$notification) {
                return response()->json(['success' => false, 'message' => 'Notification not found']);
            }

            $notification->delete();

            return response()->json(['success' => true, 'message' => 'Notification Deleted']);
        } catch (\Exception $e) {
            return response()->json(['Error' => $e->getMessage()]);
        }
    }

    /* ── Create a notification for ALL admins — called from other controllers ── */
    public static function notifyAllAdmins($title, $subtitle, $type = null, $detail = null)
    {
        $admins = Lbadmin::all();

        foreach ($admins as $admin) {
            Lbnotification::create([
                'lbadmin_id' => $admin->id,
                'title'      => $title,
                'subtitle'   => $subtitle,
                'for'        => 'admin',
                'status'     => 'unread',
                'type'       => $type,
                'detail'     => $detail,
            ]);
        }
    }
}