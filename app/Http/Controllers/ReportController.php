<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Payment;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request, $slug)
    {
        $report = true;
        // $query = Policy::policiesList($request->all(), 'reports', $report);

        $query = Payment::from('payments');

        $query->leftJoin('policies', 'policies.id', 'payments.policy_id');
        $query->leftJoin('users as client', 'client.id', '=', 'policies.client_id');
        $query->leftJoin('agencies as agency', 'agency.id', '=', 'policies.agency_id');
        $query->leftJoin('business_classes as cob', 'cob.id', '=', 'policies.cob_id');
        $query->leftJoin('departments as d', 'd.id', '=', 'cob.department_id');

        $query->select(
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
            DB::raw('(payments.gross_premium - payments.gross_premium_received) as gross_premium_outstanding'),
            'payments.brokerage_amount as brokerage_amount',
            'payments.brokerage_amount_received as brokerage_amount_received',
            DB::raw('(payments.brokerage_amount - payments.brokerage_amount_received) as brokerage_amount_outstanding'),
        );

        $grand_total = [
            'sum_insured' => $query->sum('payments.sum_insured'),
            'net_premium' => $query->sum('payments.net_premium'),

            'gross_premium' => $query->sum('payments.gross_premium'),
            'gross_premium_received' => $query->sum('payments.gross_premium_received'),
            'gross_premium_outstanding' => $query->sum('payments.gross_premium') - $query->sum('payments.gross_premium_received'),

            'brokerage_amount' => $query->sum('payments.brokerage_amount'),
            'brokerage_amount_received' => $query->sum('payments.brokerage_amount_received'),
            'brokerage_amount_outstanding' => $query->sum('payments.brokerage_amount') - $query->sum('payments.brokerage_amount_received'),
        ];

        $payments = $query->orderBy('policies.date_of_issuance','desc')->paginate(25)->withQueryString();


        // dd($payments);

        return Inertia::render('Report/ListReport', [
            'payments' => $payments,
            'filter' => session('filter'),
            'grand_total' => $grand_total,
            'slug' => $slug,
        ]);
    }

    public function export($slug)
    {
        $filter = session('filter', []);
        return Excel::download(new ReportExport($filter, $slug), 'data.xlsx');
    }
}
