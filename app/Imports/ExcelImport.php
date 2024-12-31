<?php

namespace App\Imports;

use App\Models\Agency;
use App\Models\BusinessClass;
use App\Models\Department;
use App\Models\Insurance;
use App\Models\Policy;
use App\Models\PolicyExcel;
use App\Models\User;
use Carbon\Carbon;
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
    protected $excel_type;

    function __construct($type)
    {
        $this->excel_type = $type;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        // dd($rows[0]);

        Log::channel('database')->info('Processing chunk with ' . $rows->count() . ' rows.', ['type' => 'excel_import']);

        $agency_id = $insurer_id = $cob_id = $department_id = $client_id = NULL;

        foreach ($rows as $row) {

            if (isset($row['insurer_code']) && isset($row['insurer_name'])) {
                $insurer_date = [
                    'code' => $row['insurer_code'],
                    'name' => $row['insurer_name']
                ];
                $insurer = Insurance::updateOrCreate(['code' => $row['insurer_code']], $insurer_date);
                if ($insurer) {
                    $insurer_id = $insurer->id;
                }
            }

            if (isset($row['cob_code']) && isset($row['cob_name'])) {
                $cob_data = [
                    'code' => $row['cob_code'],
                    'class_name' => $row['cob_name']
                ];
                $cob = BusinessClass::updateOrCreate(['code' => $row['cob_code']], $cob_data);
                if ($cob) {
                    $cob_id = $cob->id;
                }
            }

            if (isset($row['department_code']) && isset($row['department_name'])) {
                $department_data = [
                    'code' => $row['department_code'],
                    'name' => $row['department_name']
                ];
                $department = Department::updateOrCreate(['code' => $row['department_code']], $department_data);
                if ($department) {
                    $department_id = $department->id;
                }
            }

            if (isset($row['client_code']) && isset($row['client_name'])) {
                $client_data = [
                    'code' => $row['client_code'],
                    'name' => $row['client_name']
                ];

                $client = User::where('code', $row['client_code'])->role('client')->first();

                if ($client) {
                    $client->update($client_data);
                    $client->syncRoles('client');
                } else {
                    $client = User::create($client_data);
                    $client->assignRole('client');
                }

                if ($client) {
                    $client_id = $client->id;
                }
            }

            $agency_code = NULL;
            if (isset($row['agency_code'])) {
                $agency = Agency::where('code', $row['agency_code'])->first();
                if ($agency) {
                    $agency_id = $agency->id;
                    $agency_code = $agency->code;
                }
            }


            $date_of_issuance = NULL;
            if (isset($row['date_of_issuance'])) {
                $date_of_issuance = Carbon::parse($row['date_of_issuance'])->format('Y-m-d');
            }

            $policy_period_start = NULL;
            if (isset($row['policy_period_start'])) {
                $policy_period_start = Carbon::parse($row['policy_period_start'])->format('Y-m-d');
            }

            $policy_period_end = NULL;
            if (isset($row['policy_period_end'])) {
                $policy_period_end = Carbon::parse($row['policy_period_end'])->format('Y-m-d');
            }


            $sum_insured = NULL;
            if (isset($row['sum_insured'])) {
                $sum_insured = (int) str_replace(',', '', $row['sum_insured']);
            }

            $gross_premium_100 = NULL;
            if (isset($row['gross_premium_100'])) {
                $gross_premium_100 = (int) str_replace(',', '', $row['gross_premium_100']);
            }

            $gross_premium = NULL;
            if (isset($row['gross_premium'])) {
                $gross_premium = (int) str_replace(',', '', $row['gross_premium']);
            }

            $net_premium_100 = NULL;
            if (isset($row['net_premium_100'])) {
                $net_premium_100 = (int) str_replace(',', '', $row['net_premium_100']);
            }

            $net_premium_100 = NULL;
            if (isset($row['net_premium_100'])) {
                $net_premium_100 = (int) str_replace(',', '', $row['net_premium_100']);
            }

            $net_premium = NULL;
            if (isset($row['net_premium'])) {
                $net_premium = (int) str_replace(',', '', $row['net_premium']);
            }

            $rate_percentage = NULL;
            if (isset($row['rate_percentage'])) {
                $rate_percentage = (int) str_replace(',', '', $row['rate_percentage']);
            }

            $gp_collected = NULL;
            if (isset($row['gp_collected'])) {
                $gp_collected = (int) str_replace(',', '', $row['gp_collected']);
            }

            $brokerage_percentage = NULL;
            if (isset($row['brokerage_percentage'])) {
                $brokerage_percentage = (int) str_replace(',', '', $row['brokerage_percentage']);
            }

            $brokerage_amount = NULL;
            if (isset($row['brokerage_amount'])) {
                $brokerage_amount = (int) str_replace(',', '', $row['brokerage_amount']);
            }

            $brokerage_received_amount = NULL;
            if (isset($row['brokerage_received_amount'])) {
                $brokerage_received_amount = (int) str_replace(',', '', $row['brokerage_received_amount']);
            }

            $policy_data = [
                'policy_no' => $row['policy_no'],
            ];

            if (!empty($client_id)) {
                $policy_data['client_id'] = $client_id;
            }

            // $policy_data = [
            //     'policy_no' => $row['policy_no'],

            //     'client_id' => $client_id,
            //     'insurer_id' => $insurer_id,
            //     'cob_id' => $cob_id,
            //     'department_id' => $department_id,
            //     'agency_id' => $agency_id,
            //     'agency_code' => $agency_code,

            //     'child_agency_name' => $row['child_agency_name'] ?? null,
            //     'leader_name' => $row['leader_name'] ?? null,
            //     'leader_policy_no' => $row['leader_policy_no'] ?? null,

            //     'lead_type' => $this->getLeadType($row),

            //     'date_of_issuance' => $date_of_issuance,
            //     'policy_period_start' => $policy_period_start,
            //     'policy_period_end' => $policy_period_end,

            //     'sum_insured' => $sum_insured,
            //     'gross_premium_100' => $gross_premium_100,
            //     'gross_premium' => $gross_premium,
            //     'net_premium_100' => $net_premium_100,
            //     'net_premium' => $net_premium,
            //     'rate_percentage' => $rate_percentage,
            //     'gp_collected' => $gp_collected,

            //     'brokerage_percentage' => $brokerage_percentage,
            //     'brokerage_amount' => $brokerage_amount,
            //     'brokerage_received_amount' => $brokerage_received_amount,

            //     'base_doc_no' => $row['base_doc_no'] ?? null,
            //     'policy_type' => $this->getPolicyType($row)['policy_type'],
            //     'policy_type_other' => $this->getPolicyType($row)['policy_type_other'],
            //     'insurance_type' => $row['insurance_type'] ?? null,
            //     'branch' => $row['branch'] ?? NULL,

            //     'receipt_no' => $row['receipt_no'] ?? NULL,
            //     // 'receipt_date' => $row['receipt_date'],
            //     'receipt_amount' => $row['receipt_amount'] ?? NULL,

            //     'excel_import' => true,
            //     'excel_import_at' => Carbon::now(),
            //     'user_id' => auth()->id(),
            // ];

            $policy_data = array_filter([
                'policy_no' => $row['policy_no'],
            
                'client_id' => $client_id,
                'insurer_id' => $insurer_id,
                'cob_id' => $cob_id,
                'department_id' => $department_id,
                'agency_id' => $agency_id,
                'agency_code' => $agency_code,
            
                'child_agency_name' => $row['child_agency_name'],
                'leader_name' => $row['leader_name'],
                'leader_policy_no' => $row['leader_policy_no'],
            
                'lead_type' => $this->getLeadType($row),
            
                'date_of_issuance' => $date_of_issuance,
                'policy_period_start' => $policy_period_start,
                'policy_period_end' => $policy_period_end,
            
                'sum_insured' => $sum_insured,
                'gross_premium_100' => $gross_premium_100,
                'gross_premium' => $gross_premium,
                'net_premium_100' => $net_premium_100,
                'net_premium' => $net_premium,
                'rate_percentage' => $rate_percentage,
                'gp_collected' => $gp_collected,
            
                'brokerage_percentage' => $brokerage_percentage,
                'brokerage_amount' => $brokerage_amount,
                'brokerage_received_amount' => $brokerage_received_amount,
            
                'base_doc_no' => $row['base_doc_no'] ?? null,
                'policy_type' => $this->getPolicyType($row)['policy_type'],
                'policy_type_other' => $this->getPolicyType($row)['policy_type_other'],
                'insurance_type' => $row['insurance_type'] ?? null,
                'branch' => $row['branch'],
            
                'receipt_no' => $row['receipt_no'] ?? null,
                'receipt_amount' => $row['receipt_amount'] ?? null,
            
                'excel_import' => true,
                'excel_import_at' => Carbon::now(),
                'user_id' => auth()->id(),
            ], function ($value) {
                return $value !== null;
            });
            

            if ($this->excel_type == 1) {
                $policy = Policy::where('policy_no', $row['policy_no'])->where('policy_type', $row['policy_type'])->first();
                if ($policy) {
                    $policy->update($policy_data);
                } else {
                    Policy::create($policy_data);
                }
            }
            
            if ($this->excel_type == 2) {
                $policy = Policy::where('policy_no', $row['policy_no'])->first();
                if ($policy) {
                    $policy->update($policy_data);
                }
            }
        }
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
            0 => new ExcelImport($this->excel_type),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                Log::channel('database')->info('Excel import completed successfully.', ['type' => 'excel_import', 'import_completed' => true]);
            },
            ImportFailed::class => function (ImportFailed $event) {
                Log::channel('database')->error('failed excel import.', ['type' => 'excel_import', 'import_completed' => true]);
                // $this->importedBy->notify(new ImportHasFailedNotification);
            },
        ];
    }

    public function getLeadType($row)
    {
        $lead_type = $res = null;

        if (isset($row['lead_type'])) {
            $lead_type = $row['lead_type'];

            if ($lead_type == "Direct ( 100%)") {
                $res = 'direct';
            } else if ($lead_type == "Other Lead") {
                $res = 'other';
            } else if ($lead_type == "Our Lead") {
                $res = 'our';
            } else {
                $res = NULL;
            }
        }

        return $res;
    }

    public function getPolicyType($row)
    {
        $policy_type = $policy_type_other = NULL;

        if (isset($row['policy_type'])) {
            $policy_type_row = $row['policy_type'];

            if ($policy_type_row) {
                if ($policy_type_row == "New") {
                    $policy_type = 'new';
                } else if ($policy_type_row == "Renewed") {
                    $policy_type = 'renewal';
                } else if ($policy_type_row == "New Cover") {
                    $policy_type = 'cover';
                } else if ($policy_type_row == "Endorsement") {
                    $policy_type = 'endorsement';
                }
            }
        }

        return [
            'policy_type' => $policy_type,
            'policy_type_other' => $policy_type_other
        ];
    }
}
