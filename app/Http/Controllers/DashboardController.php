<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BusinessClass;
use App\Models\ClientGroup;
use App\Models\CustomerAccount;
use App\Models\Group;
use App\Models\Payment;
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

        $payment_query = Payment::from('payments');

        $payment_query->join('policies', 'policies.id', 'payments.policy_id');
        $payment_query->leftJoin('users as client', 'client.id', '=', 'policies.client_id');
        $payment_query->leftJoin('agencies as agency', 'agency.id', '=', 'policies.agency_id');
        $payment_query->leftJoin('business_classes as cob', 'cob.id', '=', 'policies.cob_id');
        $payment_query->leftJoin('departments as d', 'd.id', '=', 'cob.department_id');

        if ($role->id != 1) {
            $payment_query->leftJoin('user_cobs as uc', function ($join) {
                $join->on('uc.cob_id', '=', 'policies.cob_id')->where('uc.user_id', auth()->id());
            })
                ->leftJoin('user_clients as ucl', function ($join) {
                    $join->on('ucl.client_id', '=', 'policies.client_id')->where('ucl.user_id', auth()->id());
                })
                ->where(function ($q) {
                    $q->whereNotNull('uc.id')->orWhereNotNull('ucl.id');
                });
        }

        $payment_query->select(
            'policies.id as p_id',
            'policies.policy_no as policy_no',
            'policies.base_doc_no as base_doc_no',
            'policies.client_id as client_id',
            'policies.policy_period_end as expiry_date',
            'policies.policy_type as policy_type',

            'payments.sum_insured as sum_insured',
            'payments.net_premium as net_premium',
            'payments.gross_premium as gross_premium',
            'payments.gross_premium_received as gross_premium_received',
            'payments.brokerage_amount as brokerage_amount',
            'payments.brokerage_amount_received as brokerage_amount_received',
        );

        $payments = $payment_query->orderBy('policies.date_of_issuance', 'desc')->get();

        $total_revenue = $payments->sum('brokerage_amount');
        $total_sum_insured = $payments->sum('sum_insured');
        $total_commission_collected = $payments->sum('brokerage_amount') - $payments->sum('brokerage_amount_received');
        $gp_collected_outstanding = $payments->sum('gross_premium') - $payments->sum('gross_premium_received');

        $endorsements = Policy::policiesList([], 'endorsements')->get();
        $endorsements_count = count($endorsements);

        $renewals = Policy::policiesList([], 'renewals')->get();
        $renewals_count = count($renewals);

        $policy_claims = PolicyClaim::policyClaimList([])->get();
        $policy_claim_count = count($policy_claims);


        // CLASSES OF BUSINESS & GROUPS
        $cobs_query = BusinessClass::from('business_classes as cob');

        if ($role->id != 1) {
            $cobs_query->join('user_cobs as uc', function ($join) {
                $join->on('uc.cob_id', '=', 'cob.id')->where('uc.user_id', auth()->id());
            })->where(function ($q) {
                $q->whereNotNull('uc.id');
            });
        }

        $cobs_count = count($cobs_query->get());

        $group_ids = $cobs_query->where('group_id', '!=', NULL)->pluck('group_id')->toArray();
        $cob_groups = Group::whereIn('id', $group_ids)->get();
        $cob_groups_count = count($cob_groups);

        // CLIENTS & GROUPS
        $clients_query = User::role('client');

        if ($role->id != 1) {
            $clients_query->join('user_clients', function ($join) {
                $join->on('user_clients.client_id', '=', 'users.id')->where('user_clients.user_id', auth()->id());
            })->where(function ($q) {
                $q->whereNotNull('user_clients.id');
            });
        }

        $clients_count = count($clients_query->get());
        $client_groups = ClientGroup::clientGroupList([])->get();
        $client_groups_count = count($client_groups);

        $monthly_revenue = DB::table('payments')
            ->select(DB::raw('SUM(brokerage_amount) as total'), DB::raw('MONTH(receipt_at) as month'))
            ->whereYear('receipt_at', 2024) // Apply year filtering at the query level
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();


        $revenue_data = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenue_data[] = $monthly_revenue[$i] ?? 0;
        }

        $gross_premium_amount = DB::table('payments')
            ->select(
                DB::raw('IFNULL(SUM(gross_premium), 0) as gross_premium_amount'),
                DB::raw('CONCAT(MONTHNAME(receipt_at), " ", YEAR(receipt_at)) as month_name'),
                DB::raw('MONTH(receipt_at) as month')
            )
            ->whereYear('receipt_at', 2024)
            ->groupBy('month_name', 'month')
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('gross_premium_amount', 'month_name')
            ->toArray();

        $gross_premium_collected = DB::table('payments')
            ->select(
                DB::raw('IFNULL(SUM(gross_premium_received), 0) as gross_premium_collected'),
                DB::raw('CONCAT(MONTHNAME(receipt_at), " ", YEAR(receipt_at)) as month_name'),
                DB::raw('MONTH(receipt_at) as month')
            )
            ->whereYear('receipt_at', 2024)
            ->groupBy('month_name', 'month')
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('gross_premium_collected', 'month_name')
            ->toArray();

        $gross_premium_outstanding = DB::table('payments')
            ->select(
                DB::raw('IFNULL(SUM(gross_premium - gross_premium_received), 0) as gross_premium_outstanding'),
                DB::raw('CONCAT(MONTHNAME(receipt_at), " ", YEAR(receipt_at)) as month_name'),
                DB::raw('MONTH(receipt_at) as month')
            )
            ->whereYear('receipt_at', 2024)
            ->groupBy('month_name', 'month')
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('gross_premium_outstanding', 'month_name')
            ->toArray();


        $data = [
            'role' => $role,

            'policies_count' => $policies_count,
            'renewals_count' => $renewals_count,
            'endorsements_count' => $endorsements_count,
            'cob_groups_count' => $cob_groups_count,
            'cobs_count' => $cobs_count,
            'client_groups_count' => $client_groups_count,
            'clients_count' => $clients_count,
            'policy_claim_count' => $policy_claim_count,

            'total_revenue' => $total_revenue,
            'total_sum_insured' => $policies->sum('sum_insured'),
            'total_commission_collected' => $total_commission_collected,
            'gp_collected_outstanding' => $gp_collected_outstanding,

            'revenue_data' => $revenue_data,

            'gross_premium_amount' => $gross_premium_amount,
            'gross_premium_collected' => $gross_premium_collected,
            'gross_premium_outstanding' => $gross_premium_outstanding,
        ];

        return Inertia::render('Dashboard/Index', [
            'data' => $data,
        ]);
    }
}
