<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PolicyClaimNote;
use App\Models\PolicyClaimUpload;
use App\Models\PolicyClaim;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class ClaimController extends Controller
{
    public function getClaim($id)
    {

        $policyClaim = PolicyClaim::find($id);
        $data = [
            "policyClaim" => $policyClaim
        ];

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

        $data = [
            'policy_id' => $request->policy_id,
            'policy_claim_id' => $request->policy_claim_id,
            'note' => $request->note,
        ];

        PolicyClaimNote::create($data);
    }
}
