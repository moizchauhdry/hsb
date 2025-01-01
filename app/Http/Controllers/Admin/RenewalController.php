<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ClientGroup;
use App\Models\Policy;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RenewalController extends Controller
{
    public function index(Request $request)
    {
        $page_count = $request->page_count ?? 10;

        $filter = [
            'search' => $request->search,

            'date_type' => $request->date_type,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,

            'policy_type' => $request->policy_type,
            'client' => $request->client,
            'agency' => $request->agency,
            'insurer' => $request->insurer,
            'cob' => $request->cob,
            'department' => $request->department,
            'group' => $request->group,
        ];

        $policies = Policy::query()
            // ->select(
            //     'p.id as p_id',
            //     'p.policy_no as policy_no',
            //     'p.client_id as client_id',
            //     'p.policy_period_end as expiry_date',
            //     'p.policy_type as policy_type',
            //     'client.name as client_name',
            //     'agency.name as agency_name',
            //     'cob.class_name as cob_name',
            //     DB::raw('COUNT(DISTINCT pc.id) as claim_count'),
            // )
            ->policiesList($filter)
            ->whereIn('policy_type', ['renewal'])
            ->when($filter['search'], function ($q) use ($filter) {
                $q->where('p.id', $filter['search']);
                $q->orWhere('p.policy_no', "LIKE", "%" . $filter['search'] . "%");
            })
            ->groupBy('p.id', 'p.policy_no', 'p.client_id', 'p.policy_period_end', 'client.name', 'agency.name', 'cob.class_name')
            ->paginate($page_count)
            ->withQueryString();

        return Inertia::render('Policy/Index', [
            'policies' => $policies,
            'filter' => $filter,
            'filter_route' => 'renewals',
        ]);
    }

    public function clientList(Request $request)
    {
        $page_count = $request->page_count ?? 10;

        $groups = ClientGroup::clientGroupList($request->all())
            ->paginate($page_count)
            ->withQueryString();

        return Inertia::render('Client/Group', [
            'page_type' => "renewal",
            'groups' => $groups,
            'filter' => [],
        ]);
    }
}
