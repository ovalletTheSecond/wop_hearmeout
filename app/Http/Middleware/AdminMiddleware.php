<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;



class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $adminEmail = env('ADMIN_EMAIL');
        $user = $request->user();

        if (!$user || $user->email !== $adminEmail) {
            return redirect()->route('home')->with('error', 'Accès non autorisé.');
        }

        return $next($request);
    }
}