<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PolicyClaimNote;
use App\Models\PolicyClaimUpload;
use App\Models\PolicyClaim;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $role = Auth::user()->roles[0];

        $page_count = $request->page_count ?? 10;

        $current_month = $request->month ?? Carbon::now()->format('m');
        $current_year = $request->year ?? Carbon::now()->format('Y');

        $filter = [
            'search' => $request->search,
            'date_type' => $request->date_type,
            'month' => $current_month,
            'month_name' => getMonthName($current_month),
            'year' => $current_year,
        ];

        $query = PolicyClaim::query()
            ->select(
                'pc.*',
                'p.policy_no as policy_no',
                'u.name as client_name',
            )
            ->from('policy_claims as pc')
            ->join('policies as p', 'p.id', 'pc.policy_id')
            ->leftJoin('users as u', 'u.id', 'p.client_id');

        if ($role->id != 1) {
            $query->leftJoin('user_cobs as uc', function ($join) {
                $join->on('uc.cob_id', '=', 'p.cob_id')->where('uc.user_id', auth()->id());
            })
                ->leftJoin('user_clients as ucl', function ($join) {
                    $join->on('ucl.client_id', '=', 'p.client_id')->where('ucl.user_id', auth()->id());
                })
                ->where(function ($q) {
                    $q->whereNotNull('uc.id')->orWhereNotNull('ucl.id');
                });
        }

        $query->when($filter['search'], function ($q) use ($filter) {
            $q->where('pc.id', $filter['search']);
            $q->orWhere('pc.policy_id', $filter['search']);
            $q->orWhere('p.policy_no', "LIKE", "%" . $filter['search'] . "%");
            $q->orWhere('u.name', "LIKE", "%" . $filter['search'] . "%");
        });

        $query->when($filter['date_type'], function ($q) use ($filter) {
            $q->whereYear('pc.' . $filter['date_type'], $filter['year']);
            $q->whereMonth('pc.' . $filter['date_type'], $filter['month']);
        });

        $claims = $query->orderBy('pc.id', 'desc')
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

    public function fetchClaimNotes($claim_id, $policy_id)
    {
        $claim_notes = PolicyClaimNote::query()
            ->where('policy_claim_id', $claim_id)
            ->where('policy_id', $policy_id)
            ->orderBy('id', 'desc')
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
