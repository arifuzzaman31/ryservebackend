<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\InventoryResource;

class ProductResource extends JsonResource
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
            'id'                => $this->id,
            'p_name'            => $this->product_name,
            'cat_id'            => $this->category_id,
            'p_category'        => new CategoryResource($this->whenLoaded('category')),
            'p_subcategory'     => new CategoryResource($this->whenLoaded('subcategory')),
            'subcat_id'         => $this->sub_category_id,
            'p_tax'             => $this->whenLoaded('vat'),
            'p_tax_id'          => $this->vat_tax_id,
            'p_description'     => strip_tags($this->description),
            'p_raw_description' => $this->description,
            'feature_image'     => $this->product_image,
            'p_image_one'       => $this->image_one,
            'p_image_two'       => $this->image_two,
            'p_image_three'     => $this->image_three,
            'p_image_four'      => $this->image_four,
            'p_image_five'      => $this->image_five,
            'p_cost_price'      => $this->cost,
            'p_sale_price'      => $this->mrp_price,
            'p_dimension'       => 'no-longer',
            'p_length'          => $this->length,
            'p_height'          => $this->height,
            'p_width'           => $this->width,
            'p_unit'            => $this->unit,
            'p_weight'          => $this->weight,
            'fragile'           => $this->fragile,
            'fragile_charge'    => $this->fragile_charge,
            'p_care'            => $this->care,
            'is_new'            => $this->is_new == 1 ? true : false,
            'p_design_code'     => $this->design_code,
            'has_variation'     => $this->has_variation,
            'p_sizes'           => $this->whenLoaded('product_size'),
            'p_colours'         => $this->whenLoaded('product_colour'),
            'p_stocks'          => $this->whenLoaded('inventory'),
            'discount'          => $this->whenLoaded('discount'),
            'p_fabric'          => $this->whenLoaded('product_fabric'),
            'p_vendor'          => $this->whenLoaded('product_vendor'),
            'p_brand'           => $this->whenLoaded('product_brand'),
            'p_designer'        => $this->whenLoaded('product_designer'),
            'p_embellishment'   => $this->whenLoaded('product_embellishment'),
            'p_making'          => $this->whenLoaded('product_making'),
            'p_season'          => $this->whenLoaded('product_season'),
            'p_variety'         => $this->whenLoaded('product_variety'),
            'p_fit'             => $this->whenLoaded('product_fit'),
            'p_artist'          => $this->whenLoaded('product_artist'),
            'p_flat_colour'     => $this->flat_colour,
            'p_consignment'     => $this->whenLoaded('product_consignment'),
            'p_ingredient'      => $this->whenLoaded('product_ingredient'),
            'p_care'            => $this->whenLoaded('product_care'),
            'p_tag'             => $this->whenLoaded('tag'),
            'country_of_origin' => $this->country_of_origin,
            'status'            => $this->status,
            'status_text'       => $this->status == 1 ? 'Active' : 'Deactive',
            'created_date'      => date('j M Y', strtotime($this->created_at)),
        ];
    }
}
