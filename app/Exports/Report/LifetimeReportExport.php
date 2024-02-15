<?php

namespace App\Exports\Report;

use App\Http\AllStatic;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use DB;

class LifetimeReportExport implements FromView,WithStyles
{
    protected $fromdate,$todate;

    public function __construct($fromdate,$todate) {
        $this->fromdate = $fromdate;
        $this->todate = $todate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        // return Inventory::all();
        $data = User::withCount([
            'order_details as total_spent_amount' => function($query) {
                $query->select(DB::raw('SUM(total_selling_price)'))->where('is_refunded',AllStatic::$inactive);
            },
            'order_details as cancel_count' => function($query) {
                $query->select(DB::raw('COUNT(is_claim_refund)'))->where('is_claim_refund',AllStatic::$active);
            },
            'order_details as refund_count' => function($query) {
                $query->select(DB::raw('COUNT(is_refunded)'))->where('is_refunded',AllStatic::$active);
            },
            'order_details as refunded_amount' => function($query) {
                $query->select(DB::raw('SUM(total_selling_price)'))->where('is_refunded',AllStatic::$active);
            },
            'order_details as count_order' => function($query) {
                $query->select(DB::raw('COUNT(*)'));
            }
        ])->having('count_order', '>', 0)->get();
        
        return view('pages.report.excel.customer_lifetime_value_report',['lifetimevaluedata' => $data]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => [
                'font'      => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FFFF9900']],
                'fill'      => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color'    => ['rgb' => 'f3f7f0'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'E1E1E1'],
                    ],
                ],
            ],
        ];
    }
}
