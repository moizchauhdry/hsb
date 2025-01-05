<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BusinessClass;
use App\Models\ClientGroup;
use App\Models\CustomerAccount;
use App\Models\Group;
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

        $policies = Policy::policiesList([], 'policies')->get();
        $policies_count = count($policies);

        $total_revenue = $policies->sum('brokerage_amount');
        $total_sum_insured = $policies->sum('sum_insured');
        $total_commission_collected = $policies->sum('brokerage_received_amount');
        $gp_collected_outstanding = $policies->sum('gp_collected_outstanding');

        $endorsements = Policy::policiesList([], 'endorsements')->get();
        $endorsements_count = count($endorsements);

        $renewals = Policy::policiesList([], 'renewals')->get();
        $renewals_count = count($renewals);

        $policy_claims = PolicyClaim::policyClaimList([])->get();
        $policy_claim_count = count($policy_claims);

        $cob_groups = Group::get();
        $cob_groups_count = count($cob_groups);

        $cobs = BusinessClass::get();
        $cobs_count = count($cobs);

        $client_groups = ClientGroup::get();
        $client_groups_count = count($client_groups);

        $clients = User::role('client')->get();
        $clients_count = count($clients);

        // $users = User::withoutRole('client')->count();

        // if ($role->id == 1) {
        //     $client_count = User::role('client')->count();
        //     $cob_count = BusinessClass::count();
        // } else {
        //     $client_count = UserClient::where('user_id', $user->id)->count('client_id');
        //     $cob_count = UserCob::where('user_id', $user->id)->count('cob_id');
        // }

        $monthly_revenue = DB::table('policies')
            ->select(DB::raw('SUM(brokerage_amount) as total'), DB::raw('MONTH(date_of_issuance) as month'))
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $revenue_data = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenue_data[] = $monthly_revenue[$i] ?? 0;
        }

        $gross_premium_amount = DB::table('policies')
            ->select(
                DB::raw('IFNULL(SUM(gross_premium), 0) as gross_premium_amount'),
                DB::raw('CONCAT(MONTHNAME(date_of_issuance), " ", YEAR(date_of_issuance)) as month_name'),
                DB::raw('MONTH(date_of_issuance) as month')
            )
            ->where('policy_type', ['new', 'renewal'])
            ->whereNotNull('date_of_issuance')
            ->groupBy('month_name', 'month')
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('gross_premium_amount', 'month_name')
            ->toArray();

        $gross_premium_collected = DB::table('policies')
            ->select(
                DB::raw('IFNULL(SUM(gp_collected), 0) as gross_premium_collected'),
                DB::raw('CONCAT(MONTHNAME(date_of_issuance), " ", YEAR(date_of_issuance)) as month_name'),
                DB::raw('MONTH(date_of_issuance) as month')
            )
            ->where('policy_type', ['new', 'renewal'])
            ->whereNotNull('date_of_issuance')
            ->groupBy('month_name', 'month')
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('gross_premium_collected', 'month_name')
            ->toArray();

        $gross_premium_outstanding = DB::table('policies')
            ->select(
                DB::raw('IFNULL(SUM(gross_premium - gp_collected), 0) as gross_premium_outstanding'),
                DB::raw('CONCAT(MONTHNAME(date_of_issuance), " ", YEAR(date_of_issuance)) as month_name'),
                DB::raw('MONTH(date_of_issuance) as month')
            )
            ->where('policy_type', ['new', 'renewal'])
            ->whereNotNull('date_of_issuance')
            ->groupBy('month_name', 'month')
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('gross_premium_outstanding', 'month_name')
            ->toArray();


        $data = [
            'policies_count' => $policies_count,
            'renewals_count' => $renewals_count,
            'endorsements_count' => $endorsements_count,
            'cob_groups_count' => $cob_groups_count,
            'cobs_count' => $cobs_count,
            'client_groups_count' => $client_groups_count,
            'clients_count' => $clients_count,
            'policy_claim_count' => $policy_claim_count,

            'total_revenue' => $total_revenue,
            'total_sum_insured' => $total_sum_insured,
            'total_commission_collected' => $total_commission_collected,
            'gp_collected_outstanding' => $gp_collected_outstanding,

            'revenue_data' => $revenue_data,

            'gross_premium_amount' => $gross_premium_amount,
            'gross_premium_collected' => $gross_premium_collected,
            'gross_premium_outstanding' => $gross_premium_outstanding,
        ];

        // dd($data);

        return Inertia::render('Dashboard/Index', [
            'data' => $data,
        ]);
    }
}
