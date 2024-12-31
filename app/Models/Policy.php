<?php

namespace App\Models;

use App\Traits\FormatDatesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Policy extends Model
{
    use HasFactory;

    protected $guarded = [];

    // *************************** ***** ***********************
    // *********************** RELATIONS ***********************
    // *************************** ***** ***********************
    public function insurer()
    {
        return $this->belongsTo(Insurance::class, 'insurer_id', 'id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id', 'id');
    }

    public function cob()
    {
        return $this->belongsTo(BusinessClass::class, 'cob_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function policyInstallment()
    {
        return $this->hasMany(PolicyInstallmentPlan::class, 'policy_id', 'id');
    }

    // *************************** ****** ***********************
    // *************************** SCOPES ***********************
    // *************************** ****** ***********************
    public function scopePoliciesList($query, $request, $page_type = null, $report = false)
    {
        $role = Auth::user()->roles[0];

        $filter = [
            'search' => $request['search'] ?? "",
            'date_type' => $request['date_type'] ?? "",
            'from_date' => $request['from_date'] ?? "",
            'to_date' => $request['to_date'] ?? "",
            'policy_type' => $request['policy_type'] ?? "",
            'client' => $request['client'] ?? "",
            'agency' => $request['agency'] ?? "",
            'insurer' => $request['insurer'] ?? "",
            'cob' => $request['cob'] ?? "",
            'department' => $request['department'] ?? "",
            'group' => $request['group'] ?? "",
        ];

        session(['filter' => $filter]);

        $query->from('policies as p');

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

        $query->leftJoin('policy_claims as pc', 'pc.policy_id', '=', 'p.id');
        $query->leftJoin('users as client', 'client.id', '=', 'p.client_id');
        $query->leftJoin('agencies as agency', 'agency.id', '=', 'p.agency_id');
        $query->leftJoin('business_classes as cob', 'cob.id', '=', 'p.cob_id');
        $query->leftJoin('departments as d', 'd.id', '=', 'cob.department_id');
        $query->leftJoin('groups as g', 'g.id', '=', 'cob.group_id');
        $query->join('policy_renewal_statuses as renewal_status', 'renewal_status.id', '=', 'p.renewal_status_id');

        if ($page_type == 'policies') {
            $query->whereIn('policy_type', ['new', 'cover', 'renewal']);
        }

        if ($page_type == 'renewals') {
            $query->where('policy_type', 'renewal');
        }

        if ($page_type == 'endorsements') {
            $query->where('policy_type', 'endorsements');
        }

        if ($page_type == 'leads') {
            $query->whereIn('lead_type', ['other', 'our']);
        }

        if ($filter) {
            $query->when($filter['search'], function ($q) use ($filter) {
                $q->where('p.id', $filter['search']);
                $q->orWhere('p.policy_no', "LIKE", "%" . $filter['search'] . "%");
            });

            $query->when(!empty($filter['date_type']), function ($q) use ($filter) {
                if ($filter['from_date']) {
                    $q->where('p.' . $filter['date_type'], ">=", $filter['from_date']);
                }
                if ($filter['to_date']) {
                    $q->where('p.' . $filter['date_type'], "<=", $filter['to_date']);
                }
            });

            // if ($slug == 'renewal') {
            //     if ($filter['policy_type']) {
            //         $types = is_array($filter['policy_type']) ? $filter['policy_type'] : explode(',', $filter['policy_type']);
            //         $query->whereIn('p.policy_type', $types);
            //     } else {
            //         $query->where('p.policy_type', 'renewal');
            //     }
            // } else {
            //     $query->when($filter['policy_type'], function ($q) use ($filter) {
            //         $types = is_array($filter['policy_type']) ? $filter['policy_type'] : explode(',', $filter['policy_type']);
            //         $q->whereIn('p.policy_type', $types);
            //     });
            // }

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

        if ($report == false) {
            $query->select(
                'p.id as p_id',
                'p.policy_no as policy_no',
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
                'p.client_id',
                'p.policy_period_end',
                'p.policy_type',
                'p.lead_type',
                'renewal_status.name',
                'client.name',
                'agency.name',
                'cob.class_name'
            );
        }

        if ($report == true) {
            $query->select('p.*');
        }

        $query->orderBy('p.policy_period_end', 'desc');

        return $query;
    }

    // *************************** ***** ***********************
    // *************************** CASTS ***********************
    // *************************** ***** ***********************
    protected $casts = [
        'date_of_issuance' => 'datetime',
        'policy_period_start' => 'datetime',
        'policy_period_end' => 'datetime',
        'excel_import_at' => 'datetime',
    ];

    public function getDateOfIssuanceAttribute($value)
    {
        return $this->formatDate($value);
    }

    public function getPolicyPeriodStartAttribute($value)
    {
        return $this->formatDate($value);
    }

    public function getPolicyPeriodEndAttribute($value)
    {
        return $this->formatDate($value);
    }

    public function getExcelImportAtAttribute($value)
    {
        return $this->formatDate($value);
    }

    protected function formatDate($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y') : null;
    }
}
