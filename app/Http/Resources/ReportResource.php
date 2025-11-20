<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'location'    => $this->location,
            'photo_url'   => $this->photo_path ? url('storage/' . $this->photo_path) : null,
            'status'      => $this->status,

            'created_at'  => $this->created_at
                                ? $this->created_at->format('Y-m-d H:i')
                                : null,
        ];
    }
}
