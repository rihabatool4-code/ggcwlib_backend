<?php

namespace App\Http\Controllers\Teacher\notifications;

use App\Http\Controllers\Controller;
use App\Models\general\notification\Lbnotification;
use App\Models\teacher\Lbteacher;
use App\Mail\NotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TeacherNotificationController extends Controller
{
    public function fetchAllNotifications(Request $request)
    {
        try {
            $notifications = Lbnotification::where(['lbteacher_id' => $request->lbteacher_id, 'for' => 'teacher'])
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
            $notifications = Lbnotification::where(['lbteacher_id' => $request->lbteacher_id, 'status' => 'unread'])->update(['status' => 'read']);
            if ($notifications > 0) {
                return response()->json(['success' => true, 'message' => 'Notifications Marked As Read']);
            } else {
                return response()->json(['success' => false, 'message' => 'No Record Found']);
            }
        } catch (\Exception $e) {
            return response()->json(['Error' => $e->getMessage()]);
        }
    }

    public function markOneAsRead(Request $request)
    {
        try {
            $notification = Lbnotification::where(['id' => $request->id, 'lbteacher_id' => $request->lbteacher_id])->first();

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

    public function deleteNotification(Request $request)
    {
        try {
            $notification = Lbnotification::where(['id' => $request->id, 'lbteacher_id' => $request->lbteacher_id])->first();

            if (!$notification) {
                return response()->json(['success' => false, 'message' => 'Notification not found']);
            }

            $notification->delete();

            return response()->json(['success' => true, 'message' => 'Notification Deleted']);
        } catch (\Exception $e) {
            return response()->json(['Error' => $e->getMessage()]);
        }
    }

    public function deleteAllNotifications(Request $request)
    {
        try {
            Lbnotification::where(['lbteacher_id' => $request->lbteacher_id, 'for' => 'teacher'])->delete();
            return response()->json(['success' => true, 'message' => 'All Notifications Cleared']);
        } catch (\Exception $e) {
            return response()->json(['Error' => $e->getMessage()]);
        }
    }

    /* ══════════════════════════════════════════════════════
       Create a notification for a teacher — called from other controllers.
       Respects emailNotifications / inappNotifications preferences
       (columns default to '0' — off — until teacher enables them).
    ══════════════════════════════════════════════════════ */
    public static function notifyTeacher($teacherId, $title, $subtitle, $type = null, $detail = null)
    {
        $teacher = Lbteacher::find($teacherId);

        if (!$teacher) {
            return null;
        }

        $inAppEnabled = in_array($teacher->inappNotifications, [1, "1", true], true);
        $emailEnabled = in_array($teacher->emailNotifications, [1, "1", true], true);

        $created = null;

        if ($inAppEnabled) {
            $created = Lbnotification::create([
                'lbteacher_id' => $teacherId,
                'title'        => $title,
                'subtitle'     => $subtitle,
                'for'          => 'teacher',
                'status'       => 'unread',
                'type'         => $type,
                'detail'       => $detail,
            ]);
        }

        if ($emailEnabled && $teacher->email) {
            try {
                Mail::to($teacher->email)->send(
                    new NotificationMail($title, $subtitle, $teacher->name)
                );
            } catch (\Exception $e) {
                Log::error('Teacher notification email failed: ' . $e->getMessage());
            }
        }

        return $created;
    }
}