<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SellerAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id'=>$this->id,
          'name'=>$this->name,
          'email'=>$this->email,
          'store_name'=>$this->store->name,
          'address'=>$this->store->address,
          'latitude'=>$this->store->latitude,
          'longitude'=>$this->store->longitude,
        ];
    }
}
