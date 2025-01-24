<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ClientGroup;
use App\Models\Policy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RenewalController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());

        $role = Auth::user()->roles[0];

        $page_count = $request->page_count ?? 10;

        $filter = [
            'search' => $request->search,
            'date_type' => $request->date_type,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'client_ids' => $request->client_ids ?? session('filter')['client_ids'],
            'renewal_ids' => $request->renewal_ids ?? session('filter')['renewal_ids'],
            'agency' => $request->agency,
            'insurer' => $request->insurer,
            'cob' => $request->cob,
            'department' => $request->department,
            'group' => $request->group,
        ];

        session(['filter' => $filter]);

        $renewal_ids = is_array($filter['renewal_ids']) ? $filter['renewal_ids'] : explode(',', $filter['renewal_ids']);

        $query = Policy::from('policies as p');
        $query->whereIn('p.id', $renewal_ids);

        $query->leftJoin('policy_claims as pc', 'pc.policy_id', '=', 'p.id');
        $query->leftJoin('users as client', 'client.id', '=', 'p.client_id');
        $query->leftJoin('agencies as agency', 'agency.id', '=', 'p.agency_id');
        $query->leftJoin('business_classes as cob', 'cob.id', '=', 'p.cob_id');
        $query->leftJoin('departments as d', 'd.id', '=', 'cob.department_id');
        $query->leftJoin('groups as g', 'g.id', '=', 'cob.group_id');
        $query->leftJoin('policy_renewal_statuses as renewal_status', 'renewal_status.id', '=', 'p.renewal_status_id');

        if ($role->id != 1) {
            $query->leftJoin('user_cobs as uc', function ($join) {
                $join->on('uc.cob_id', '=', 'p.cob_id')->where('uc.user_id', auth()->id());
            })
                ->leftJoin('user_clients as ucl', function ($join) {
                    $join->on('ucl.client_id', '=', 'p.client_id')->where('ucl.user_id', auth()->id());
                })
                ->where(function ($q) {
                    $q->whereNotNull('uc.id')->orWhereNotNull('ucl.id');
                });
        }

        $query->select(
            'p.id as p_id',
            'p.policy_no as policy_no',
            'p.base_doc_no as base_doc_no',
            'p.client_id as client_id',
            'p.policy_period_end as expiry_date',
            'p.policy_type as policy_type',
            'p.lead_type as policy_lead_type',
            'renewal_status.name as renewal_status',
            'client.name as client_name',
            'agency.name as agency_name',
            'cob.class_name as cob_name',
            DB::raw('COUNT(DISTINCT pc.id) as claim_count'),
        );

        $query->groupBy(
            'p.id',
            'p.policy_no',
            'p.base_doc_no',
            'p.client_id',
            'p.policy_period_end',
            'p.policy_type',
            'p.lead_type',
            'renewal_status.name',
            'client.name',
            'agency.name',
            'cob.class_name'
        );

        $policies = $query->paginate($page_count)->withQueryString();

        return Inertia::render('Renewal/Index', [
            'policies' => $policies,
            'filter' => $filter,
        ]);
    }

    public function clientList(Request $request)
    {
        $role = Auth::user()->roles[0];

        $page_count = $request->page_count ?? 10;

        $policy_type = [];
        if ($request->policy_type) {
            $policy_type = is_array($request->policy_type) ? $request->policy_type : explode(',', $request->policy_type);
        }

        $client_ids = [];
        if ($request->client_ids) {
            $client_ids = is_array($request->client_ids) ? $request->client_ids : explode(',', $request->client_ids);
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
            'client_ids' => $client_ids,
            'agency' => $agency,
            'insurer' => $insurer,
            'department' => $department,
            'group' => $group,
            'cob' => $cob,
        ];

        // dd($filter,  $request->all());

        $query = ClientGroup::query()
            ->from('client_groups as group')
            ->select([
                'group.id as group_id',
                'group.code as group_code',
                'group.name as group_name',
                DB::raw('COUNT(DISTINCT client.id) as client_count'),
                DB::raw('GROUP_CONCAT(DISTINCT client.id) as client_ids'),
                DB::raw('COUNT(DISTINCT p.id) as renewal_count'),
                DB::raw('GROUP_CONCAT(DISTINCT p.id) as renewal_ids'),
            ])
            ->leftJoin('users as client', 'client.client_group_code', '=', 'group.code')
            ->leftJoin('policies as p', 'p.client_id', '=', 'client.id')
            ->leftJoin('business_classes as cob', 'p.cob_id', '=', 'cob.id')
            ->whereIn('p.policy_type', ['new', 'renewal', 'cover'])
            ->groupBy('group.id', 'group.code', 'group.name')
            ->orderBy('renewal_count', 'desc');

        if ($role->id != 1) {
            $query->leftJoin('user_cobs as uc', function ($join) {
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
            $query->when($filter['search'], function ($q) use ($filter) {
                $q->where('group.code', $filter['search'])->orWhere('group.name', 'LIKE', '%' . $filter['search'] . '%');
            });

            if ($filter['date_type'] && $filter['from_date'] && $filter['to_date']) {
                $query->where('p.policy_period_end', ">=", $filter['from_date']);
                $query->where('p.policy_period_end', "<=", $filter['to_date']);
            } else {
                $startOfNextMonth = Carbon::now()->addMonth()->startOfMonth()->format('Y-m-d');
                $endOfNextMonth = Carbon::now()->addMonth()->endOfMonth()->format('Y-m-d');

                $query->whereDate('p.policy_period_end', '>=', $startOfNextMonth);
                $query->whereDate('p.policy_period_end', '<=', $endOfNextMonth);
            }

            $query->when(!empty($filter['client_ids']), function ($q) use ($filter) {
                $q->whereIn('p.client_id', $filter['client_ids']);
            });

            $query->when(!empty($filter['agency']), function ($q) use ($filter) {
                $q->whereIn('p.agency_id', $filter['agency']);
            });

            $query->when(!empty($filter['insurer']), function ($q) use ($filter) {
                $q->whereIn('p.insurer_id', $filter['insurer']);
            });

            $query->when($filter['cob'], function ($q) use ($filter) {
                $q->whereIn('p.cob_id', $filter['cob']);
            });

            $query->when($filter['department'], function ($q) use ($filter) {
                $q->whereIn('cob.department_id', $filter['department']);
            });

            $query->when($filter['group'], function ($q) use ($filter) {
                $q->whereIn('cob.group_id', $filter['group']);
            });
        }

        $groups =  $query->paginate($page_count)->withQueryString();

        return Inertia::render('Renewal/Client', [
            'groups' => $groups,
            'filters' => $filter,
        ]);
    }
}
