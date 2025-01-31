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

    public function scopeClientGroupList($query, $filter)
    {
        $user = Auth::user();
        $role = $user->roles[0];

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

            $query->when($filter['date_type'], function ($q) use ($filter) {
                if ($filter['from_date']) {
                    $q->where('p.' . $filter['date_type'], ">=", $filter['from_date']);
                }
                if ($filter['to_date']) {
                    $q->where('p.' . $filter['date_type'], "<=", $filter['to_date']);
                }
            });

            $query->when($filter['policy_type'], function ($q) use ($filter) {
                $q->whereIn('p.policy_type', $filter['policy_type']);
            });

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

        return $query;
    }
}
