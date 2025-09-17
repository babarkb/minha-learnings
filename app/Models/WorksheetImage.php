<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorksheetImage extends Model
{
    /** @use HasFactory<\Database\Factories\WorksheetImageFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'worksheet_id',
        'section_index',
        'image_path',
    ];

    public function worksheet(): BelongsTo
    {
        return $this->belongsTo(Worksheet::class);
    }
}
