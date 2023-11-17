<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OfferCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'offerId' => $this->id,
            'productId' => $this->product()->first()->id,
            // 'product' => [
            //     'name' => $this->product()->first()->name,
            //     'category' => $this->product()->first()->category,
            //     'price' => $this->product()->first()->price,
            // ],
            'sellerId' => $this->seller_id,
            'price' => $this->price,
            'condition' => $this->condition,
            'availability' => $this->availability,
        ];
    }
}
