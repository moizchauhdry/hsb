<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Agency;
use App\Models\Policy;
use App\Rules\ExcelFile;
use App\Models\Insurance;
use Illuminate\Http\File;
use App\Models\Department;
use App\Models\PolicyNote;
use App\Models\PolicyClaim;
use App\Models\PolicyUpload;
use Illuminate\Http\Request;
use App\Imports\ClientImport;
use App\Imports\PolicyImport;
use App\Models\BusinessClass;
use Illuminate\Support\Carbon;
use App\Models\PolicyClaimNote;
use App\Models\PolicyInsurance;
use App\Models\PolicyClaimUpload;
use Monolog\Handler\IFTTTHandler;
use App\Http\Controllers\Controller;
use App\Imports\ExcelImport;
use App\Imports\SpecificSheetImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PolicyInstallmentPlan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        $filter = [
            'search_type' => $request->search_type,
            'search_value' => $request->search_value,
        ];

        $policies = Policy::query()
            ->when($filter['search_type'] == 1 && $filter['search_value'], function ($q) use ($filter) {
                $q->where('id', $filter['search_value']);
            })
            ->when($filter['search_type'] == 2 && $filter['search_value'], function ($q) use ($filter) {
                $q->where('policy_no', 'LIKE', '%' . $filter['search_value'] . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString()
            ->through(fn($policy) => [
                'id' => $policy->id,
                'policy_no' => $policy->policy_no,
                'client_name' => $policy->client ? $policy->client->name : null,
                'insurer_name' => $policy->insurer ? $policy->insurer->name : null,
                'insurance_date' => $policy->date_of_insurance ? Carbon::parse($policy->date_of_insurance)->format('d-m-Y') : null,
                'policy_start' => $policy->policy_start_period ? Carbon::parse($policy->policy_start_period)->format('d-m-Y') : null,
                'policy_end' => $policy->policy_end_period ? Carbon::parse($policy->policy_end_period)->format('d-m-Y') : null,

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
        $departments = Department::select('id', 'name')->get()->toArray();
        $cobs = BusinessClass::select('id', 'class_name')->get()->toArray();

        $data = [
            'users' => $users,
            'clients' => $clients,
            'insurances' => $insurances,
            'agencies' => $agencies,
            'departments' => $departments,
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
                'lead_type' => ['required'],
                'policy_no' => ['required'],
                'agency_id' => ['required'],
                'department_id' => ['required'],
                'class_of_business_id' => ['required'],
                'orignal_endorsment' => ['required'],
                'date_of_insurance' => 'required|date',
                'policy_start_period' => 'required|date',
                'policy_end_period' => 'required|date',
                'installment_plan' => 'required',
            ], [
                'date_of_insurance.required' => 'The insurance date is required.',
                'date_of_insurance.date' => 'The insurance date must be a valid date.',
                'policy_start_period.required' => 'The policy start period is required.',
                'policy_start_period.date' => 'The policy start period must be a valid date.',
                'policy_end_period.required' => 'The policy end period is required.',
                'policy_end_period.date' => 'The policy end period must be a valid date.',
            ]);
        }

        if ($current_step == 2) {
            $request->validate([
                'sum_insured' => ['required'],
                'gross_premium' => ['required'],
                'net_premium' => ['required'],
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
                'department_id' => $request->department_id,
                'lead_type' => $request->lead_type,
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
                'user_id' => auth()->user()->id,
                'percentage' => $request->percentage,
                'hsb_profit' => $request->hsb_profit,
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
        $departments = Department::select('id', 'name')->get()->toArray();

        $data = [
            'policy' => $policy,
            'users' => $users,
            'clients' => $clients,
            'insurances' => $insurances,
            'agencies' => $agencies,
            'cobs' => $cobs,
            'departments' => $departments,
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

            $policyInstallment = $policy->policyInstallment;

            $policyNotes = PolicyNote::where('policy_id', $policy->id)->get();

            $policyClaims = PolicyClaim::where('policy_id', $policy->id)
                ->paginate(5)
                ->withQueryString()
                ->through(fn($policyClaim) => [
                    'id' => $policyClaim->id,
                    'detail' => $policyClaim->detail,
                    'status' => $policyClaim->status
                ]);

            $policyUploads = PolicyUpload::where('policy_id', $policy->id)
                ->paginate(5)
                ->withQueryString()
                ->through(fn($policyUpload) => [
                    'id' => $policyUpload->id,
                    'upload' => $policyUpload->upload,
                    'type' => $policyUpload->type
                ]);

            return Inertia::render('Policy/Detail', [
                'policy' => $policy,
                'policyInstallment' => $policyInstallment,
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

    public function delete(Request $request)
    {
        $policy = Policy::find($request->id);
        if (!empty($policy)) {
            $policyClaims = PolicyClaim::where('policy_id', $policy->id)->get();
            if ($policyClaims->isNotEmpty()) {
                foreach ($policyClaims as $policyClaim) {
                    $policyClaim->delete();
                }
            }

            $policyInstallment = PolicyInstallmentPlan::where('policy_id', $policy->id)->get();
            if ($policyInstallment->isNotEmpty()) {
                foreach ($policyInstallment as $policyInstallmentPlan) {
                    $policyInstallmentPlan->delete();
                }
            }

            $policyClaimNote = PolicyClaimNote::where('policy_id', $policy->id)->get();
            if ($policyClaimNote->isNotEmpty()) {
                foreach ($policyClaimNote as $policyClaimNot) {
                    $policyClaimNot->delete();
                }
            }

            $policyClaimUpload = PolicyClaimUpload::where('policy_id', $policy->id)->get();
            if ($policyClaimUpload->isNotEmpty()) {
                foreach ($policyClaimUpload as $policyClaimUploads) {
                    $policyClaimUploads->delete();
                }
            }

            $policyNote = PolicyNote::where('policy_id', $policy->id)->get();
            if ($policyNote->isNotEmpty()) {
                foreach ($policyNote as $policyNotes) {
                    $policyNotes->delete();
                }
            }

            $policyUpload = PolicyUpload::where('policy_id', $policy->id)->get();
            if ($policyUpload->isNotEmpty()) {
                foreach ($policyUpload as $policyUploads) {
                    $policyUploads->delete();
                }
            }

            $policy->delete();
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

    // ******* POLICY CLAIMS *******

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
            'status' => ['required']
        ]);

        try {
            $data = [
                'policy_id' => $request->policy_id,
                'detail' => $request->detail,
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
            'status' => ['required']
        ]);



        $data = [
            'policy_id' => $request->policy_id,
            'detail' => $request->detail,
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

    public function getDepartmentByBusinessClass($id)
    {
        $cobs = BusinessClass::where('department_id', $id)->get()->toArray();

        $data = [
            'cobs' => $cobs
        ];

        return response()->json($data);
    }

    public function getBusinessClassByPercent($id)
    {
        $cobPercentage = BusinessClass::find($id);
        $data = [
            'cobPercentage' => $cobPercentage->percentage
        ];

        return response()->json($data);
    }

    public function installmentPlan(Request $request)
    {
        $request->validate([
            'policy_id' => ['required', 'numeric'],
            'due_date' => ['required', 'date_format:Y-m-d\TH:i:s.v\Z'],
            'gross_premium' => ['required', 'numeric'],
            'net_premium' => ['required', 'numeric'],
            'payment_status' => ['required'],
        ]);

        // Get the policy ID from the request
        $policy_id = $request->input('policy_id');

        // Extract installment plan data from the request
        $installmentPlans = $request->only(['due_date', 'gross_premium', 'net_premium', 'payment_status']);
        $due_date = Carbon::parse($installmentPlans['due_date']);


        $dueDate = $due_date->format('Y-m-d');

        // Create data array for the installment plan
        $data = [
            'policy_id' => $policy_id,
            'due_date' => $dueDate,
            'gross_premium' => $installmentPlans['gross_premium'],
            'net_premium' => $installmentPlans['net_premium'],
            'payment_status' => $installmentPlans['payment_status'],
        ];

        // Create new PolicyInstallmentPlan entry
        PolicyInstallmentPlan::create($data);
    }

    public function importData(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx',
            'type' => 'required',
        ]);

        try {
            $file = $request->file('file');
            $type = $request->type;

            if ($type == "1") {
                // $import = new PolicyImport();
                // Excel::import($import, $file)->onlySheets('report');

                // DB::table('policies')->truncate();
                Excel::queueImport(new ExcelImport, $file);
            }

            return redirect()->route('policy.index');
        } catch (\Throwable $th) {
            throw $th;
            // abort(403, $th->getMessage());
        }

        // if ($type == "2") {
        //     if (!empty($file)) {
        //         if ($file) {
        //             $import = new ClientImport();
        //             Excel::import($import, $file);
        //             if (!empty($import->errors)) {
        //                 return response()->json(['errors' => $import->errors], 422);
        //             }
        //         } else {
        //             return response()->json(['error' => 'File not uploaded or invalid'], 400);
        //         }
        //     } else {
        //         return response()->json(['error' => 'No files uploaded'], 400);
        //     }
        // }
    }
}
