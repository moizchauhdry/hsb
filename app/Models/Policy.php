<?php

namespace App\Models;

use App\Traits\FormatDatesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory, FormatDatesTrait;

    protected $guarded = [];
    protected $dates = ['date_of_insurance', 'policy_start_period', 'policy_end_period', 'excel_import_at'];

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

    public function policyInstallment()
    {
        return $this->hasMany(PolicyInstallmentPlan::class, 'policy_id', 'id');
    }

    public function scopePoliciesList($query, $filter, $slug)
    {
        $query->with(['client', 'insurance', 'agency', 'businessClass']);

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

        if ($slug == 'sales') {
            $query->when($filter['policy_type'], function ($q) use ($filter) {
                $q->where('policy_type', $filter['policy_type']);
            });
        } elseif ($slug == 'renewal') {
            $query->where('policy_type', 'renewal');
        }

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

        $query->orderBy('id', 'desc');

        return $query;
    }
}
