<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin \App\Models\PageType */
class PageTypeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'name' => $this->resource->name,
            'columns' => $this->resource->columns,
            'rows' => $this->resource->rows,
            'sections' => $this->resource->sections_count,
            'logo_path' => $this->resource->logo_path,
            'logo_url' => $this->resource->logo_path
                ? Storage::disk('public')->url($this->resource->logo_path)
                : null,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
