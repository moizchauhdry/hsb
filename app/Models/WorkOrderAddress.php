<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;

class WorkOrderAddress extends Model implements Auditable
{
    use HasFactory,Notifiable, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

}
