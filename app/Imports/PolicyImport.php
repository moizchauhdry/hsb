<?php

namespace App\Imports;

use App\Models\Policy;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PolicyImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        
        
        if (count($row) > 0 && !empty($row['policy_no'])) {
            $validator = Validator::make($row, [
                'policy_no' => 'required',
                'client_name' => 'required',
                'insurer_name' => 'required',
                'insurance_date' => 'required',
                'policy_start_period' => 'required',
                'policy_end_period' => 'required',
                'installment_plan' => 'required',
            ]);
        
            $policy = Policy::updateOrCreate([
                'policy_no' => $row['policy_no'],
                'client_id' => $row['client_name'],
                'insurance_id' => $row['insurer_name'],
                'date_of_insurance' => $row['insurance_date'],
                'policy_start_period' => $row['policy_start_period'],
                'policy_end_period' => $row['policy_end_period'],
                'installment_plan' => $row['installment_plan'],
            ]);
        
            return $policy;
        }
        
    }
}