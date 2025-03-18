<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PolicyClaimNote;
use App\Models\PolicyClaimUpload;
use App\Models\PolicyClaim;
use Carbon\Carbon;
use Inertia\Inertia;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $page_count = $request->page_count ?? 10;
        // $current_month = $request->month ?? Carbon::now()->format('m');
        // $current_year = $request->year ?? Carbon::now()->format('Y');

        $filter = [
            'search' => $request->search,
            'client' => $request->client,
            'policy_id' => $request->policy_id,
            // 'date_type' => $request->date_type,
            'date_type' => in_array($request->date_type, ['created_at', 'updated_at', 'approved_at']) ? $request->date_type : null,
            'month' => $request->month ?? null,
            // 'month_name' => getMonthName($current_month),
            'year' => $request->year ?? null,
        ];

        $claims = PolicyClaim::policyClaimList($filter)
            ->orderBy('pc.id', 'desc')
            ->paginate($page_count)
            ->withQueryString();

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
            'claim_no' => ['required'],
        ]);

        $data = [
            'policy_id' => $request->policy_id,
            'status' => $request->status,
            'detail' => $request->description,
            'claim_at' => setDateFormat($request->claim_at),
            'intimation_at' => setDateFormat($request->intimation_at),
            'survivor_name' => $request->survivor_name,
            'contact_no' => $request->contact_no,
            'claim_no' => $request->claim_no,
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

    public function fetchClaimNotes($claim_id, $policy_id)
    {
        $claim_notes = PolicyClaimNote::query()
            ->select('note.*','claim.claim_no as claim_no','u.name as user_name')
            ->from('policy_claim_notes as note')
            ->join('policy_claims as claim', 'claim.id', 'note.policy_claim_id')
            ->join('users as u', 'u.id', 'note.created_by')
            ->where('note.policy_claim_id', $claim_id)
            ->where('note.policy_id', $policy_id)
            ->orderBy('note.id', 'desc')
            ->get()
            ->toArray();

        $data = ["claim_notes" => $claim_notes];

        return response()->json($data);
    }

    public function storeClaimNote(Request $request)
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
            'created_by' => auth()->id(),
        ];

        $policy_claim_note = PolicyClaimNote::create($data);

        return redirect()->back()->with(['data' => $policy_claim_note]);
    }

    public function fetchClaimUploads($claim_id, $policy_id)
    {
        $claim_uploads = PolicyClaimUpload::query()
            ->where('policy_claim_id', $claim_id)
            ->where('policy_id', $policy_id)
            ->orderBy('id', 'desc')
            ->get();

        $data = ["claim_uploads" => $claim_uploads];

        return response()->json($data);
    }

    public function storeClaimUpload(Request $request)
    {
        $request->validate([
            'policy_id' => ['required'],
            'policy_claim_id' => ['required'],
            'file' => ['required'],
        ]);

        $data = [
            'policy_id' => $request->policy_id,
            'policy_claim_id' => $request->policy_claim_id,
        ];

        $policy_claim_upload = PolicyClaimUpload::create($data);

        if ($request->hasFile('file')) {
            $url = $request->file('file')->store('policy-claim-upload', 'public');
            $policy_claim_upload->update(['file_url' => $url]);
        }

        return redirect()->back()->with(['data' => $policy_claim_upload]);
    }
}
