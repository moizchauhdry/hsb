<?php

namespace App\Imports;

use App\Models\Agency;
use App\Models\BusinessClass;
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
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelImport implements ToCollection, WithHeadingRow, WithChunkReading, WithMultipleSheets, ShouldQueue
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        // dd($rows[0]);
        Log::info('Processing chunk with ' . $rows->count() . ' rows.');

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


        foreach ($rows as $row) {

            $insurer_date = [
                'code' => $row['insurer_code'],
                'name' => $row['insurer_name']
            ];
            $insurer = Insurance::updateOrCreate(['code' => $row['insurer_code']], $insurer_date);

            $cob_data = [
                'code' => $row['cob_code'],
                'class_name' => $row['cob_name']
            ];
            $cob = BusinessClass::updateOrCreate(['code' => $row['cob_code']], $cob_data);

            $client_data = [
                'code' => $row['client_code'],
                'name' => $row['client_name']
            ];
            $client = User::updateOrCreate(['code' => $row['client_code']], $client_data);
            $client->syncRoles('client');

            // Prepare Policy data for bulk insert
            $policy_data = [
                'policy_no' => $row['policy_no'],
                'agency_code' => $row['agency_code'],

                'client_id' => $client->id,
                'insurance_id' => $insurer->id,
                'class_of_business_id' => $cob->id,

                'sum_insured' => (int) str_replace(',', '', $row['sum_insured']),
                'gross_premium' => (int) str_replace(',', '', $row['gross_premium']),
                'net_premium' => (int) str_replace(',', '', $row['net_premium']),

                // 'insurer_name' => $row['insurer_name'],
                // 'insurer_code' => $row['insurer_code'],
                // 'client_name' => $row['client_name'],
                // 'client_code' => $row['client_code'],
                // 'agency_code' => $row['agency_code'],
                // 'department_name' => $row['department_name'],
                // 'department_code' => $row['department_code'],
                // 'cob_name' => $row['cob_name'],
                // 'cob_code' => $row['cob_code'],
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
}
