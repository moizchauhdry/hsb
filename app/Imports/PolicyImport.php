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
                'insurance_date' => 'required|date',
                'policy_start_period' => 'required|date',
                'policy_end_period' => 'required|date',
                'installment_plan' => 'required',
            ], [
                'insurance_date.required' => 'The insurance date is required.',
                'insurance_date.date' => 'The insurance date must be a valid date.',
                'policy_start_period.required' => 'The policy start period is required.',
                'policy_start_period.date' => 'The policy start period must be a valid date.',
                'policy_end_period.required' => 'The policy end period is required.',
                'policy_end_period.date' => 'The policy end period must be a valid date.',
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