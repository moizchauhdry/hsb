<?php

namespace App\Imports;

use App\Models\Agency;
use App\Models\BusinessClass;
use App\Models\Department;
use App\Models\Insurance;
use App\Models\Policy;
use App\Models\PolicyExcel;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterImport;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Events\ImportFailed;

class ExcelImport implements ToCollection, WithHeadingRow, WithChunkReading, WithMultipleSheets, ShouldQueue, WithEvents
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        // dd($rows[0]);
        Log::info('Processing chunk with ' . $rows->count() . ' rows.');
        
        Session::put('excel_import', 'Processing chunk with ' . $rows->count() . ' rows.');


        // $data = $rows->map(function ($row) {
        //     return [
        //         'policy_no' => $row['policy_no'],
        //         'insurer_name' => $row['insurer_name'],
        //         'insurer_code' => $row['insurer_code'],
        //         'client_name' => $row['client_name'],
        //         'client_code' => $row['client_code'],
        //         'agency_code' => $row['agency_code'],
        //         'department_name' => $row['department_name'],
        //         'department_code' => $row['department_code'],
        //         'cob_name' => $row['cob_name'],
        //         'cob_code' => $row['cob_code'],
        //     ];
        // });

        // PolicyExcel::insert($data->toArray()); // Bulk insert

        $agency_id = $insurer_id = $cob_id = $department_id = $client_id = NULL;

        foreach ($rows as $row) {

            $insurer_date = [
                'code' => $row['insurer_code'],
                'name' => $row['insurer_name']
            ];
            $insurer = Insurance::updateOrCreate(['code' => $row['insurer_code']], $insurer_date);
            if ($insurer) {
                $insurer_id = $insurer->id;
            }

            $cob_data = [
                'code' => $row['cob_code'],
                'class_name' => $row['cob_name']
            ];
            $cob = BusinessClass::updateOrCreate(['code' => $row['cob_code']], $cob_data);
            if ($cob) {
                $cob_id = $cob->id;
            }

            $department_data = [
                'code' => $row['department_code'],
                'name' => $row['department_name']
            ];
            $department = Department::updateOrCreate(['code' => $row['department_code']], $department_data);
            if ($department) {
                $department_id = $department->id;
            }

            $client_data = [
                'code' => $row['client_code'],
                'name' => $row['client_name']
            ];
            $client = User::updateOrCreate(['code' => $row['client_code']], $client_data);
            $client->syncRoles('client');
            if ($client) {
                $client_id = $client->id;
            }

            $agency_code = NULL;
            $agency = Agency::where('code', $row['agency_code'])->first();
            if ($agency) {
                $agency_id = $agency->id;
                $agency_code = $agency->code;
            }

            // Prepare Policy data for bulk insert
            $policy_data = [
                'policy_no' => $row['policy_no'],

                'client_id' => $client_id,
                'insurance_id' => $insurer_id,
                'class_of_business_id' => $cob_id,
                'department_id' => $department_id,
                'agency_id' => $agency_id,
                'agency_code' => $agency_code,
                
                'child_agency_name' => $row['child_agency_name'],
                'leader_name' => $row['leader'],
                'leader_policy_number' => $row['leader_policy_no'],
                // 'lead_type' => $row['lead_type'],
               
                // 'date_of_insurance' => !empty($row['date_of_issuance']) ? Date::excelToDateTimeObject($row['date_of_issuance'])->format('Y-m-d') : null,
                // 'policy_start_period' => !empty($row['policy_period_start']) ? Date::excelToDateTimeObject($row['policy_period_start'])->format('Y-m-d') : null,
                // 'policy_end_period' => !empty($row['policy_period_end']) ? Date::excelToDateTimeObject($row['policy_period_end'])->format('Y-m-d') : null,

                'sum_insured' => (int) str_replace(',', '', $row['sum_insured']),
                'gross_premium_100' => (int) str_replace(',', '', $row['gross_premium_100']),
                'gross_premium' => (int) str_replace(',', '', $row['gross_premium']),
                'net_premium_100' => (int) str_replace(',', '', $row['net_premium_100']),
                'net_premium' => (int) str_replace(',', '', $row['net_premium']),

                'rate_percentage' => (int) str_replace(',', '', $row['rate_percentage']),
                'brokerage_percentage' => (int) str_replace(',', '', $row['brokerage_percentage']),
                'brokerage_amount' => (int) str_replace(',', '', $row['brokerage_amount']),

            ];

            $policy = Policy::where('policy_no', $row['policy_no'])->first();
            if ($policy) {
                $policy->update($policy_data);
            } else {
                Policy::create($policy_data);
            }

            // $policy = Policy::updateOrCreate(['policy_no' => $row['policy_no']],['agency_code' => $row['agency_code']], $policy_data);
        }

        // // Bulk insert policies
        // if (!empty($data)) {
        // Policy::insert($policy_data);
        // }

        // Perform upsert (update or insert)
        // Policy::upsert(
        //     $policy_data, // The data array
        //     ['policy_no'], // Columns to check for duplicates
        //     ['agency_code', 'client_id','insurance_id','class_of_business_id'] // Columns to update if a match is found
        // );
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function sheets(): array
    {
        return [
            // 'Report' => new PolicyImport(), // Replace 'Sheet1' with your sheet's name
            // 0 => new PolicyImport(),
            0 => new ExcelImport(),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                Log::info('success import');
                Session::put('excel_import', 'success import');
            },
            ImportFailed::class => function (ImportFailed $event) {
                Log::info("failed excel import");
                Session::put('excel_import', 'failed excel import');
                // $this->importedBy->notify(new ImportHasFailedNotification);
            },
        ];
    }
}
