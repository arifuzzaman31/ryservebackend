<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_id'          => $this->order_id,
            'user_id'           => $this->user_id,
            'total_price'       => $this->total_price,
            'charge_total_price' => $this->charge_total_price,
            'order_detail'      =>  $this->whenLoaded('order_details'),
            'vat_rate'          => $this->vat_rate,
            'charge_vat_rate'   => $this->charge_vat_rate,
            'vat_amount'        => $this->vat_amount,
            'charge_vat_amount' => $this->charge_vat_amount,
            'total_item'        => $this->total_item,
            'shipping_method'   => $this->shipping_method,
            'coupon'            => $this->coupon,
            'payment_method'    => $this->payment_method,
            'payment_method_text' => paymentMethodType($this->payment_method),
            'payment_method_name' => $this->payment_method_name,
            'discount'          => $this->discount,
            'charge_discount'   => $this->charge_discount,
            'coupon_discount'   => $this->coupon_discount,
            'is_claim_refund'   => $this->is_claim_refund,
            'refund_claim_date' => $this->refund_claim_date != null ? date('j M Y', strtotime($this->refund_claim_date)) : '',
            'payment_status'    => $this->payment_status,
            'order_position'    => $this->order_position,
            'payment_status_text' => paymentStatusText($this->payment_status),
            'order_date'        => date('j M Y', strtotime($this->order_date)),
            'requested_delivery_date' => $this->requested_delivery_date,
            'payment_date'      => date('j M Y', strtotime($this->payment_date)),
            'shipping_amount'   => $this->shipping_amount,
            'tracking_id'       => $this->tracking_id,
            'status'            => $this->status,
            'charged_currency'  => $this->charged_currency,
            // 'customer_note'     => $this->user_note,
            'status_text'       => orderStatus($this->status),
            'created_date'      => date('j M Y', strtotime($this->created_at)),
        ];
    }
}
