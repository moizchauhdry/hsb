<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function billing_address()
    {
        return $this->hasOne(WorkOrderAddress::class, 'wo_id', 'id')->where('wo_addr_type', 'billing');
    }

    public function items()
    {
        return $this->hasMany(WorkOrderItem::class, 'wo_id', 'id');
    }
}
