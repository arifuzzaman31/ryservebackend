<?php

namespace App\Exports\Report;

use App\Http\AllStatic;
use App\Models\OrderDetails;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RefundReportExport implements FromView,WithStyles
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
        $data = OrderDetails::with(['user:id,name,phone,address,email','order:id,order_date,total_price,payment_via,payment_method_name'])
        ->where('is_claim_refund',AllStatic::$active)->latest()->get();
        
        return view('pages.report.excel.refund_report',['refunddata' => $data]);
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
