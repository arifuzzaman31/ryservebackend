<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CategorySheetExport implements FromArray, WithHeadings, WithTitle, WithColumnWidths, WithColumnFormatting, WithStyles, WithMapping
{
    protected $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->category_name,
            $row->parent_category
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Category_Name',
            'Parent_Category'
        ];
    }

    function array(): array
    {
        return $this->rows;
    }

    public function title(): string
    {
        return 'Category';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => [
                'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFF9900']],
                'fill'      => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color'    => ['rgb' => 'FAD7DD'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '#,##0',
            'B' => '#,##0',
            'C' => '#,##0',
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 40,            
            'C' => 30,            
        ];
    }
}