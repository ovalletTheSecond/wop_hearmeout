<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class HandleNotifications
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $notifications = Notification::where('user_id', Auth::id())
                ->where('read', false)
                ->get();
            
            view()->share('unreadNotifications', $notifications);
        }

        return $next($request);
    }
}