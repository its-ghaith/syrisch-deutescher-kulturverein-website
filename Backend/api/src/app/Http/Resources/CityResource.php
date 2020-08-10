<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'admin_id' => $this->admin_id,
            'state' => $this->state,
            'country' => $this->country,
            'photo_url' => $this->photo_url,
            'admin_trashed' => isset(User::withTrashed()->find($this->admin_id)->deleted_at),
            'lon' => $this->lon,
            'lat' => $this->lat,
            'updated_at' => $this->updated_at,
            'time'=> new TimeResource($this->time)
        ];
    }
}
