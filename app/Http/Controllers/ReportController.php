<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function saleReport(Request $request)
    {
        $current_month = $request->month ?? Carbon::now()->format('m');
        $current_year = $request->year ?? Carbon::now()->format('Y');

        $filter = [
            'month' => $current_month,
            'month_name' => getMonthName($current_month),
            'year' => $current_year,
        ];

        session(['filter' => $filter]);

        $query = Policy::with(['client', 'insurance', 'agency', 'businessClass']);
        
        $query->whereYear('created_at', $filter['year']);
        $query->whereMonth('created_at', $filter['month']);

        $policies = $query
            ->orderBy('id', 'desc')
            ->paginate(10000)
            ->withQueryString()
            ->through(fn($policy) => [
                'data' => $policy,
            ]);

        $grand_total = [
            'sum_insured' => $query->sum('sum_insured'),
            'gross_premium' => $query->sum('gross_premium'),
            'net_premium' => $query->sum('net_premium'),
        ];

        $clients = User::role('client')->get();

        return Inertia::render('Report/SaleReport', [
            'policies' => $policies,
            'clients' => $clients,
            'filter' => $filter,
            'grand_total' => $grand_total,
        ]);
    }
}
