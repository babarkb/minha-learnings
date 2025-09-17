<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageType extends Model
{
    /** @use HasFactory<\Database\Factories\PageTypeFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'columns',
        'rows',
        'logo_path',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'columns' => 'integer',
            'rows' => 'integer',
        ];
    }

    public function worksheets(): HasMany
    {
        return $this->hasMany(Worksheet::class);
    }

    public function getSectionsCountAttribute(): int
    {
        return $this->columns * $this->rows;
    }
}
