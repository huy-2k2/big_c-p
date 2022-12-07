<?php

namespace App\Http\Controllers;

use App\Events\ReadedNotifiEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function mark_readed(Request $request)
    {
        $user_id = $request->user_id;
        $notification_id = $request->notification_id;
        $notification_user = User::find($user_id)->notifications->where('id', $notification_id)->first()->pivot;
        $timestamp = Carbon::now();
        if (!$notification_user->readed_at) {
            $notification_user->update(['readed_at' => $timestamp]);
        }
        broadcast(new ReadedNotifiEvent(['time' => $timestamp->toDateTimeString(),  'notifi_id' => $notification_id, 'user_id' => $user_id]));
        return response()->json($timestamp->toDateTimeString(), 200);
    }
}
