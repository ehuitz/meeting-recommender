<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusyResource extends JsonResource
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
            'title' => $this->title,
            'employee_id' => $this->employee_id,
            'employee' => $this->employee->name,
            'date' => $this->date,
            'start_time' => date("H:i:s", strtotime($this->start_time)),
            'end_time' =>date("H:i:s", strtotime($this->end_time)),
        ];
    }
}
