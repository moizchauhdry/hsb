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

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function classOfBusiness()
    {
        return $this->belongsTo(ClassOfBusiness::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
