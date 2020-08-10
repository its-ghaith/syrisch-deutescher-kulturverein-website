<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimeResource extends JsonResource
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
            'city_time_url'=>$this->times_url,
            'calc_method'=>$this->calc_method,
            'updated_at'=>$this->updated_at,
//            'city'=>new CityResource($this->city),
        ];
    }
}
