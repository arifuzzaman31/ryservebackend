<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use AmrShawky\LaravelCurrency\Facade\Currency;

class InventoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            // 'id'                =>  $this->id,
            // 'product_id'        =>  $this->product_id,
            // 'colour_id'         =>  $this->colour_id,
            // 'size_id'           =>  $this->size_id,
            // 'stock'             =>  $this->stock,
            'cpu'               =>  $this->cpu,
            // 'mrp'               =>  Currency::convert()->from('BDT')->to('EUR')->amount($this->mrp)->get(),
            // 'sku'               =>  $this->sku,
            // 'warning_amount'    =>  $this->warning_amount,
            // 'warehouse'         =>  $this->warehouse,
            // 'created_at'        =>  $this->created_at,
            // 'updated_at'        =>  $this->updated_at
        ];
    }
}
