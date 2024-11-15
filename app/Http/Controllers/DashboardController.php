<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BusinessClass;
use App\Models\CustomerAccount;
use App\Models\Policy;
use App\Models\User;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $policies = Policy::count();
        $clients = User::role('client')->count();
        $users = User::withoutRole('client')->count();
        $cob = BusinessClass::count();

        $data = [
            'policies' => $policies,
            'cob' => $cob,
            'clients' => $clients,
            'users' => $users,
        ];

        return Inertia::render('Dashboard/Index', [
            'data' => $data,
        ]);
    }
}
