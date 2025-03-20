<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class WorkOrder extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;
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
