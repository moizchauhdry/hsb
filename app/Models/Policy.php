<?php

namespace App\Models;

use App\Traits\FormatDatesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
    public function scopePoliciesList($query, $filter, $slug = null)
    {
        $role = Auth::user()->roles[0];
        // $query->with(['client', 'insurer', 'agency', 'cob']);

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

        if ($filter) {
            $query->when($filter['date_type'] == 'date_of_issuance', function ($q) use ($filter) {
                $q->whereYear('p.date_of_issuance', $filter['year']);
                $q->whereMonth('p.date_of_issuance', $filter['month']);
            });
    
            $query->when($filter['date_type'] == 'policy_period_start', function ($q) use ($filter) {
                $q->whereYear('p.policy_period_start', $filter['year']);
                $q->whereMonth('p.policy_period_start', $filter['month']);
            });
    
            $query->when($filter['date_type'] == 'policy_period_end', function ($q) use ($filter) {
                $q->whereYear('p.policy_period_end', $filter['year']);
                $q->whereMonth('p.policy_period_end', $filter['month']);
            });
    
            $query->when($filter['date_type'] == 'created_at', function ($q) use ($filter) {
                $q->whereYear('p.created_at', $filter['year']);
                $q->whereMonth('p.created_at', $filter['month']);
            });
    
            if ($slug == 'sales') {
                $query->when($filter['policy_type'], function ($q) use ($filter) {
                    $q->where('p.policy_type', $filter['policy_type']);
                });
            } elseif ($slug == 'renewal') {
                $query->where('p.policy_type', 'renewal');
            }
    
            $query->when($filter['client'], function ($q) use ($filter) {
                $q->where('p.client_id', $filter['client']);
            });
    
            $query->when($filter['agency'], function ($q) use ($filter) {
                $q->where('p.agency_id', $filter['agency']);
            });
    
            $query->when($filter['insurer'], function ($q) use ($filter) {
                $q->where('p.insurance_id', $filter['insurer']);
            });
    
            $query->when($filter['cob'], function ($q) use ($filter) {
                $q->where('p.cob_id', $filter['cob']);
            });
        }

        $query->orderBy('p.id', 'desc');

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
