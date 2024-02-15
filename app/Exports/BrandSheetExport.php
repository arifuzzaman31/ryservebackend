<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class BrandSheetExport implements FromArray, WithHeadings,WithColumnWidths, WithTitle, WithMapping, WithStyles
{
    public $keys;
    public $rows;

    public function __construct(array $rows,$key)
    {
        $this->rows = $rows;
        $this->keys = $key;
    }

    public function map($row): array
    {
        $va = $this->keys.'_name';
        return [
            $row->id,
            $row->$va
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            ucfirst($this->keys == 'tax' ? 'tax_percentage' : $this->keys.'_name')
        ];
    }

    function array(): array
    {
        return $this->rows;
    }

    public function title(): string
    {
        return ucfirst($this->keys);
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
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 40,            
        ];
    }
}