<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Agency;
use App\Models\Policy;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ClassOfBusiness;
use App\Models\PolicyInsurance;
use Monolog\Handler\IFTTTHandler;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::orderBy('id', 'desc')->paginate(10)
            ->withQueryString()
            ->through(fn ($policy) => [
                'id' => $policy->id,
                'client_name' => $policy->client->name,
                'policy_no' => $policy->policy_no,
                'created_at' => $policy->created_at->format('d-m-Y h:i A'),
            ]);

        return Inertia::render('Policy/Index', [
            'policies' => $policies,
            'policy' => NULL,
        ]);
    }

    public function create()
    {
        $users = User::select('id', 'name')->where('role_users_id', 1)->get()->toArray();
        $clients = User::select('id', 'name')->where('role_users_id', 2)->get()->toArray();
        $insurances = Insurance::select('id', 'name')->get()->toArray();
        $agencies = Agency::select('id', 'name')->get()->toArray();
        $cobs = ClassOfBusiness::select('id', 'b_class_name')->get()->toArray();

        $data = [
            'users' => $users,
            'clients' => $clients,
            'insurances' => $insurances,
            'agencies' => $agencies,
            'cobs' => $cobs
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $current_step = $request->current_step;

        if ($current_step == 1) {
            $request->validate([
                'client_id' => ['required'],
                'insurance_id' => ['required'],
                'co_insurance' => ['required'],
                'takeful_type' => ['required'],
                'policy_no' => ['required'],
                'agency_id' => ['required'],
                'agency_code' => ['required'],
                'class_of_business_id' => ['required'],
                'orignal_endorsment' => ['required'],
                'date_of_insurance' => ['required'],
                'policy_start_period' => ['required'],
                'policy_end_period' => ['required'],
            ]);
        }

        if ($current_step == 2) {
            $request->validate([
                'sum_insured' => ['required'],
                'gross_premium' => ['required'],
                'net_premium' => ['required'],
                'cover_note_no' => ['required'],
                'installment_plan' => ['required'],
                'leader' => ['required'],
                'leader_policy_no' => ['required'],
                'branch' => ['required'],
                'brokerage_amount' => ['required'],
                'user_id' => ['required'],
                'tax' => ['required'],
                'percentage' => ['required'],
            ]);
        }


        if ($current_step == 3) {
            $date_of_insurance = Carbon::parse($request->date_of_insurance);
            $policy_start_period = Carbon::parse($request->policy_start_period);
            $policy_end_period = Carbon::parse($request->policy_end_period);
            // Format the date as per your requirement
            $dateOfInsurance = $date_of_insurance->format('Y-m-d');
            $policyStartPeriod = $policy_start_period->format('Y-m-d');
            $policyEndPeriod = $policy_end_period->format('Y-m-d');

            $data = [
                'client_id' => $request->client_id,
                'insurance_id' => $request->insurance_id,
                'co_insurance' => $request->co_insurance,
                'takeful_type' => $request->takeful_type,
                'policy_no' => $request->policy_no,
                'agency_id' => $request->agency_id,
                'agency_code' => $request->agency_code,
                'class_of_business_id' => $request->class_of_business_id,
                'orignal_endorsment' => $request->orignal_endorsment,
                'date_of_insurance' => $dateOfInsurance,
                'policy_start_period' => $policyStartPeriod,
                'policy_end_period' => $policyEndPeriod,
                'sum_insured' => $request->sum_insured,
                'gross_premium' => $request->gross_premium,
                'net_premium' => $request->net_premium,
                'cover_note_no' => $request->cover_note_no,
                'installment_plan' => $request->installment_plan,
                'leader' => $request->leader,
                'leader_policy_no' => $request->leader_policy_no,
                'branch' => $request->branch,
                'brokerage_amount' => $request->brokerage_amount,
                'user_id' => $request->user_id,
                'tax' => $request->tax,
                'percentage' => $request->percentage,
            ];

            $policy = Policy::create($data);
        }
    }

    public function update(Request $request)
    {
        $policy = Policy::findOrFail($request->policy_id);

        $validate = $request->validate([
            'client_id' => ['required'],
            'co_insurance' => ['required'],
            'takeful_type' => ['required'],
            'policy_no' => ['required'],
            'agency_id' => ['required'],
            'agency_code' => ['required'],
            'class_of_business_id' => ['required'],
            'orignal_endorsment' => ['required'],
            'date_of_insurance' => ['required'],
            'policy_start_period' => ['required'],
            'policy_end_period' => ['required'],
            'sum_insured' => ['required'],
            'gross_premium' => ['required'],
            'net_premium' => ['required'],
            'cover_note_no' => ['required'],
            'installment_plan' => ['required'],
            'leader' => ['required'],
            'leader_policy_no' => ['required'],
            'branch' => ['required'],
            'brokerage_amount' => ['required'],
            'user_id' => ['required'],
            'tax' => ['required'],
            'percentage' => ['required'],
        ]);

        $date_of_insurance = Carbon::parse($request->date_of_insurance);
        $policy_start_period = Carbon::parse($request->policy_start_period);
        $policy_end_period = Carbon::parse($request->policy_end_period);
        // Format the date as per your requirement
        $dateOfInsurance = $date_of_insurance->format('Y-m-d');
        $policyStartPeriod = $policy_start_period->format('Y-m-d');
        $policyEndPeriod = $policy_end_period->format('Y-m-d');

        $data = [
            'client_id' => $request->client_id,
            'insurance_id' => $request->insurance_id,
            'co_insurance' => $request->co_insurance,
            'takeful_type' => $request->takeful_type,
            'policy_no' => $request->policy_no,
            'agency_id' => $request->agency_id,
            'agency_code' => $request->agency_code,
            'class_of_business_id' => $request->class_of_business_id,
            'orignal_endorsment' => $request->orignal_endorsment,
            'date_of_insurance' => $dateOfInsurance,
            'policy_start_period' => $policyStartPeriod,
            'policy_end_period' => $policyEndPeriod,
            'sum_insured' => $request->sum_insured,
            'gross_premium' => $request->gross_premium,
            'net_premium' => $request->net_premium,
            'cover_note_no' => $request->cover_note_no,
            'installment_plan' => $request->installment_plan,
            'leader' => $request->leader,
            'leader_policy_no' => $request->leader_policy_no,
            'branch' => $request->branch,
            'brokerage_amount' => $request->brokerage_amount,
            'user_id' => $request->user_id,
            'tax' => $request->tax,
            'percentage' => $request->percentage,
        ];

        $policy->update($data);
    }
}
