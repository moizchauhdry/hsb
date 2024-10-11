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

            $original_endorsement_other_value = $original_endorsement = NULL;

            if (isset($row['originalendormsent'])) {
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
                    // abort(403, 'Agency');
                }
            }

            $insurer_id = NULL;
            if ($row['insurer_name']) {
                $insurer = Insurance::where('name', $row['insurer_name'])->first();
                if ($insurer) {
                    $insurer_id = $insurer->id;
                } else {
                    // abort(403, 'Insurer');
                }
            }

            $cob_id = NULL;
            $department_id = NULL;
            if ($row['class_of_business']) {
                $cob = BusinessClass::updateOrCreate(['class_name' => $row['class_of_business']], [
                    'class_name' => $row['class_of_business'],
                    'department_id' => 0,
                ]);

                $cob_id = $cob->id;
                $department_id = $cob->department_id;
            }

            $gross_premium = 0;
            $gross_premium_received = 0;
            $gross_premium_outstanding = 0;

            if (isset($row['gross_premium']) && is_numeric($row['gross_premium'])) {
                $gross_premium = $row['gross_premium'];
            }

            if (isset($row['gross_premium_received']) && is_numeric($row['gross_premium_received'])) {
                $gross_premium_received = $row['gross_premium_received'];
            }

            $gross_premium_outstanding = $gross_premium - $gross_premium_received;

            $brokerage_amount = 0;
            $brokerage_percentage = 0;
            $brokerage_received_amount = 0;
            $brokerage_status = 0;
            $balance_amount = 0;
            $brokerage_paid_date = NULL;

            if (isset($row['brokerage_amount']) && is_numeric($row['brokerage_amount'])) {
                $brokerage_amount = $row['brokerage_amount'];
            }

            if (isset($row['brokerage_percentage']) && is_numeric($row['brokerage_percentage'])) {
                $brokerage_percentage = $row['brokerage_percentage'];
            }

            if (isset($row['brokerage_received_amount']) && is_numeric($row['brokerage_received_amount'])) {
                $brokerage_received_amount = $row['brokerage_received_amount'];
            }

            if (isset($row['brokerage_paid_date'])) {
                $brokerage_paid_date = !empty($row['brokerage_paid_date']) ? Date::excelToDateTimeObject($row['brokerage_paid_date'])->format('Y-m-d') : null;
            }

            if (isset($row['brokerage_status'])) {
                $brokerage_status = $row['brokerage_status'];
            }

            $balance_amount = $brokerage_amount - $brokerage_received_amount;
            
            if ($row['policy_no']) {
                $policy = Policy::updateOrCreate(['policy_no' => $row['policy_no']], [
                    'policy_no' => $row['policy_no'],
                    'department_id' => $department_id,
                    'client_id' => $client_id,
                    'insurance_id' => $insurer_id,
                    'agency_id' => $agency_id,
                    'agency_code' => $agency_code,
                    'child_agency_name' => $row['agency'],
                    'class_of_business_id' => $cob_id,
                    'date_of_insurance' => !empty($row['date_of_issuance']) ? Date::excelToDateTimeObject($row['date_of_issuance'])->format('Y-m-d') : null,
                    'policy_start_period' => !empty($row['policy_period_start']) ? Date::excelToDateTimeObject($row['policy_period_start'])->format('Y-m-d') : null,
                    'policy_end_period' => !empty($row['policy_period_end']) ? Date::excelToDateTimeObject($row['policy_period_end'])->format('Y-m-d') : null,
                    'orignal_endorsment' => $original_endorsement,
                    'original_endorsement_other_value' => $original_endorsement_other_value,
                    'leader_policy_number' => $row['leader_policy_no'],

                    'gross_premium' => $gross_premium,
                    'gross_premium_received' => $gross_premium_received,
                    'gross_premium_outstanding' => $gross_premium_outstanding,

                    'brokerage_amount' => $brokerage_amount,
                    'brokerage_percentage' => $brokerage_percentage,
                    'brokerage_received_amount' => $brokerage_received_amount,
                    'brokerage_paid_date' => $brokerage_paid_date,
                    'brokerage_status' => $brokerage_status,
                    'balance_amount' => $balance_amount,

                    'sum_insured' => $row['sum_insured'] ?? 0,
                    'net_premium' => $row['net_premium'] ?? 0,
                    'percentage' =>  $row['rate_percentage'] ?? 0,

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
