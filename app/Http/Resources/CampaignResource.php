<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
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
            'id'            =>  $this->id,
            'camp_name'     =>  $this->campaign_name,
            'slug'          =>  $this->slug,
            'title'         =>  $this->campaign_title,
            'camp_banner'   =>  $this->campaign_banner_default
        ];
    }
}
