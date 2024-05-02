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
        $work_orders = WorkOrder::count();
        $customer_accounts = CustomerAccount::count();

        return Inertia::render('Dashboard', [
            'work_orders' => $work_orders,
            'customer_accounts' => $customer_accounts,
        ]);
    }
}
