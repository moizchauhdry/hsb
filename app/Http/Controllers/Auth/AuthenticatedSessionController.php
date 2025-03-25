<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\LoginLog;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        if ($user) {
            $this->createLoginLog($user, 'login');
        }

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            $this->createLoginLog($user, 'logout');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Handle session expiration logout.
     */
    public function autoLogoutInactiveUsers()
    {
        $inactiveUsers = LoginLog::whereNull('logout_at')
            ->where('login_at', '<', Carbon::now()->subMinutes(config('session.lifetime')))
            ->get();

        foreach ($inactiveUsers as $log) {
            $log->update(['logout_at' => Carbon::now()]);
        }
    }

    private function createLoginLog($user, string $event = 'login')
    {
        $loginLog = [
            'user_id' => $user->id,
            'event' => $event,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        if ($event == 'login') {
            $loginLog['login_at'] = Carbon::now();
        } else {
            $loginLog['logout_at'] = Carbon::now();
        }

        LoginLog::create($loginLog);

        return true;
    }
}
