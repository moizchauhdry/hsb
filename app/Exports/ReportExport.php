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
        $query = Policy::policiesList($this->filter, $this->slug);

        return view('exports.report-export', [
            'policies' => $query->get(),
            'filter' => $this->filter,
            'slug' => $this->slug,
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
