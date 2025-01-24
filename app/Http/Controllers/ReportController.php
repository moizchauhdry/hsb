<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Payment;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request, $slug)
    {
        // dd($request->all());
        $user = Auth::user();
        $role = $user->roles[0];

        $filter = [
            'search' => $request['search'] ?? "",
            'date_type' => 'date_of_issuance',
            'from_date' => $request['from_date'] ?? "",
            'to_date' => $request['to_date'] ?? "",
            // 'policy_type' => $request['policy_type'] ?? "",
            // 'client' => $request['client'] ?? "",
            'agency' => $request['agency'] ?? "",
            'insurer' => $request['insurer'] ?? "",
            'cob' => $request['cob'] ?? "",
            'department' => $request['department'] ?? "",
            'group' => $request['group'] ?? "",
        ];

        // dd($filter);

        session(['filter' => $filter]);

        $policies = Policy::policiesList($filter, 'policies')->get();

        $payment_query = Payment::from('payments');

        $payment_query->join('policies as p', 'p.id', 'payments.policy_id');
        $payment_query->leftJoin('users as client', 'client.id', '=', 'p.client_id');
        $payment_query->leftJoin('agencies as agency', 'agency.id', '=', 'p.agency_id');
        $payment_query->leftJoin('business_classes as cob', 'cob.id', '=', 'p.cob_id');
        $payment_query->leftJoin('departments as d', 'd.id', '=', 'cob.department_id');

        if ($role->id != 1) {
            $payment_query->leftJoin('user_cobs as uc', function ($join) {
                $join->on('uc.cob_id', '=', 'p.cob_id')->where('uc.user_id', auth()->id());
            })
                ->leftJoin('user_clients as ucl', function ($join) {
                    $join->on('ucl.client_id', '=', 'p.client_id')->where('ucl.user_id', auth()->id());
                })
                ->where(function ($q) {
                    $q->whereNotNull('uc.id')->orWhereNotNull('ucl.id');
                });
        }

        if ($filter) {

            $payment_query->when(!empty($filter['date_type']), function ($q) use ($filter) {
                if ($filter['from_date']) {
                    $q->where('p.' . $filter['date_type'], ">=", $filter['from_date']);
                }
                if ($filter['to_date']) {
                    $q->where('p.' . $filter['date_type'], "<=", $filter['to_date']);
                }
            });

            // $payment_query->when($filter['policy_type'], function ($q) use ($filter) {
            //     $types = is_array($filter['policy_type']) ? $filter['policy_type'] : explode(',', $filter['policy_type']);
            //     $q->whereIn('p.policy_type', $types);
            // });

            // $payment_query->when(!empty($filter['client']), function ($q) use ($filter) {
            //     $clients = is_array($filter['client']) ? $filter['client'] : explode(',', $filter['client']);
            //     $q->whereIn('p.client_id', $clients);
            // });

            // $payment_query->when(!empty($filter['agency']), function ($q) use ($filter) {
            //     $agencies = is_array($filter['agency']) ? $filter['agency'] : explode(',', $filter['agency']);
            //     $q->whereIn('p.agency_id', $agencies);
            // });

            // $payment_query->when(!empty($filter['insurer']), function ($q) use ($filter) {
            //     $insurers = is_array($filter['insurer']) ? $filter['insurer'] : explode(',', $filter['insurer']);
            //     $q->whereIn('p.insurer_id', $insurers);
            // });

            // $payment_query->when($filter['cob'], function ($q) use ($filter) {
            //     $cobs = is_array($filter['cob']) ? $filter['cob'] : explode(',', $filter['cob']);
            //     $q->whereIn('p.cob_id', $cobs);
            // });

            // $payment_query->when($filter['department'], function ($q) use ($filter) {
            //     $departments = is_array($filter['department']) ? $filter['department'] : explode(',', $filter['department']);
            //     $q->whereIn('cob.department_id', $departments);
            // });

            // $payment_query->when($filter['group'], function ($q) use ($filter) {
            //     $groups = is_array($filter['group']) ? $filter['group'] : explode(',', $filter['group']);
            //     $q->whereIn('cob.group_id', $groups);
            // });
        }

        $payment_query->select(
            'p.id as p_id',
            'p.policy_no as policy_no',
            'p.base_doc_no as base_doc_no',
            'p.client_id as client_id',
            'p.policy_period_end as expiry_date',
            'p.policy_type as policy_type',

            'client.name as client_name',
            'cob.class_name as cob_name',

            'payments.sum_insured as sum_insured',
            'payments.net_premium as net_premium',
            'payments.gross_premium as gross_premium',
            'payments.gross_premium_received as gross_premium_received',
            DB::raw('(payments.gross_premium - payments.gross_premium_received) as gross_premium_outstanding'),
            'payments.brokerage_amount as brokerage_amount',
            'payments.brokerage_amount_received as brokerage_amount_received',
            DB::raw('(payments.brokerage_amount - payments.brokerage_amount_received) as brokerage_amount_outstanding'),
        );

        $grand_total = [
            'sum_insured' => $policies->sum('sum_insured'),
            'net_premium' => $payment_query->sum('payments.net_premium'),

            'gross_premium' => $policies->sum('gross_premium'),
            'gross_premium_received' => $payment_query->sum('payments.gross_premium_received'),
            'gross_premium_outstanding' => $policies->sum('gross_premium') - $payment_query->sum('payments.gross_premium_received'),

            'brokerage_amount' => $payment_query->sum('payments.brokerage_amount'),
            'brokerage_amount_received' => $payment_query->sum('payments.brokerage_amount_received'),
            'brokerage_amount_outstanding' => $payment_query->sum('payments.brokerage_amount') - $payment_query->sum('payments.brokerage_amount_received'),
        ];

        $payments = $payment_query->orderBy('p.date_of_issuance', 'desc')->paginate(25)->withQueryString();

        $all_payments = Payment::from('payments')
            ->select(
                'payments.brokerage_amount_received as brokerage_amount_received',
            )->where('payments.policy_id', NULL);

        $miscellaneous_paid_amount = $all_payments->sum('payments.brokerage_amount_received');

        return Inertia::render('Report/ListReport', [
            'payments' => $payments,
            'filter' => session('filter'),
            'grand_total' => $grand_total,
            'miscellaneous_paid_amount' => $miscellaneous_paid_amount,
            'slug' => $slug
        ]);
    }

    public function export($slug)
    {
        $filter = session('filter', []);
        return Excel::download(new ReportExport($filter, $slug), 'data.xlsx');
    }
}
