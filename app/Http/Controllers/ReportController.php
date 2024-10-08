<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\BusinessClass;
use App\Models\Insurance;
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
            'date_type' => $request->date_type,
            'month' => $current_month,
            'month_name' => getMonthName($current_month),
            'year' => $current_year,
            'policy_type' => $request->policy_type,

            'client' => $request->client,
            'agency' => $request->agency,
            'insurer' => $request->insurer,
            'cob' => $request->cob,
        ];

        session(['filter' => $filter]);

        $query = Policy::with(['client', 'insurance', 'agency', 'businessClass']);
        
        $query->when($filter['date_type'] == 'date_of_insurance', function ($q) use ($filter) {
            $q->whereYear('date_of_insurance', $filter['year']);
            $q->whereMonth('date_of_insurance', $filter['month']);
        });

        $query->when($filter['date_type'] == 'policy_start_period', function ($q) use ($filter) {
            $q->whereYear('policy_start_period', $filter['year']);
            $q->whereMonth('policy_start_period', $filter['month']);
        });

        $query->when($filter['date_type'] == 'policy_end_period', function ($q) use ($filter) {
            $q->whereYear('policy_end_period', $filter['year']);
            $q->whereMonth('policy_end_period', $filter['month']);
        });

        $query->when($filter['date_type'] == 'created_at', function ($q) use ($filter) {
            $q->whereYear('created_at', $filter['year']);
            $q->whereMonth('created_at', $filter['month']);
        });

        $query->when($filter['policy_type'], function ($q) use ($filter) {
            $q->where('orignal_endorsment', $filter['policy_type']);
        });

        $query->when($filter['client'], function ($q) use ($filter) {
            $q->where('client_id', $filter['client']);
        });

        $query->when($filter['agency'], function ($q) use ($filter) {
            $q->where('agency_id', $filter['agency']);
        });

        $query->when($filter['insurer'], function ($q) use ($filter) {
            $q->where('insurance_id', $filter['insurer']);
        });

        $query->when($filter['cob'], function ($q) use ($filter) {
            $q->where('class_of_business_id', $filter['cob']);
        });

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
        $insurers = Insurance::get();
        $agencies = Agency::get();
        $cobs = BusinessClass::get();

        $data = [
            'clients' => $clients,
            'insurers' => $insurers,
            'agencies' => $agencies,
            'cobs' => $cobs,
        ];

        return Inertia::render('Report/SaleReport', [
            'policies' => $policies,
            'data' => $data,
            'filter' => $filter,
            'grand_total' => $grand_total,
        ]);
    }
}
