<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worksheet extends Model
{
    /** @use HasFactory<\Database\Factories\WorksheetFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'notes',
        'page_type_id',
    ];

    public function pageType(): BelongsTo
    {
        return $this->belongsTo(PageType::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(WorksheetImage::class)->orderBy('section_index');
    }
}
