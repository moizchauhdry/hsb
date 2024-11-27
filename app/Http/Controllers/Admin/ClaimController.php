<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\BusinessClass;
use App\Models\Insurance;
use App\Models\Policy;
use Illuminate\Http\Request;

use App\Models\PolicyClaimNote;
use App\Models\PolicyClaimUpload;
use App\Models\PolicyClaim;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Inertia\Inertia;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $current_month = $request->month ?? Carbon::now()->format('m');
        $current_year = $request->year ?? Carbon::now()->format('Y');

        $filter = [
            'search' => $request->search,
            'date_type' => $request->date_type,
            'month' => $current_month,
            'month_name' => getMonthName($current_month),
            'year' => $current_year,
        ];

        $claims = PolicyClaim::query()
            ->when($filter['search'], function ($q) use ($filter) {
                $q->where('id', $filter['search']);
            })
            ->when($filter['date_type'], function ($q) use ($filter) {
                $q->whereYear($filter['date_type'], $filter['year']);
                $q->whereMonth($filter['date_type'], $filter['month']);
            })
            ->orderBy('id', 'desc')
            ->paginate(25)
            ->withQueryString()
            ->through(fn($claim) => [
                'data' => $claim,
                'policy_no' => $claim->policy->policy_no ?? NULL,
                'claim_at' => dateFormat($claim->claim_at),
                'intimation_at' => dateFormat($claim->intimation_at),
            ]);

        return Inertia::render('Policy/Claim/Index', [
            'claims' => $claims,
            'filter' => $filter,
        ]);
    }

    public function fetch($id)
    {
        $policy_claim = PolicyClaim::find($id);
        $data = ["policy_claim" => $policy_claim];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'policy_id' => ['required'],
            'status' => ['required'],
            'description' => ['required'],
            'claim_at' => ['required'],
            'intimation_at' => ['required'],
            'survivor_name' => ['required'],
            'contact_no' => ['required'],
        ]);

        $data = [
            'policy_id' => $request->policy_id,
            'status' => $request->status,
            'detail' => $request->description,
            'claim_at' => setDateFormat($request->claim_at),
            'intimation_at' => setDateFormat($request->intimation_at),
            'survivor_name' => $request->survivor_name,
            'contact_no' => $request->contact_no,
        ];

        PolicyClaim::create($data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'policy_id' => ['required'],
            'status' => ['required'],
            'description' => ['required'],
            'claim_at' => ['required'],
            'intimation_at' => ['required'],
            'survivor_name' => ['required'],
            'contact_no' => ['required'],
        ]);

        try {
            $policy_claim = PolicyClaim::find($request->claim_id);

            $data = [
                'policy_id' => $request->policy_id,
                'status' => $request->status,
                'detail' => $request->description,
                'claim_at' => setDateFormat($request->claim_at),
                'intimation_at' => setDateFormat($request->intimation_at),
                'survivor_name' => $request->survivor_name,
                'contact_no' => $request->contact_no,
            ];

            $policy_claim->update($data);
        } catch (\Throwable $th) {
            return back()->withErrors([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
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
    }

    public function fetchClaimNote($id)
    {
        $claim_notes = PolicyClaimNote::where('policy_id', $id)->get()->toArray();
        $data = ["claim_notes" => $claim_notes];

        return response()->json($data);
    }

    public function claimNote(Request $request)
    {
        $request->validate([
            'policy_id' => ['required'],
            'policy_claim_id' => ['required'],
            'note' => ['required']
        ]);

        $data = [
            'policy_id' => $request->policy_id,
            'policy_claim_id' => $request->policy_claim_id,
            'note' => $request->note,
        ];

        PolicyClaimNote::create($data);
    }
}
