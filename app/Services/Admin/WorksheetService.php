<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Worksheet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WorksheetService
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Worksheet::query()
            ->with('pageType')
            ->latest()
            ->paginate($perPage);
    }

    public function count(): int
    {
        return Worksheet::query()->count();
    }

    public function find(int $id): Worksheet
    {
        return Worksheet::query()
            ->with(['pageType', 'images'])
            ->findOrFail($id);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function store(array $attributes): Worksheet
    {
        /** @var array<int, UploadedFile> $images */
        $images = $attributes['images'] ?? [];
        unset($attributes['images']);

        return DB::transaction(function () use ($attributes, $images): Worksheet {
            /** @var Worksheet $worksheet */
            $worksheet = Worksheet::query()->create($attributes);

            $this->syncImages($worksheet, $images);

            return $worksheet->load(['pageType', 'images']);
        });
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Worksheet $worksheet, array $attributes): Worksheet
    {
        /** @var array<int, UploadedFile>|null $images */
        $images = $attributes['images'] ?? null;
        unset($attributes['images']);

        return DB::transaction(function () use ($worksheet, $attributes, $images): Worksheet {
            $worksheet->fill($attributes);
            $worksheet->save();

            if ($images !== null) {
                $this->syncImages($worksheet, $images, true);
            }

            return $worksheet->load(['pageType', 'images']);
        });
    }

    public function delete(Worksheet $worksheet): void
    {
        DB::transaction(function () use ($worksheet): void {
            $worksheet->loadMissing('images');

            foreach ($worksheet->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $worksheet->delete();
        });
    }

    /**
     * @param  array<int, UploadedFile>  $images
     */
    private function syncImages(Worksheet $worksheet, array $images, bool $replace = false): void
    {
        if ($replace) {
            $worksheet->loadMissing('images');

            foreach ($worksheet->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $worksheet->images()->delete();
        }

        foreach (array_values($images) as $index => $image) {
            $worksheet->images()->create([
                'section_index' => $index,
                'image_path' => $image->store('worksheet-images', 'public'),
            ]);
        }
    }
}
