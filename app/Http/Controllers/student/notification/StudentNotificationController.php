<?php

namespace App\Http\Controllers\student\notification;

use App\Http\Controllers\Controller;
use App\Models\general\notification\Lbnotification;
use App\Models\student\Lbstudent;
use App\Mail\NotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

    public function deleteAllNotifications(Request $request)
    {
        try {
            Lbnotification::where(['lbstudent_id' => $request->lbstudent_id, 'for' => 'student'])->delete();
            return response()->json(['success' => true, 'message' => 'All Notifications Cleared']);
        } catch (\Exception $e) {
            return response()->json(['Error' => $e->getMessage()]);
        }
    }

    /* ══════════════════════════════════════════════════════
       Create a notification for a student — called from other controllers.
       Respects the student's email_notifications / inapp_notifications
       preferences (from MyProfilePage). Null/missing = treated as enabled
       (default), so existing students without a saved preference still
       get notified as before.
    ══════════════════════════════════════════════════════ */
   /* ══════════════════════════════════════════════════════
   Create a notification for a student — called from other controllers.
   Respects the student's emailNotifications / inappNotifications
   preferences (from MyProfilePage). Columns default to '0' (off),
   so a student who never touched the toggles gets no notifications
   until they explicitly enable them.
══════════════════════════════════════════════════════ */
public static function notifyStudent($studentId, $title, $subtitle, $type = null, $detail = null)
{
    $student = Lbstudent::find($studentId);

    if (!$student) {
        return null;
    }

    $inAppEnabled = in_array($student->inappNotifications, [1, "1", true], true);
    $emailEnabled = in_array($student->emailNotifications, [1, "1", true], true);

    $created = null;

    if ($inAppEnabled) {
        $created = Lbnotification::create([
            'lbstudent_id' => $studentId,
            'title'        => $title,
            'subtitle'     => $subtitle,
            'for'          => 'student',
            'status'       => 'unread',
            'type'         => $type,
            'detail'       => $detail,
        ]);
    }

    if ($emailEnabled && $student->email) {
        try {
            Mail::to($student->email)->send(
                new NotificationMail($title, $subtitle, $student->fullName)
            );
        } catch (\Exception $e) {
            Log::error('Student notification email failed: ' . $e->getMessage());
        }
    }

    return $created;
    }
}