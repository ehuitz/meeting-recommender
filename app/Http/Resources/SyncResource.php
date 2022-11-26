<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SyncResource extends JsonResource
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
            'id' => $this->id,
            'status' => $this->status,
            'origin' => $this->origin,
            'name' => $this->name,
            'path' => $this->path,
            'created_at' => $this->created_at->toDateString()
        ];
    }
}
