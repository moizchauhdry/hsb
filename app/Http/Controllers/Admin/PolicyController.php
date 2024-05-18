<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Agency;
use App\Models\Policy;
use App\Models\Insurance;
use Illuminate\Http\Request;
use App\Models\ClassOfBusiness;
use App\Models\PolicyInsurance;
use App\Http\Controllers\Controller;
use Monolog\Handler\IFTTTHandler;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::orderBy('id', 'desc')->paginate(10)
            ->withQueryString()
            ->through(fn ($policy) => [
                'id' => $policy->id,
                'client_name' => $policy->client_name,
                'insurance_id' => $policy->insurance,
                'co_insurance' => $policy->co_insurance,
                'takeful_type' => $policy->takeful_type,
                'policy_no' => $policy->policy_no,
                'agency_id' => $policy->agency_id,
                'agency_code' => $policy->agency_code,
                'class_of_business_id' => $policy->class_of_business_id,
                'orignal_endorsment' => $policy->orignal_endorsment,
                'date_of_insurance' => $policy->date_of_insurance,
                'policy_start_period' => $policy->policy_start_period,
                'policy_end_period' => $policy->policy_end_period,
                'sum_insured' => $policy->sum_insured,
                'gross_premium' => $policy->gross_premium,
                'net_premium' => $policy->net_premium,
                'cover_note_no' => $policy->cover_note_no,
                'installment_plan' => $policy->installment_plan,
                'leader' => $policy->leader,
                'leader_policy_no' => $policy->leader_policy_no,
                'branch' => $policy->branch,
                'brokerage_amount' => $policy->brokerage_amount,
                'user_id' => $policy->user_id,
                'tax' => $policy->tax,
                'percentage' => $policy->percentage,
                'created_at' => $policy->created_at->format('d-m-Y h:i A'),
            ]);

        $insurances = Insurance::select('id', 'name')->get();
        $agencies = Agency::select('id', 'name')->get();
        $classOfBusiness = ClassOfBusiness::select('id', 'b_class_name')->get();
        $users = User::select('id', 'name')->get();

        return Inertia::render('Policy/Index', [
            'insurances' => $insurances,
            'policies' => $policies,
            'agencies' => $agencies,
            'classOfBusiness' => $classOfBusiness,
            'users' => $users,
        ]);
    }

    public function create(Request $request)
    {
        $validate = $request->validate([
            'client_name' => ['required', 'string', 'min:5', 'max:50'],
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

        $data = [
            'client_name' => $request->client_name,
            'co_insurance' => $request->co_insurance,
            'takeful_type' => $request->takeful_type,
            'policy_no' => $request->policy_no,
            'agency_id' => $request->agency_id,
            'agency_code' => $request->agency_code,
            'class_of_business_id' => $request->class_of_business_id,
            'orignal_endorsment' => $request->orignal_endorsment,
            'date_of_insurance' => $request->date_of_insurance,
            'policy_start_period' => $request->policy_start_period,
            'policy_end_period' => $request->policy_end_period,
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

        if($request->has('insurance_id'))
        {
            $insuranceRequest = $request->insurance_id;
            foreach($insuranceRequest as $insurance){
                $data = [
                    'policy_id' => $policy->id,
                    'insurance_id' => $insurance,
                ];

                PolicyInsurance::create($data);
            }

        }
    }

    public function update(Request $request)
    {        
        $policy = Policy::findOrFail($request->policy_id);

        $validate = $request->validate([
            'client_name' => ['required', 'string', 'min:5', 'max:50'],
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

        $data = [
            'client_name' => $request->client_name,
            'co_insurance' => $request->co_insurance,
            'takeful_type' => $request->takeful_type,
            'policy_no' => $request->policy_no,
            'agency_id' => $request->agency_id,
            'agency_code' => $request->agency_code,
            'class_of_business_id' => $request->class_of_business_id,
            'orignal_endorsment' => $request->orignal_endorsment,
            'date_of_insurance' => $request->date_of_insurance,
            'policy_start_period' => $request->policy_start_period,
            'policy_end_period' => $request->policy_end_period,
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

        if($request->has('insurance_id'))
        {
            $insuranceRequest = $request->insurance_id;
            foreach($insuranceRequest as $insurance){
                $ins = PolicyInsurance::where('insurance_id',$insurance)
                  ->where('policy_id',$policy->id)->first();
                if(!empty($ins)){
                    $data = [
                        'policy_id' => $policy->id,
                        'insurance_id' => $insurance,
                    ];

                    $ins->update($data);

                } else {

                    $data = [
                        'policy_id' => $policy->id,
                        'insurance_id' => $insurance,
                    ];
                    PolicyInsurance::create($data);
                }
            }
        }
    }
}
