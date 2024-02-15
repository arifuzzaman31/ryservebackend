<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromView,WithStyles
{
    protected $keyword,$byposition,$status,$invoice;

	public function __construct($keyword,$byposition,$status,$invoice='') {
	        $this->keyword = $keyword;
	        $this->byposition = $byposition;
	        $this->status = $status;
	        $this->invoice = $invoice;
	 }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $order = Order::with('user','delivery','user_billing_info','user_shipping_info','order_details')->orderBy('updated_at','desc');

        if($this->byposition != ''){
            $order = $order->where('order_position',$this->byposition);
        }

        if($this->status != ''){
            $order = $order->where('status',$this->status);
        }

        if ($this->keyword != '')
        {
            $order = $order->where('order_id','like','%'.$this->keyword.'%');
        }

      	$order =  $order->get();
        if($this->invoice == 'invoice'){
            return view('pages.report.excel.invoice_excel',['orders' => $order]);
        }
       return view('pages.order.excel.order_excel',['orders' => $order]);
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
