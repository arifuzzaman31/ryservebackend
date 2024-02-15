<?php

namespace App\Exports\Report;

use App\Models\Inventory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CampaignReportExport implements FromView,WithStyles
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
        $data = Inventory::with('product.category:id,category_name','product.subcategory:id,category_name',
            'product.product_brand:id,brand_name','product.product_fabric:id,fabric_name','colour:id,color_name',
            'product.product_size:id,size_name','product.product_designer:id,designer_name','product.product_embellishment:id,embellishment_name',
            'product.product_making:id,making_name','product.product_season:id,season_name','product.product_variety:id,variety_name',
            'product.product_fit:id,fit_name','product.product_artist:id,artist_name','product.product_consignment:id,consignment_name',
            'product.product_ingredient:id,ingredient_name','product.campaign:id,campaign_name,campaign_start_date,campaign_expire_date')
            ->selectRaw('order_details.product_id, sum(quantity) as sales_quantity,sum(total_selling_price) as total_selling_amount,
            sum(vat_amount) as total_vat_amount,ROUND(sum(total_buying_price),3) as total_buying_amount,
            ROUND(sum(total_selling_price - total_buying_price),3) as profit,inventories.stock as current_stock,
            inventories.sku as p_sku,inventories.colour_id,inventories.size_id,inventories.created_at')
            ->join('order_details', 'inventories.product_id', '=', 'order_details.product_id')
            ->whereColumn('inventories.product_id', 'order_details.product_id')
            ->whereColumn('inventories.colour_id', 'order_details.colour_id')
            ->whereColumn('inventories.size_id', 'order_details.size_id')
            ->groupBy('p_sku')
            ->groupBy('order_details.product_id')
            ->groupBy('current_stock')
            ->groupBy('inventories.colour_id')
            ->groupBy('inventories.size_id')
            ->groupBy('inventories.created_at')
            ->orderByDesc('sales_quantity')
            ->whereHas('product', function ($query) {
                 $query->has('campaign');
            })->get();
            return view('pages.report.excel.campaign_report',['campaigndata' => $data]);
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
