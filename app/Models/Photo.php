<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'path'];

    protected $appends = ['url'];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/photos/'.$this->path);
    }

    protected static function boot(): void
    {
        parent::boot();
        static::deleting(static function (Photo $photo): void {
            Storage::disk('public')->delete('photos/'.$photo->path);
        });
    }
}
