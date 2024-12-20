<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Agency;
use App\Models\Policy;
use App\Models\Insurance;
use Illuminate\Http\File;
use App\Models\Department;
use App\Models\PolicyNote;
use App\Models\PolicyClaim;
use App\Models\PolicyUpload;
use Illuminate\Http\Request;
use App\Models\BusinessClass;
use Illuminate\Support\Carbon;
use App\Models\PolicyClaimNote;
use App\Models\PolicyClaimUpload;
use App\Http\Controllers\Controller;
use App\Imports\ExcelImport;
use App\Models\ErrorLog;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PolicyInstallmentPlan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        $page_count = $request->page_count ?? 10;

        $filter = [
            'search' => $request->search,

            'date_type' => $request->date_type,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,

            'policy_type' => $request->policy_type,
            'client' => $request->client,
            'agency' => $request->agency,
            'insurer' => $request->insurer,
            'cob' => $request->cob,
            'department' => $request->department,
            'group' => $request->group,
        ];

        $policies = Policy::query()
            ->select(
                'p.id as p_id',
                'p.policy_no as policy_no',
                'p.client_id as client_id',
                'p.policy_period_end as expiry_date',
                'client.name as client_name',
                'agency.name as agency_name',
                'cob.class_name as cob_name',
                DB::raw('COUNT(DISTINCT pc.id) as claim_count'),
            )
            ->policiesList($filter)
            ->when($filter['search'], function ($q) use ($filter) {
                $q->where('p.id', $filter['search']);
                $q->orWhere('p.policy_no', "LIKE", "%" . $filter['search'] . "%");
            })
            ->groupBy('p.id', 'p.policy_no', 'p.client_id', 'p.policy_period_end', 'client.name', 'agency.name', 'cob.name')
            ->paginate($page_count)
            ->withQueryString();

        return Inertia::render('Policy/Index', [
            'policies' => $policies,
            'filter' => $filter,
        ]);
    }

    public function create()
    {
        $users = User::select('id', 'name')->withoutRole('client')->get()->toArray();
        $clients = User::select('id', 'name')->role('client')->get()->toArray();
        $insurances = Insurance::select('id', 'name')->get()->toArray();
        $agencies = Agency::select('id', 'name')->get()->toArray();
        $cobs = BusinessClass::select('id', 'class_name')->get()->toArray();
        $departments = Department::select('id', 'name')->get()->toArray();

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
                'insurer_id' => ['required'],
                'lead_type' => ['required'],
                'policy_no' => ['required'],
                'agency_id' => ['required'],
                'department_id' => ['required'],
                'cob_id' => ['required'],
                'date_of_issuance' => 'required|date',
                'policy_period_start' => 'required|date',
                'policy_period_end' => 'required|date',
                'installment_plan' => 'nullable',
            ], [
                'date_of_issuance.required' => 'The insurance date is required.',
                'date_of_issuance.date' => 'The insurance date must be a valid date.',
                'policy_period_start.required' => 'The Inception Date is required.',
                'policy_period_start.date' => 'The Inception Date must be a valid date.',
                'policy_period_end.required' => 'The Expiry Date is required.',
                'policy_period_end.date' => 'The Expiry Date must be a valid date.',
            ]);
        }

        if ($current_step == 2) {
            $request->validate([
                'sum_insured' => ['required'],
                'gross_premium' => ['required'],
                'net_premium' => ['required'],
                // 'rate_percentage' => ['required'],
                // 'brokerage_amount' => ['required'],
            ]);
        }

        if ($current_step == 3) {
            $date_of_issuance = Carbon::parse($request->date_of_issuance);
            $policy_period_start = Carbon::parse($request->policy_period_start);
            $policy_period_end = Carbon::parse($request->policy_period_end);

            $date_of_issuance = $date_of_issuance->format('Y-m-d');
            $policyStartPeriod = $policy_period_start->format('Y-m-d');
            $policyEndPeriod = $policy_period_end->format('Y-m-d');

            $data = [
                'client_id' => $request->client_id,
                'insurer_id' => $request->insurer_id,
                // 'co_insurance' => $request->co_insurance,
                'insurance_type' => $request->insurance_type,
                'department_id' => $request->department_id,
                'lead_type' => $request->lead_type,
                'policy_no' => $request->policy_no,
                'agency_id' => $request->agency_id,
                'agency_code' => $request->agency_code,
                'cob_id' => $request->cob_id,
                'policy_type' => $request->policy_type,
                'date_of_issuance' => $date_of_issuance,
                'policy_period_start' => $policyStartPeriod,
                'policy_period_end' => $policyEndPeriod,
                'sum_insured' => $request->sum_insured,
                'gross_premium' => $request->gross_premium,
                'net_premium' => $request->net_premium,
                // 'cover_note_no' => $request->cover_note_no,
                'installment_plan' => $request->installment_plan,
                'user_id' => auth()->user()->id,
                'rate_percentage' => $request->rate_percentage,
                'brokerage_amount' => $request->brokerage_amount,
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

        $users = User::select('id', 'name')->withoutRole('client')->get()->toArray();
        $clients = User::select('id', 'name')->role('client')->get()->toArray();
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
            $policy = Policy::with(['agency', 'insurer', 'client', 'cob', 'department'])->find($id);

            $policyInstallment = $policy->policyInstallment;

            $policyNotes = PolicyNote::where('policy_id', $policy->id)->get();

            $policy_claims = PolicyClaim::where('policy_id', $policy->id)
                ->orderBy('id', 'desc')
                ->paginate(5)
                ->withQueryString()
                ->through(fn($claim) => [
                    'data' => $claim,
                    'claim_at' => getDateTimeFormat($claim->claim_at),
                    'intimation_at' => getDateTimeFormat($claim->intimation_at),
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
                'policy_claims' => $policy_claims,
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
        Session::forget('excel_import');

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx',
            'type' => 'required',
        ]);

        try {
            $file = $request->file('file');
            $type = $request->type;

            $error_logs = ErrorLog::where('type', 'excel_import')->delete();

            if ($type == "1") {
                Excel::queueImport(new ExcelImport, $file);
            }

            // Session::put('excel_import', true);
            Log::channel('database')->info('Start Importing .. Please Wait', ['type' => 'excel_import']);

            return redirect()->route('error-logs.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
