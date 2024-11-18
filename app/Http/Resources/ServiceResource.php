<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OfficialResource;


class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'official_id' => $this->official_id,
            'title' => $this->title,
            'description' => $this->description,
            'eligibility' => $this->eligibility,
            'category' => $this->category,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'status' => $this->status,
            'official' => new OfficialResource($this->whenLoaded('official')),
        ];
    }
}






