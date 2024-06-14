<?php

namespace App\Imports;

use App\Models\Policy;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PolicyImport implements ToModel, WithHeadingRow
{
    public $errors = [];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $original_endorsement_other_value = '';

        if ($row['newrenewalendorsement'] == "RENEWAL") {
            $original_endorsement = 'renewal';
        } elseif ($row['newrenewalendorsement'] == "NEW") {
            $original_endorsement = 'new';
        } elseif ($row['newrenewalendorsement'] == "ENDORSEMENT") {
            $original_endorsement = 'endorsment';
        } else {
            $original_endorsement = 'others';
            $original_endorsement_other_value = $row['newrenewalendorsement'];
        }

        // lead_type check start //
        if ($row['insu_type'] == "Direct ( 100%)") {
            $insu_type = 1;
        } elseif ($row['insu_type'] == "Our Lead") {
            $insu_type = 2;
        } elseif ($row['insu_type'] == "Other Lead") {
            $insu_type = 3;
        }

        $data = [
            'doc_ref' => $row['doc_ref'],
            'department' => $row['department'],
            'client' => $row['client'],
            'other_lead' => $row['other_lead'],
            'agency' => $row['agency'],
            'rate' => $row['rate'],
            'issue_dt' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['issue_dt'])->format('Y-m-d'),
            'comm_dt' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['comm_dt'])->format('Y-m-d'),
            'expiry_dt' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['expiry_dt'])->format('Y-m-d'),
            'newrenewalendorsement' => $original_endorsement,
            'original_endorsement_other_value' => $original_endorsement_other_value,
            'insu_type' => $insu_type,
            'leader_policy_number' => $row['leader_policy_number'],
            'b_class' => $row['b_class'],
            'sum_insured' => $row['sum_insured'],
            'gross_preimmium' => $row['gross_preimmium'],
            'net_premimum' => $row['net_premimum'],

        ];

        if (count($data) > 0 && !empty($data['doc_ref'])) {
            $validator = Validator::make($data, [
                'doc_ref' => 'required',
                'department' => 'required',
                'client' => 'required',
                'agency' => 'required',
                'rate' => 'required',
                'issue_dt' => 'required|date',
                'comm_dt' => 'required|date',
                'expiry_dt' => 'required|date',
                'newrenewalendorsement' => 'required',
                'insu_type' => 'required',
                'leader_policy_number' => 'required',
                'b_class' => 'required',
                'sum_insured' => 'required',
                'gross_preimmium' => 'required',
                'net_premimum' => 'required',
            ]);

            if ($validator->fails()) {
                $this->errors[] = $validator->errors()->all();
                return null;
            }

            $policy = Policy::updateOrCreate([
                'policy_no' => $data['doc_ref'],
                'department_id' => $data['department'],
                'client_id' => $data['client'],
                // 'other_lead' => $data['other_lead'],
                'agency_id' => $data['agency'],
                'percentage' => $data['rate'],
                'date_of_insurance' => $data['issue_dt'],
                'policy_start_period' => $data['comm_dt'],
                'policy_end_period' => $data['expiry_dt'],
                'orignal_endorsment' => $data['newrenewalendorsement'],
                'original_endorsement_other_value' => $data['original_endorsement_other_value'],
                'lead_type' => $data['insu_type'],
                'leader_policy_number' => $data['leader_policy_number'],
                'class_of_business_id' => $data['b_class'],
                'sum_insured' => $data['sum_insured'],
                'gross_premium' => $data['gross_preimmium'],
                'net_premium' => $data['net_premimum'],
            ]);

            return $policy;
        }
    }
}
