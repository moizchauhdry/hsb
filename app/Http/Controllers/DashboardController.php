<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BusinessClass;
use App\Models\CustomerAccount;
use App\Models\Policy;
use App\Models\PolicyClaim;
use App\Models\User;
use App\Models\UserClient;
use App\Models\UserCob;
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
        $role = $user->roles[0];

        $policies = Policy::policiesList([])->count();
        $users = User::withoutRole('client')->count();

        if ($role->id == 1) {
            $client_count = User::role('client')->count();
            $cob_count = BusinessClass::count();
        } else {
            $client_count = UserClient::where('user_id', $user->id)->count('client_id');
            $cob_count = UserCob::where('user_id', $user->id)->count('cob_id');
        }

        $claim_count = PolicyClaim::policyClaimList([])->count();
        
        $data = [
            'policies' => $policies,
            'cob' => $cob_count,
            'clients' => $client_count,
            'users' => $users,
            'claims' => $claim_count,
        ];

        return Inertia::render('Dashboard/Index', [
            'data' => $data,
        ]);
    }
}
