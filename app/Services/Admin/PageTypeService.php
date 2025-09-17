<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\PageType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PageTypeService
{
    public function __construct(
        private readonly WorksheetService $worksheetService,
    ) {
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return PageType::query()
            ->latest()
            ->paginate($perPage);
    }

    public function count(): int
    {
        return PageType::query()->count();
    }

    public function find(int $id): PageType
    {
        return PageType::query()->findOrFail($id);
    }

    /**
     * @return Collection<int, PageType>
     */
    public function all(): Collection
    {
        return PageType::query()
            ->orderBy('name')
            ->get();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function store(array $attributes): PageType
    {
        $logo = $attributes['logo'] ?? null;
        unset($attributes['logo']);

        return DB::transaction(function () use ($attributes, $logo): PageType {
            if ($logo instanceof UploadedFile) {
                $attributes['logo_path'] = $this->storeLogo($logo);
            }

            /** @var PageType $pageType */
            $pageType = PageType::query()->create($attributes);

            return $pageType;
        });
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(PageType $pageType, array $attributes): PageType
    {
        $logo = $attributes['logo'] ?? null;
        unset($attributes['logo']);

        return DB::transaction(function () use ($pageType, $attributes, $logo): PageType {
            if ($logo instanceof UploadedFile) {
                $this->deleteLogo($pageType->logo_path);
                $attributes['logo_path'] = $this->storeLogo($logo);
            }

            $pageType->fill($attributes);
            $pageType->save();

            return $pageType->refresh();
        });
    }

    public function delete(PageType $pageType): void
    {
        DB::transaction(function () use ($pageType): void {
            $pageType->loadMissing('worksheets.images');

            foreach ($pageType->worksheets as $worksheet) {
                $this->worksheetService->delete($worksheet);
            }

            $this->deleteLogo($pageType->logo_path);

            $pageType->delete();
        });
    }

    private function storeLogo(UploadedFile $logo): string
    {
        return $logo->store('logos', 'public');
    }

    private function deleteLogo(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
