<?php

namespace App\Imports;

use App\Models\Agency;
use App\Models\BusinessClass;
use App\Models\Department;
use App\Models\Insurance;
use App\Models\Policy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PolicyImport implements ToModel, WithCalculatedFormulas, WithHeadingRow, WithChunkReading, ShouldQueue, WithMultipleSheets
{
    public $errors = [];
    private $currentRow = 0; // Variable to track the current row number

    public function model(array $row)
    {
        // dd($row);

        try {
            $this->currentRow++; // Increment the row number each time

            // $original_endorsement_other_value = $original_endorsement = NULL;

            // if (isset($row['originalendormsent'])) {
            //     if ($row['originalendormsent'] == "RENEWAL") {
            //         $original_endorsement = 'renewal';
            //     } elseif ($row['originalendormsent'] == "NEW") {
            //         $original_endorsement = 'new';
            //     } elseif ($row['originalendormsent'] == "ENDORSEMENT") {
            //         $original_endorsement = 'endorsment';
            //     } else {
            //         $original_endorsement = 'others';
            //         $original_endorsement_other_value = $row['originalendormsent'];
            //     }
            // }


            // $agency_id = $insurer_id = $client_id = $cob_id = $department_id = NULL;
            // // $insurer_id = $this->getInsurer($row);
            
            // // $client_id = NULL;
            // // if ($row['client_code'] && $row['client_name']) {
    
            // //     $client = User::where('code', $row['client_code'])->first();
            // //     if ($client) {
            // //         $client->update([
            // //             'name' => $row['client_name'],
            // //         ]);
            // //     } else {
            // //         $client = User::create([
            // //             'name' => $row['client_name'],
            // //             'code' => $row['client_code'],
            // //             'role_users_id' => 2,
            // //             'password' => 0,
            // //         ]);
    
            // //         // $client->syncRoles('client');
            // //     }
    
            // //     $client_id = $client->id;
            // // }

            // // if ($row['agency_code']) {
            // //     $agency = Agency::where('code', $row['agency_code'])->first();
            // //     $agency_id = $agency->id;
            // // }

            // $agency_id = $this->getAgency($row);
            // // $cob_id = $this->getCob($row);
            // // $department_id = $this->getDepartment($row);

            // $gross_premium = 0;
            // $gross_premium_received = 0;
            // $gross_premium_outstanding = 0;

            // if (isset($row['gross_premium']) && is_numeric($row['gross_premium'])) {
            //     $gross_premium = $row['gross_premium'];
            // }

            // if (isset($row['gross_premium_received']) && is_numeric($row['gross_premium_received'])) {
            //     $gross_premium_received = $row['gross_premium_received'];
            // }

            // $gross_premium_outstanding = $gross_premium - $gross_premium_received;

            // $brokerage_amount = 0;
            // $brokerage_percentage = 0;
            // $brokerage_received_amount = 0;
            // $brokerage_status = 0;
            // $balance_amount = 0;
            // $brokerage_paid_date = NULL;

            // if (isset($row['brokerage_amount']) && is_numeric($row['brokerage_amount'])) {
            //     $brokerage_amount = $row['brokerage_amount'];
            // }

            // if (isset($row['brokerage_percentage']) && is_numeric($row['brokerage_percentage'])) {
            //     $brokerage_percentage = $row['brokerage_percentage'];
            // }

            // if (isset($row['brokerage_received_amount']) && is_numeric($row['brokerage_received_amount'])) {
            //     $brokerage_received_amount = $row['brokerage_received_amount'];
            // }

            // if (isset($row['brokerage_paid_date'])) {
            //     $brokerage_paid_date = !empty($row['brokerage_paid_date']) ? Date::excelToDateTimeObject($row['brokerage_paid_date'])->format('Y-m-d') : null;
            // }

            // if (isset($row['brokerage_status'])) {
            //     $brokerage_status = $row['brokerage_status'];
            // }

            // $balance_amount = $brokerage_amount - $brokerage_received_amount;

            // if ($row['policy_no']) {
                $policy = Policy::updateOrCreate(['policy_no' => $row['policy_no']], [
                    'policy_no' => $row['policy_no'],
                    
                    // 'client_id' => $client_id,
                    // 'insurance_id' => $insurer_id,
                    // 'agency_id' => $agency_id,
                    // 'class_of_business_id' => $cob_id,
                    // 'department_id' => $department_id,

                    // // 'agency_code' => $agency_code,
                    // 'child_agency_name' => $row['child_agency'],
                    // 'date_of_insurance' => !empty($row['date_of_issuance']) ? Date::excelToDateTimeObject($row['date_of_issuance'])->format('Y-m-d') : null,
                    // 'policy_start_period' => !empty($row['policy_period_start']) ? Date::excelToDateTimeObject($row['policy_period_start'])->format('Y-m-d') : null,
                    // 'policy_end_period' => !empty($row['policy_period_end']) ? Date::excelToDateTimeObject($row['policy_period_end'])->format('Y-m-d') : null,
                    // // 'orignal_endorsment' => $original_endorsement,
                    // 'original_endorsement_other_value' => $original_endorsement_other_value,
                    // 'leader_policy_number' => $row['leader_policy_no'],

                    // 'gross_premium' => $gross_premium,
                    // 'gross_premium_received' => $gross_premium_received,
                    // 'gross_premium_outstanding' => $gross_premium_outstanding,

                    // 'brokerage_amount' => $brokerage_amount,
                    // 'brokerage_percentage' => $brokerage_percentage,
                    // 'brokerage_received_amount' => $brokerage_received_amount,
                    // 'brokerage_paid_date' => $brokerage_paid_date,
                    // 'brokerage_status' => $brokerage_status,
                    // 'balance_amount' => $balance_amount,

                    // 'sum_insured' => $row['sum_insured'] ?? 0,
                    // 'net_premium' => $row['net_premium'] ?? 0,
                    // 'percentage' =>  $row['rate_percentage'] ?? 0,

                    'user_id' => auth()->id(),
                    'excel_import' => true,
                    'excel_import_at' => Carbon::now(),
                ]);

                Log::info('IMPORT SUCCESS ROW #'. $this->currentRow);

                return $policy;
            // }
        } catch (\Throwable $th) {
            //throw $th;
            abort(403, $th->getMessage() . " ERROR on EXCEL ROW #" . $this->currentRow + 1);
        }
    }

    public function rules(): array
    {
        return [
            'column1' => 'required',
            'column2' => 'required',
        ];
    }

    public function chunkSize(): int
    {
        return 5;
    }

    public function sheets(): array
    {
        return [
            // 'Report' => new PolicyImport(), // Replace 'Sheet1' with your sheet's name
            // 0 => new PolicyImport(),
            0 => new PolicyImport(),
        ];
    }

    // ****************************************

    private function getClient($row)
    {
        $client_id = NULL;

        if ($row['client_code'] && $row['client_name']) {

            $client = User::where('code', $row['client_code'])->first();
            if ($client) {
                $client->update([
                    'name' => $row['client_name'],
                ]);
            } else {
                $client = User::create([
                    'name' => $row['client_name'],
                    'code' => $row['client_code'],
                    'role_users_id' => 2,
                    'password' => 0,
                ]);

                // $client->syncRoles('client');
            }

            $client_id = $client->id;
        }

        return $client_id;
    }

    private function getAgency($row)
    {
        $agency_id = NULL;

        if ($row['agency_code']) {
            $agency = Agency::where('code', $row['agency_code'])->first();
            $agency_id = $agency->id;
        }

        return $agency_id;
    }

    private function getInsurer($row)
    {
        $insurer_id = NULL;

        if ($row['insurer_code'] && $row['insurer_name']) {

            $insurer = Insurance::where('code', $row['insurer_code'])->first();
            if ($insurer) {
                $insurer->update([
                    'name' => $row['insurer_name'],
                ]);
            } else {
                $insurer = Insurance::create([
                    'name' => $row['insurer_name'],
                    'code' => $row['insurer_code'],
                ]);
            }

            $insurer_id = $insurer->id;
        }

        return $insurer_id;
    }

    private function getCob($row)
    {
        $cob_id = NULL;

        // if ($row['cob_code'] && $row['cob_name']) {

        //     $cob = BusinessClass::where('code', $row['cob_code'])->first();
        //     if ($cob) {
        //         $cob->update([
        //             'class_name' => $row['cob_name'],
        //         ]);
        //     } else {
        //         $cob = BusinessClass::create([
        //             'class_name' => $row['cob_name'],
        //             'code' => $row['cob_code'],
        //         ]);
        //     }

        //     $cob_id = $cob->id;
        // }

        return $cob_id;
    }

    private function getDepartment($row)
    {
        $department_id = NULL;

        if ($row['department_code'] && $row['department_name']) {

            $department = Department::where('code', $row['department_code'])->first();
            if ($department) {
                $department->update([
                    'name' => $row['department_name'],
                ]);
            } else {
                $department = Department::create([
                    'name' => $row['department_name'],
                    'code' => $row['department_code'],
                ]);
            }

            $department_id = $department->id;
        }

        return $department_id;
    }
}
