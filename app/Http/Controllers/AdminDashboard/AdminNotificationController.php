<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminNotificationController extends Controller
{

    public function index()
    {
        $admin = admin::find(auth()->guard('admin')->user()->id);

        return response()->json([
            'notifications' => $admin->notifications,
        ]);
    }

    public function unread()
    {
        $admin = admin::find(auth()->guard('admin')->user()->id);

        return response()->json([
            'notifications' => $admin->unreadNotifications,
        ]);
    }
    public function markasread()
    {
        $admin = admin::find(auth()->guard('admin')->user()->id);

        foreach ($admin->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
        return response()->json([
            'notifications' => 'readed the notifications',
        ]);
    }
    public function markoneasread($id)
    {
        $admin = admin::find(auth()->guard('admin')->user()->id);

        DB::table('notifications')->where('id', $id)->update(['read_at' => now()]);

        return response()->json([
            'notifications' => 'readed the notification',
        ]);
    }
    public function read()
    {
        $admin = admin::find(auth()->guard('admin')->user()->id);

        return response()->json([
            'notifications' => $admin->readNotifications,
        ]);
    }
    public function deletenotifications()
    {
        $admin = admin::find(auth()->guard('admin')->user()->id);

        $admin->notifications()->delete();
        return response()->json([
            'notifications' => 'deleted successfuly',
        ]);
    }
    public function deletenotification($id)
    {

        DB::table('notifications')->where('id', $id)->delete();
        return response()->json([
            'notifications' => 'deleted successfuly',
        ]);
    }
}
