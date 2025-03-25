<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LoginLog extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'user_id',
        'event',  // e.g., 'login', 'logout'
        'ip_address',
        'login_at',
        'logout_at',
        'user_agent',
    ];

    /**
     * Define relationship: Each log belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
