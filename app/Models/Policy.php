<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(User::class)->where('role_users_id', 2);
    }

    public function insurance()
    {
        return $this->belongsTo(Insurance::class,'insurance_id','id');
    }

    public function insurer()
    {
        return $this->belongsTo(Insurance::class,'insurance_id','id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function businessClass()
    {
        return $this->belongsTo(BusinessClass::class,'class_of_business_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function policyInstallment()
    {
        return $this->hasMany(PolicyInstallmentPlan::class,'policy_id','id');

    }
}
