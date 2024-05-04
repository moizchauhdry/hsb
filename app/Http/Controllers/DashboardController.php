<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CustomerAccount;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data = [];

        return Inertia::render('Dashboard/Index', [
            'data' => $data,
        ]);
    }
}
