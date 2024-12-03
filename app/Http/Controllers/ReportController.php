<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Agency;
use App\Models\BusinessClass;
use App\Models\Insurance;
use App\Models\Policy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request, $slug)
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

        $query = Policy::policiesList($filter, $slug);

        $grand_total = [
            'sum_insured' => $query->sum('sum_insured'),
            'gross_premium' => $query->sum('gross_premium'),
            'net_premium' => $query->sum('net_premium'),
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

        return Inertia::render('Report/ListReport', [
            'policies' => $policies,
            'data' => $data,
            'filter' => $filter,
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
