<?php

namespace App\Imports;

use App\Models\Agency;
use App\Models\BusinessClass;
use App\Models\Insurance;
use App\Models\Policy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PolicyImport implements ToModel, WithHeadingRow
{
    public $errors = [];
    private $currentRow = 0; // Variable to track the current row number

    public function model(array $row)
    {
        // dd($row);
        
        try {
            $this->currentRow++; // Increment the row number each time

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

            $client_id = NULL;
            if ($row['client_name']) {
                $client = User::updateOrCreate(['name' => $row['client_name']], [
                    'name' => $row['client_name'],
                    'role_users_id' => 2,
                    'password' => Hash::make(0),
                ]);
                $client->syncRoles('client');

                $client_id = $client->id;
            }

            $agency_id = $agency_code =  NULL;
            if ($row['agency_code']) {
                $agency = Agency::where('code', $row['agency_code'])->first();
                if ($agency) {
                    $agency_id = $agency->id;
                    $agency_code = $agency->code;
                } else {
                    abort(403, 'Agency');
                }
            }

            $insurer_id = NULL;
            if ($row['insurer_name']) {
                $insurer = Insurance::where('name', $row['insurer_name'])->first();
                if ($insurer) {
                    $insurer_id = $insurer->id;
                } else {
                    abort(403, 'Insurer');
                }
            }

            $cob_id = NULL;
            $department_id = NULL;
            if ($row['class_of_business']) {
                $cob = BusinessClass::updateOrCreate(['class_name' => $row['class_of_business']], [
                    'class_name' => $row['class_of_business'],
                    // 'department_id' => 0,
                ]);

                $cob_id = $cob->id;
                $department_id = $cob->department_id;
            }


            $data = [
                'policy_no' => $row['policy'],
                'date_of_issuance' => !empty($row['date_of_issuance']) ? Date::excelToDateTimeObject($row['date_of_issuance'])->format('Y-m-d') : null,
                'policy_period_start' => !empty($row['policy_period_start']) ? Date::excelToDateTimeObject($row['policy_period_start'])->format('Y-m-d') : null,
                'policy_period_end' => !empty($row['policy_period_end']) ? Date::excelToDateTimeObject($row['policy_period_end'])->format('Y-m-d') : null,
                'original_endormsent' => $original_endorsement,
                'original_endorsement_other_value' => $original_endorsement_other_value,
                'leader_policy_no' => $row['leader_policy_no'],
                'sum_insured' => $row['sum_insured'],
                'gross_premium' => $row['gross_premium'],
                'net_premium' => $row['net_premium'],
                'rate' => $row['rate'],
                'agency' => $row['agency'],
            ];

            if (count($data) > 0 && !empty($data['policy_no'])) {

                $policy = Policy::updateOrCreate(['policy_no' => $data['policy_no']], [
                    'policy_no' => $data['policy_no'],
                    'department_id' => $department_id,
                    'client_id' => $client_id,
                    'insurance_id' => $insurer_id,

                    'agency_id' => $agency_id,
                    'agency_code' => $agency_code,
                    'child_agency_name' => $data['agency'],

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

                    'user_id' => auth()->id(),
                    'excel_import' => true,
                    'excel_import_at' => Carbon::now(),
                ]);

                return $policy;
            }
        } catch (\Throwable $th) {
            //throw $th;
            abort(403, $th->getMessage() . " ERROR on EXCEL ROW #" . $this->currentRow + 1);
        }
    }
}
