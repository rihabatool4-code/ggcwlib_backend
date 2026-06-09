<?php

namespace App\Http\Controllers\admin\notificaion;

use App\Http\Controllers\Controller;
use App\Models\general\notification\Lbnotification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function fetchAllNotifications(Request $request)
    {
        // return response()->json(['request' => $request->toArray()]);

        try {
            $notifications = Lbnotification::where(['lbadmin_id' => $request->lbadmin_id, 'for' => 'admin'])->get();
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
        // return response()->json(['request' => $request->toArray()]);

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
    
}
