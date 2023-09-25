<?php

namespace App\Http\Controllers;

class NotificationsController extends Controller
{
    public function unread()
    {
        return response()->json(auth()->user()->unreadNotifications);
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json();
    }
}
