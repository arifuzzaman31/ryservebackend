<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'id'                =>  $this->id,
            'cat_name'          =>  $this->category_name,
            'slug'              =>  $this->slug,
            'parent_cat'        =>  $this->parent_category,
            'cat_img_one'       =>  $this->category_image_one,
            'type_one'          =>  $this->type_one,
            'cat_img_two'       =>  $this->category_image_two,
            'type_two'          =>  $this->type_two,
            'cat_img_three'     =>  $this->category_image_three,
            'type_three'        =>  $this->type_three,
            'category_feature_image'         =>  $this->category_feature_image,
            'children'          =>   $this->whenLoaded('children'),
            'status'            =>  $this->status,
            'whats_new'         =>  $this->whats_new == 0 ? false : true,
            'status_text'       =>  $this->status == 0 ? 'Active':'Deactive',
            'precedence'        =>  $this->precedence
        ];
    }
}
