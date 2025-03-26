<?php

namespace App\Http\Middleware;

use App\Models\LoginLog;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AutoLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('lastActivityTime');
            $timeout = 60 * 60; // 1 hour in seconds
            // $timeout = 60;

            if ($lastActivity && (time() - $lastActivity) > $timeout) {

                $user = Auth::user();
                $loginLog = [
                    'user_id' => $user->id,
                    'event' => 'auto logout',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ];
                LoginLog::create($loginLog);

                Auth::logout();
                session()->flush();

                Log::info('auto logout');

                return redirect('/login')->with('message', 'You have been logged out due to inactivity.');
            }

            session(['lastActivityTime' => time()]);
        }

        return $next($request);
    }
}
