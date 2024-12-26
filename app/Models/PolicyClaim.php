<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PolicyClaim extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    public function scopePolicyClaimList($query, $filter)
    {
        $role = Auth::user()->roles[0];

        $query->select(
            'pc.*',
            'p.policy_no as policy_no',
            'u.name as client_name',
        )
            ->from('policy_claims as pc')
            ->join('policies as p', 'p.id', 'pc.policy_id')
            ->leftJoin('users as u', 'u.id', 'p.client_id');

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
                $q->where('pc.id', $filter['search']);
                $q->orWhere('pc.policy_id', $filter['search']);
                $q->orWhere('p.policy_no', "LIKE", "%" . $filter['search'] . "%");
                $q->orWhere('u.name', "LIKE", "%" . $filter['search'] . "%");
            });

            $query->when($filter['date_type'], function ($q) use ($filter) {
                $q->whereYear('pc.' . $filter['date_type'], $filter['year']);
                $q->whereMonth('pc.' . $filter['date_type'], $filter['month']);
            });

            $query->when($filter['policy_id'], function ($q) use ($filter) {
                $q->where('p.id', $filter['policy_id']);
            });

            $query->when($filter['client'], function ($q) use ($filter) {
                $q->where('p.client_id', $filter['client']);
            });
        }

        return $query;
    }
}
