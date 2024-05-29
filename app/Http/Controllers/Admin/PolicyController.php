<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Agency;
use App\Models\Policy;
use App\Models\Insurance;
use Illuminate\Http\File;
use App\Models\PolicyNote;
use App\Models\PolicyClaim;
use App\Models\PolicyUpload;
use Illuminate\Http\Request;
use App\Models\BusinessClass;
use Illuminate\Support\Carbon;
use App\Models\PolicyClaimNote;
use App\Models\PolicyInsurance;
use App\Models\PolicyClaimUpload;
use Monolog\Handler\IFTTTHandler;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::orderBy('id', 'desc')->paginate(10)
            ->withQueryString()
            ->through(fn ($policy) => [
                'id' => $policy->id,
                'policy_no' => $policy->policy_no,
                'client_name' => $policy->client->name,
                'insurer_name' => $policy->insurer->name,
                'insurance_date' => Carbon::parse($policy->date_of_insurance)->format('d-m-Y'),
                'policy_start' => Carbon::parse($policy->policy_start_period)->format('d-m-Y'),
                'policy_end' => Carbon::parse($policy->policy_end_period)->format('d-m-Y'),
            ]);

        return Inertia::render('Policy/Index', [
            'policies' => $policies
        ]);
    }

    public function create()
    {
        $users = User::select('id', 'name')->where('role_users_id', 1)->get()->toArray();
        $clients = User::select('id', 'name')->where('role_users_id', 2)->get()->toArray();
        $insurances = Insurance::select('id', 'name')->get()->toArray();
        $agencies = Agency::select('id', 'name')->get()->toArray();
        $cobs = BusinessClass::select('id', 'class_name')->get()->toArray();

        $data = [
            'users' => $users,
            'clients' => $clients,
            'insurances' => $insurances,
            'agencies' => $agencies,
            'cobs' => $cobs
        ];

        return response()->json($data);
    }

    public function save($request)
    {
        $current_step = $request->current_step;

        if ($current_step == 1) {
            $request->validate([
                'client_id' => ['required'],
                'insurance_id' => ['required'],
                'co_insurance' => ['required'],
                'takeful_type' => ['required'],
                'policy_no' => ['required'],
                'cover_note_no' => ['required'],
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
                'installment_plan' => ['required'],
                // 'leader' => ['required'],
                // 'leader_policy_no' => ['required'],
                // 'branch' => ['required'],
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

            if ($request->policy_id) {
                $policy = Policy::find($request->policy_id);
                $policy->update($data);
            } else {
                Policy::create($data);
            }
        }
    }

    public function store(Request $request)
    {
        $this->save($request);
    }

    public function edit($id)
    {
        $policy = Policy::where('id', $id)->first();

        $users = User::select('id', 'name')->where('role_users_id', 1)->get()->toArray();
        $clients = User::select('id', 'name')->where('role_users_id', 2)->get()->toArray();
        $insurances = Insurance::select('id', 'name')->get()->toArray();
        $agencies = Agency::select('id', 'name')->get()->toArray();
        $cobs = BusinessClass::select('id', 'class_name')->get()->toArray();

        $data = [
            'policy' => $policy,
            'users' => $users,
            'clients' => $clients,
            'insurances' => $insurances,
            'agencies' => $agencies,
            'cobs' => $cobs
        ];

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $this->save($request);
    }

    public function detail($id)
    {
        try {
            $policy = Policy::find($id);

            $policyResponse = [
                'id' => $policy->id,
                'client_name' => $policy->client->name,
                'insurance_id' => $policy->insurance->name,
                'co_insurance' => $policy->co_insurance,
                'takeful_type' => $policy->takeful_type,
                'policy_no' => $policy->policy_no,
                'agency_id' => $policy->agency->name,
                'agency_code' => $policy->agency_code,
                'class_of_business_id' => $policy->businessClass->class_name,
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
                'user_id' => $policy->user->name,
                'tax' => $policy->tax,
                'percentage' => $policy->percentage,
                'created_at' => $policy->created_at->format('d-m-Y h:i A'),
            ];

            $policyNotes = PolicyNote::where('policy_id', $policy->id)->get();
            $policyClaims = PolicyClaim::where('policy_id', $policy->id)->get();
            $policyUploads = PolicyUpload::where('policy_id', $policy->id)->get();
            return Inertia::render('Policy/Detail', [
                'policy' => $policyResponse,
                'policyNotes' => $policyNotes,
                'policyClaims' => $policyClaims,
                'policyUploads' => $policyUploads,
                'assetUrl' => asset('storage')
            ]);
        } catch (ModelNotFoundException $e) {
            // Handle case when policy with the given ID doesn't exist
            return response()->json(['error' => 'Policy not found'], 404);
        }
    }

    public function additionalNotes(Request $request)
    {
        $request->validate([
            'policy_id' => ['required'],
            'additionalNotes' => ['required'],
        ]);

        try {
            $data = [
                'policy_id' => $request->policy_id,
                'additional_notes' => $request->additionalNotes,
            ];

            PolicyNote::create($data);
        } catch (ModelNotFoundException $e) {
            // Handle case when policy with the given ID doesn't exist
            return response()->json(['error' => 'Policy not found'], 404);
        }
    }


    public function uploads(Request $request)
    {
        $request->validate([
            'policy_id' => ['required'],
            'uploads' => ['required'],
            'type' => ['required'],
        ]);

        try {
            $data = [
                'policy_id' => $request->policy_id,
                'type' => $request->type,
            ];

            $policyUpload = PolicyUpload::create($data);

            $policyUploadDirectory = 'policyUploads';
            if ($request->hasFile('uploads')) {

                // Assuming $request->file('uploads') returns an array of uploaded files
                $files = $request->file('uploads');

                foreach ($files as $file) {
                    // Get the original file name
                    $fileName = $file->getClientOriginalName();

                    // Check if the storage directory exists, if not create it
                    if (!Storage::exists($policyUploadDirectory)) {
                        Storage::makeDirectory($policyUploadDirectory);
                    }

                    // Store the file and get the path
                    $imageUrl = Storage::putFile($policyUploadDirectory, new File($file));

                    // Update the policy upload with the file path
                    $policyUpload->update(['upload' => $imageUrl]);
                }
            }
        } catch (ModelNotFoundException $e) {
            // Handle case when policy with the given ID doesn't exist
            return response()->json(['error' => 'Policy not found'], 404);
        }
    }

    // Policy Claim function Start

    public function getClaim($id)
    {

        $policyClaim = PolicyClaim::find($id);
        $data = [
            "policyClaim" => $policyClaim
        ];

        return response()->json($data);
    }

    public function claims(Request $request)
    {
        $request->validate([
            'policy_id' => ['required'],
            'detail' => ['required'],
            'progress' => ['required'],
            'settled' => ['required'],
            'status' => ['required']
        ]);

        try {
            $data = [
                'policy_id' => $request->policy_id,
                'detail' => $request->detail,
                'progress' => $request->progress,
                'settled' => $request->settled,
                'status' => $request->status,
            ];

            PolicyClaim::create($data);
        } catch (ModelNotFoundException $e) {
            // Handle case when policy with the given ID doesn't exist
            return response()->json(['error' => 'Policy not found'], 404);
        }
    }


    public function updateClaim(Request $request)
    {
        $policyClaim = PolicyClaim::where('id', $request->claim_id)->first();

        $request->validate([
            'policy_id' => ['required'],
            'detail' => ['required'],
            'progress' => ['required'],
            'settled' => ['required'],
            'status' => ['required']
        ]);



        $data = [
            'policy_id' => $request->policy_id,
            'detail' => $request->detail,
            'progress' => $request->progress,
            'settled' => $request->settled,
            'status' => $request->status
        ];
        $policyClaim->update($data);
    }



    public function getClaimUpload($id)
    {

        $policyClaimUploads = PolicyClaimUpload::where('policy_id', $id)->get()->toArray();
        $data = [
            "policyClaimUploads" => $policyClaimUploads
        ];

        return response()->json($data);
    }

    public function claimUpload(Request $request)
    {
        $request->validate([
            'policy_id' => ['required'],
            'policy_claim_id' => ['required'],
            'uploads' => ['required'],
        ]);

        try {
            $data = [
                'policy_id' => $request->policy_id,
                'policy_claim_id' => $request->policy_claim_id,
            ];

            $policyClaimUpload = PolicyClaimUpload::create($data);

            $policyClaimUploadDirectory = 'policyClaimUploads';
            if ($request->hasFile('uploads')) {

                // Assuming $request->file('uploads') returns an array of uploaded files
                $files = $request->file('uploads');

                foreach ($files as $file) {
                    // Get the original file name
                    $fileName = $file->getClientOriginalName();

                    // Check if the storage directory exists, if not create it
                    if (!Storage::exists($policyClaimUploadDirectory)) {
                        Storage::makeDirectory($policyClaimUploadDirectory);
                    }

                    // Store the file and get the path
                    $imageUrl = Storage::putFile($policyClaimUploadDirectory, new File($file));

                    // Update the policy upload with the file path
                    $policyClaimUpload->update(['file_url' => $imageUrl]);
                }
            }
        } catch (ModelNotFoundException $e) {
            // Handle case when policy with the given ID doesn't exist
            return response()->json(['error' => 'Policy not found'], 404);
        }
    }

    public function getClaimNote($id)
    {

        $policyClaimNotes = PolicyClaimNote::where('policy_id', $id)->get()->toArray();
        $data = [
            "policyClaimNotes" => $policyClaimNotes
        ];

        return response()->json($data);
    }

    public function claimNote(Request $request)
    {
        $request->validate([
            'policy_id' => ['required'],
            'policy_claim_id' => ['required'],
            'note' => ['required']
        ]);

        try {
            $data = [
                'policy_id' => $request->policy_id,
                'policy_claim_id' => $request->policy_claim_id,
                'note' => $request->note,
            ];

            PolicyClaimNote::create($data);
        } catch (ModelNotFoundException $e) {
            // Handle case when policy with the given ID doesn't exist
            return response()->json(['error' => 'Policy not found'], 404);
        }
    }

    // Policy Claim function End

}
