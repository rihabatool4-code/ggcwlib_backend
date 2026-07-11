<?php

namespace App\Http\Controllers\student\notification;

use App\Http\Controllers\Controller;
use App\Models\general\notification\Lbnotification;
use Illuminate\Http\Request;

class StudentNotificationController extends Controller
{
    public function fetchAllNotifications(Request $request)
    {
        try {
            $notifications = Lbnotification::where(['lbstudent_id' => $request->lbstudent_id, 'for' => 'student'])
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
            $notifications = Lbnotification::where(['lbstudent_id' => $request->lbstudent_id, 'status' => 'unread'])->update(['status' => 'read']);
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
            $notification = Lbnotification::where(['id' => $request->id, 'lbstudent_id' => $request->lbstudent_id])->first();

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
            $notification = Lbnotification::where(['id' => $request->id, 'lbstudent_id' => $request->lbstudent_id])->first();

            if (!$notification) {
                return response()->json(['success' => false, 'message' => 'Notification not found']);
            }

            $notification->delete();

            return response()->json(['success' => true, 'message' => 'Notification Deleted']);
        } catch (\Exception $e) {
            return response()->json(['Error' => $e->getMessage()]);
        }
    }
    /* ── Delete ALL notifications for this student ── */
public function deleteAllNotifications(Request $request)
  {
    try {
        Lbnotification::where(['lbstudent_id' => $request->lbstudent_id, 'for' => 'student'])->delete();
        return response()->json(['success' => true, 'message' => 'All Notifications Cleared']);
    } catch (\Exception $e) {
        return response()->json(['Error' => $e->getMessage()]);
    }
  }

    /* ── Create a notification for a student — called from other controllers ── */
    public static function notifyStudent($studentId, $title, $subtitle, $type = null, $detail = null)
    {
        return Lbnotification::create([
            'lbstudent_id' => $studentId,
            'title'        => $title,
            'subtitle'     => $subtitle,
            'for'          => 'student',
            'status'       => 'unread',
            'type'         => $type,
            'detail'       => $detail,
        ]);
    }
}