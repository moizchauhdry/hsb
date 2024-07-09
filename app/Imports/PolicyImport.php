<?php

namespace App\Imports;

use App\Models\Agency;
use App\Models\BusinessClass;
use App\Models\Policy;
use App\Models\User;
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
        try {

            // dd($row);

            $original_endorsement_other_value = '';

            if ($row['originalendormsent'] == "RENEWAL") {
                $original_endorsement = 'renewal';
            } elseif ($row['originalendormsent'] == "NEW") {
                $original_endorsement = 'new';
            } elseif ($row['originalendormsent'] == "ENDORSEMENT") {
                $original_endorsement = 'endorsment';
            } else {
                $original_endorsement = 'others';
                $original_endorsement_other_value = $row['originalendormsent'];
            }

            // // lead_type check start //
            // if ($row['insu_type'] == "Direct ( 100%)") {
            //     $insu_type = 1;
            // } elseif ($row['insu_type'] == "Our Lead") {
            //     $insu_type = 2;
            // } elseif ($row['insu_type'] == "Other Lead") {
            //     $insu_type = 3;
            // }


            $client_id = NULL;
            if ($row['client_name']) {
                $client = User::updateOrCreate(['name' => $row['client_name']], [
                    'name' => $row['client_name'],
                    'role_users_id' => 2,
                ]);

                $client_id = $client->id;
            }

            $agency_id = NULL;
            if ($row['agency']) {
                $agency = Agency::updateOrCreate(['name' => $row['agency']], [
                    'name' => $row['agency'],
                ]);

                $agency_id = $agency->id;
            }

            $cob_id = NULL;
            $department_id = NULL;
            if ($row['class_of_business']) {
                $cob = BusinessClass::updateOrCreate(['class_name' => $row['class_of_business']], [
                    'class_name' => $row['class_of_business'],
                ]);

                $cob_id = $cob->id;
                $department_id = $cob->department_id;
            }


            $data = [
                'policy_no' => $row['policy'],
                // 'department' => $row['department'],
                // 'other_lead' => $row['other_lead'],
                'date_of_issuance' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_issuance'])->format('Y-m-d'),
                'policy_period_start' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['policy_period_start'])->format('Y-m-d'),
                'policy_period_end' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['policy_period_end'])->format('Y-m-d'),
                'original_endormsent' => $original_endorsement,
                'original_endorsement_other_value' => $original_endorsement_other_value,
                // 'insu_type' => $insu_type,
                'leader_policy_no' => $row['leader_policy_no'],
                'sum_insured' => $row['sum_insured'],
                'gross_premium' => $row['gross_premium'],
                'net_premium' => $row['net_premium'],
                'rate' => $row['rate'],
            ];

            if (count($data) > 0 && !empty($data['policy_no'])) {
                // $validator = Validator::make($data, [
                //     'policy' => 'required',
                //     // 'department' => 'required',
                //     'client' => 'required',
                //     'agency' => 'required',

                //     'date_of_issuance' => 'required|date',
                //     'policy_period_start' => 'required|date',
                //     'policy_period_end' => 'required|date',

                //     'originalendormsent' => 'required',
                //     // 'insu_type' => 'required',
                //     'leader_policy_no' => 'required',
                //     'class_of_business' => 'required',

                //     'sum_insured' => 'required',
                //     'gross_premium' => 'required',
                //     'net_premium' => 'required',
                //     'rate' => 'required',
                // ]);

                // if ($validator->fails()) {
                //     $this->errors[] = $validator->errors()->all();
                //     dd($this->errors);
                //     // return null;
                // }

                $policy = Policy::updateOrCreate(['policy_no' => $data['policy_no']], [
                    'policy_no' => $data['policy_no'],
                    'department_id' => $department_id,
                    'client_id' => $client_id,
                    'agency_id' => $agency_id,
                    'class_of_business_id' => $cob_id,

                    'date_of_insurance' => $data['date_of_issuance'],
                    'policy_start_period' => $data['policy_period_start'],
                    'policy_end_period' => $data['policy_period_end'],

                    'orignal_endorsment' => $data['original_endormsent'],
                    'original_endorsement_other_value' => $data['original_endorsement_other_value'],

                    'leader_policy_number' => $data['leader_policy_no'],

                    'sum_insured' => $data['sum_insured'],
                    'gross_premium' => $data['gross_premium'],
                    'net_premium' => $data['net_premium'],
                    'percentage' => $data['rate'],

                    // 'other_lead' => $data['other_lead'],
                    // 'lead_type' => $data['insu_type'],

                ]);

                return $policy;
            }
        } catch (\Throwable $th) {
            //throw $th;
            abort(403, $th->getMessage());
        }
    }
}
