<?php

namespace App\Exports;

use App\Models\Policy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromView;

class RenewalExport implements FromView, WithStyles
{   
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function view(): View
    {
        $role = $this->user->roles[0];

        $query = Policy::from('policies as p');

        $query->leftJoin('policy_claims as pc', 'pc.policy_id', '=', 'p.id');
        $query->leftJoin('users as client', 'client.id', '=', 'p.client_id');
        $query->leftJoin('agencies as agency', 'agency.id', '=', 'p.agency_id');
        $query->leftJoin('business_classes as cob', 'cob.id', '=', 'p.cob_id');
        $query->leftJoin('departments as d', 'd.id', '=', 'cob.department_id');
        $query->leftJoin('groups as g', 'g.id', '=', 'cob.group_id');
        $query->leftJoin('policy_renewal_statuses as renewal_status', 'renewal_status.id', '=', 'p.renewal_status_id');

        $startOfNextMonth = Carbon::now()->addMonth()->startOfMonth()->format('Y-m-d');
        $endOfNextMonth = Carbon::now()->addMonth()->endOfMonth()->format('Y-m-d');

        $query->whereDate('p.policy_period_end', '>=', $startOfNextMonth);
        $query->whereDate('p.policy_period_end', '<=', $endOfNextMonth);

        if ($role->id != 1) {
            $query->leftJoin('user_cobs as uc', function ($join) {
                $join->on('uc.cob_id', '=', 'p.cob_id')->where('uc.user_id', $this->user->id);
            })
                ->leftJoin('user_clients as ucl', function ($join) {
                    $join->on('ucl.client_id', '=', 'p.client_id')->where('ucl.user_id', $this->user->id);
                })
                ->where(function ($q) {
                    $q->whereNotNull('uc.id')->orWhereNotNull('ucl.id');
                });
        }

        $query->select(
            'p.id as p_id',
            'p.policy_no as policy_no',
            'p.base_doc_no as base_doc_no',
            'p.client_id as client_id',
            'p.policy_period_end as expiry_date',
            'p.policy_type as policy_type',
            'p.lead_type as policy_lead_type',
            'renewal_status.name as renewal_status',
            'client.name as client_name',
            'agency.name as agency_name',
            'cob.class_name as cob_name',
            DB::raw('COUNT(DISTINCT pc.id) as claim_count'),
        );

        $query->groupBy(
            'p.id',
            'p.policy_no',
            'p.base_doc_no',
            'p.client_id',
            'p.policy_period_end',
            'p.policy_type',
            'p.lead_type',
            'renewal_status.name',
            'client.name',
            'agency.name',
            'cob.class_name'
        );

        $policies = $query->get();

        return view('exports.renewal-export', [
            'policies' => $policies
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        foreach (range('A', 'Z') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
        ]);

        $sheet->getStyle('A2:J2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '023eb5',
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Center the text
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Center vertically
            ],
        ]);

        return [
            // Optionally, set styles for specific rows or columns
            1 => ['font' => ['bold' => true]], // First row bold
            2 => ['font' => ['bold' => true]], // First row bold
        ];
    }
}
