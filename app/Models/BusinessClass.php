<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessClass extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function businessInsurance()
    {
        return $this->hasMany(BusinessClassInsurance::class,'business_class_id','id');
    }
}
