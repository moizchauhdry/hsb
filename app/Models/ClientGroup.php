<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientGroup extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopeClientGroupList($query, $request)
    {
        $user = Auth::user();
        $role = $user->roles[0];

        $filter = [
            'search' => $request['search'] ?? "",

            'date_type' => $request['date_type'] ?? "",
            'from_date' => $request['from_date'] ?? "",
            'to_date' => $request['to_date'] ?? "",

            'policy_type' => $request['policy_type'] ?? "",
            'client' => $request['client'] ?? "",
            'agency' => $request['agency'] ?? "",
            'insurer' => $request['insurer'] ?? "",
            'department' => $request['department'] ?? "",
            'group' => $request['group'] ?? "",
            'cob' => $request['cob'] ?? "",
        ];

        $query->from('client_groups as group')
            ->select([
                'group.id as group_id',
                'group.code as group_code',
                'group.name as group_name',
                DB::raw('COUNT(DISTINCT client.id) as client_count'),
                DB::raw('COUNT(DISTINCT p.id) as policy_count'),
                // DB::raw("COUNT(DISTINCT CASE WHEN p.policy_type IN ('new', 'renewal', 'cover') THEN p.id ELSE NULL END) as policy_count"),
                DB::raw('GROUP_CONCAT(DISTINCT client.id) as client_ids'),
                DB::raw("SUM(CASE WHEN p.policy_type = 'renewal' THEN 1 ELSE 0 END) as renewal_count"),
            ])
            ->leftJoin('users as client', 'client.client_group_code', '=', 'group.code')
            ->leftJoin('policies as p', 'p.client_id', '=', 'client.id')
            ->leftJoin('business_classes as cob', 'p.cob_id', '=', 'cob.id')
            ->whereIn('p.policy_type', ['new', 'renewal', 'cover'])
            ->groupBy('group.id', 'group.code', 'group.name')
            ->orderBy('policy_count', 'desc');


        if ($role->id != 1) {
            $query->join('user_clients', function ($join) {
                $join->on('user_clients.client_id', '=', 'client.id')->where('user_clients.user_id', auth()->id());
            })->where(function ($q) {
                $q->whereNotNull('user_clients.id');
            });
        }

        if ($filter) {
            $query->when($filter['search'], function ($q) use ($filter) {
                $q->where('group.code', $filter['search'])->orWhere('group.name', 'LIKE', '%' . $filter['search'] . '%');
            });

            $query->when($filter['date_type'], function ($q) use ($filter) {
                if ($filter['from_date']) {
                    $q->where('p.' . $filter['date_type'], ">=", $filter['from_date']);
                }
                if ($filter['to_date']) {
                    $q->where('p.' . $filter['date_type'], "<=", $filter['to_date']);
                }
            });

            $query->when($filter['policy_type'], function ($q) use ($filter) {
                $types = is_array($filter['policy_type']) ? $filter['policy_type'] : explode(',', $filter['policy_type']);
                $q->whereIn('p.policy_type', $types);
            });

            $query->when(!empty($filter['client']), function ($q) use ($filter) {
                $clients = is_array($filter['client']) ? $filter['client'] : explode(',', $filter['client']);
                $q->whereIn('p.client_id', $clients);
            });

            $query->when(!empty($filter['agency']), function ($q) use ($filter) {
                $agencies = is_array($filter['agency']) ? $filter['agency'] : explode(',', $filter['agency']);
                $q->whereIn('p.agency_id', $agencies);
            });

            $query->when(!empty($filter['insurer']), function ($q) use ($filter) {
                $insurers = is_array($filter['insurer']) ? $filter['insurer'] : explode(',', $filter['insurer']);
                $q->whereIn('p.insurer_id', $insurers);
            });

            $query->when($filter['cob'], function ($q) use ($filter) {
                $cobs = is_array($filter['cob']) ? $filter['cob'] : explode(',', $filter['cob']);
                $q->whereIn('p.cob_id', $cobs);
            });

            $query->when($filter['department'], function ($q) use ($filter) {
                $departments = is_array($filter['department']) ? $filter['department'] : explode(',', $filter['department']);
                $q->whereIn('cob.department_id', $departments);
            });

            $query->when($filter['group'], function ($q) use ($filter) {
                $groups = is_array($filter['group']) ? $filter['group'] : explode(',', $filter['group']);
                $q->whereIn('cob.group_id', $groups);
            });
        }

        return $query;
    }
}
