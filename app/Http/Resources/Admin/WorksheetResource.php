<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Worksheet */
class WorksheetResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'title' => $this->resource->title,
            'notes' => $this->resource->notes,
            'page_type_id' => $this->resource->page_type_id,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'page_type' => PageTypeResource::make($this->whenLoaded('pageType')),
            'images' => WorksheetImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
