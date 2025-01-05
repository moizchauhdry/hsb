<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Policy;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request, $slug)
    {
        $report = true;
        $query = Policy::policiesList($request->all(), 'reports', $report);

        $grand_total = [
            'sum_insured' => $query->sum('sum_insured'),
            'net_premium' => $query->sum('net_premium'),

            'gross_premium' => $query->sum('gross_premium'),
            'gross_premium_collected' => $query->sum('gp_collected'),
            'gross_premium_outstanding' => $query->sum('gross_premium') - $query->sum('gp_collected'),

            'brokerage_amount' => $query->sum('brokerage_amount'),
            'brokerage_received_amount' => $query->sum('brokerage_received_amount'),
            'brokerage_amount_outstanding' => $query->sum('brokerage_amount') - $query->sum('brokerage_received_amount'),
        ];

        $policies = $query
            ->paginate(25)
            ->withQueryString()
            ->through(fn($policy) => [
                'data' => $policy,
                'client_name' => getClientName($policy->client_id),
                'insurer_name' => $policy->insurer->name ?? null,
                'agency_name' => $policy->agency->name ?? null,
                'department_name' => $policy->department->name ?? null,
                'cob_name' => $policy->cob->class_name ?? null,
            ]);

        return Inertia::render('Report/ListReport', [
            'policies' => $policies,
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
