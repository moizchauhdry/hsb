<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PolicyValidationService
{
    /**
     * Renewal + Base Doc Not Found
     * Invalid policy end date (expiry date)
     * Invalid policy start date (inception date)
     * Invalid date of issuance
     * Missing Client name and client code
     * Missing cob name and code
     * Missing agency name and code
     * Missing department name and code
     * Invalid lead type
     * Invalid insurance type
     */



    protected $errors = [];

    /**
     * Validate a single row.
     */
    public function validateRow(array $row)
    {
        $this->errors = [];

        // Check errors
        $this->checkRenewalWithoutBaseDoc($row);
        $this->checkInvalidPolicyEndDate($row);
        $this->checkInvalidPolicyStartDate($row);
        $this->checkMissingClientInfo($row);
        $this->checkMissingCOBInfo($row);
        $this->checkMissingAgencyInfo($row);
        $this->checkMissingDepartmentInfo($row);
        $this->checkLeadType($row);
        $this->checkInvalidInsuranceType($row);

        return $this->errors;
    }

    /**
     * Check if Renewal policy has no base document.
     */
    protected function checkRenewalWithoutBaseDoc($row)
    {
        if ((Str::lower($row['policy_type']) == 'renewal') && (empty($row['base_doc_no']) || $row['base_doc_no'] == '0')) {
            $this->errors[] = [
                'type' => 'Renewal + Base Doc Not Found',
                'message' => 'Base document is required for renewal policies.'
            ];
        }
    }

    /**
     * Check if the policy end date is invalid.
     */
    protected function checkInvalidPolicyEndDate($row)
    {
        if (!empty($row['policy_period_end']) && !empty($row['policy_period_start'])) {
            try {
                // Parse both start and end dates using Carbon (convert DateTime to Carbon)
                $startDate = Carbon::instance(Date::excelToDateTimeObject($row['policy_period_start']));
                $endDate = Carbon::instance(Date::excelToDateTimeObject($row['policy_period_end']));

                // Ensure that the end date is later than the start date
                if ($endDate->lt($startDate)) {
                    $this->errors[] = [
                        'type' => 'Invalid Policy End Date',
                        'message' => 'Policy expiry date cannot be earlier than the policy start date.'
                    ];
                }
            } catch (\Exception $e) {
                $this->errors[] = [
                    'type' => 'Invalid Policy End Date',
                    'message' => 'Policy expiry date is not in a valid format.'
                ];
            }
        }
    }


    /**
     * Check if the policy start date is invalid.
     */
    protected function checkInvalidPolicyStartDate($row)
    {
        if (!empty($row['policy_period_start'])) {
            try {
                $startDate = Carbon::parse($row['policy_period_start']);
            } catch (\Exception $e) {
                $this->errors[] = [
                    'type' => 'Invalid Policy Start Date',
                    'message' => 'Policy inception date is not in a valid format.'
                ];
            }
        }
    }

    /**
     * Check if client name or client code is missing.
     */
    protected function checkMissingClientInfo($row)
    {
        if (empty($row['client_name'])) {
            $this->errors[] = [
                'type' => 'Missing Client Information',
                'message' => 'Client name are required.'
            ];
        }
        if (empty($row['client_code'])) {
            $this->errors[] = [
                'type' => 'Missing Client Information',
                'message' => 'Client code are required.'
            ];
        }
    }
    protected function checkMissingCOBInfo($row)
    {
        if (empty($row['cob_name'])) {
            $this->errors[] = [
                'type' => 'Missing COB Information',
                'message' => 'Cob name are required.'
            ];
        }
        if (empty($row['cob_code'])) {
            $this->errors[] = [
                'type' => 'Missing COB Information',
                'message' => 'Cob code are required.'
            ];
        }
    }
    protected function checkMissingAgencyInfo($row)
    {
        if (empty($row['child_agency_name'])) {
            $this->errors[] = [
                'type' => 'Missing Agency Information',
                'message' => 'Agency name are required.'
            ];
        }
        if (empty($row['agency_code'])) {
            $this->errors[] = [
                'type' => 'Missing Agency Information',
                'message' => 'Agency code are required.'
            ];
        }
    }
    protected function checkMissingDepartmentInfo($row)
    {
        if (empty($row['department_name'])) {
            $this->errors[] = [
                'type' => 'Missing Department Information',
                'message' => 'Department name are required.'
            ];
        }
        if (empty($row['department_code'])) {
            $this->errors[] = [
                'type' => 'Missing Department Information',
                'message' => 'Department code are required.'
            ];
        }
    }
    protected function checkLeadType($row)
    {
        // Ensure 'lead_type' is either "Our Lead" or "Direct ( 100%)"
        if (!empty($row['lead_type'])) {
            $validLeadTypes = ['Our Lead', 'Direct ( 100%)', 'Other Lead'];

            if (!in_array($row['lead_type'], $validLeadTypes)) {
                $this->errors[] = [
                    'type' => 'Invalid Lead Type',
                    'message' => 'Lead type must be either "Our Lead", "Other Lead", or "Direct ( 100%)".'
                ];
            }
        }
    }

    protected function checkInvalidInsuranceType($row)
    {
        // Ensure 'lead_type' is either "Our Lead" or "Direct ( 100%)"
        if (!empty($row['insurance_type'])) {
            $validInsuranceTypes = ['Conventional', 'Takaful'];

            if (!in_array($row['insurance_type'], $validInsuranceTypes)) {
                $this->errors[] = [
                    'type' => 'Invalid Insurance Type',
                    'message' => 'Insurance type must be either "Conventional" or "Takaful".'
                ];
            }
        }
    }
}
