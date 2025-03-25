<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Audit;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuditController extends Controller
{
     public function index(Request $request)
    {
        $audits = Audit::latest()->paginate(10);

        return Inertia::render('Audit/Index', [
            'audits' => $audits
        ]);
    }
    public function userAudits($id)
{
    $user = User::findOrFail($id);
    $audits = Audit::where('user_id', $id)->latest()->paginate(10);

    return Inertia::render('Audit/UserAudit', [
        'user' => $user,
        'audits' => $audits
    ]);
}
}

