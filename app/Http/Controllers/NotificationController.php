<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\FormReceived;

class NotificationController extends Controller
{
    public function read($id)
    {
        // Find the notification
        $user = auth()->user();

        // Find the notification by ID and mark it as read
        $user->unreadNotifications->where('id', $id)->markAsRead();

        return response()->json(['message' => 'Notification marked as read'], 200);

    }
    public function index()
    {
        return view('content.pages.notifications');
    }
}
