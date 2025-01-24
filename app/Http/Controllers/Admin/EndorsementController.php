<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EndorsementController extends Controller
{
    public function index(Request $request)
    {
        $page_count = $request->page_count ?? 10;

        $policy_type = [];
        if ($request->policy_type) {
            $policy_type = is_array($request->policy_type) ? $request->policy_type : explode(',', $request->policy_type);
        }

        $client = [];
        if ($request->client) {
            $client = is_array($request->client) ? $request->client : explode(',', $request->client);
        }

        $agency = [];
        if ($request->agency) {
            $agency = is_array($request->agency) ? $request->agency : explode(',', $request->agency);
        }

        $insurer = [];
        if ($request->insurer) {
            $insurer = is_array($request->insurer) ? $request->insurer : explode(',', $request->insurer);
        }

        $department = [];
        if ($request->department) {
            $department = is_array($request->department) ? $request->department : explode(',', $request->department);
        }

        $group = [];
        if ($request->group) {
            $group = is_array($request->group) ? $request->group : explode(',', $request->group);
        }

        $cob = [];
        if ($request->cob) {
            $cob = is_array($request->cob) ? $request->cob : explode(',', $request->cob);
        }

        $filter = [
            'search' => $request->search ?? "",
            'date_type' => $request->date_type ?? "",
            'from_date' => $request->from_date ?? "",
            'to_date' => $request->to_date ?? "",
            'policy_type' => $policy_type,
            'client' => $client,
            'agency' => $agency,
            'insurer' => $insurer,
            'department' => $department,
            'group' => $group,
            'cob' => $cob,
        ];

        $policies = Policy::query()
            ->policiesList($filter)
            ->whereIn('policy_type', ['endorsement'])
            // ->where('p.sum_insured', 0)
            ->when($filter['search'], function ($q) use ($filter) {
                $q->where('p.id', $filter['search']);
                $q->orWhere('p.policy_no', "LIKE", "%" . $filter['search'] . "%");
            })
            ->groupBy('p.id', 'p.policy_no', 'p.client_id', 'p.policy_period_end', 'client.name', 'agency.name', 'cob.class_name')
            ->paginate($page_count)
            ->withQueryString();

        return Inertia::render('Endorsement/Index', [
            'policies' => $policies,
            'filters' => $filter
        ]);
    }
}
