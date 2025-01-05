<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Policy;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportExport implements FromView, WithStyles
{
    protected $filter, $slug;

    public function __construct(array $filter, $slug)
    {
        $this->filter = $filter;
        $this->slug = $slug;
    }


    public function view(): View
    {
        $report = true;
        $query = Policy::policiesList($this->filter, 'reports', $report);
        $policies = $query->get();

        $grand_total = [
            'sum_insured' => $policies->sum('sum_insured'),
            'net_premium' => $policies->sum('net_premium'),

            'gross_premium' => $policies->sum('gross_premium'),
            'gross_premium_collected' => $policies->sum('gp_collected'),
            'gross_premium_outstanding' => $policies->sum('gross_premium') - $policies->sum('gp_collected'),

            'brokerage_amount' => $policies->sum('brokerage_amount'),
            'brokerage_received_amount' => $policies->sum('brokerage_received_amount'),
            'brokerage_amount_outstanding' => $policies->sum('brokerage_amount') - $policies->sum('brokerage_received_amount'),
        ];

        return view('exports.report-export', [
            'policies' => $policies,
            'filter' => $this->filter,
            'slug' => $this->slug,
            'grand_total' => $grand_total,
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
