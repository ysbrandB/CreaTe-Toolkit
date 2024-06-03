<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string title
 * @property string description
 * @property HasMany item
 * @property BelongsToMany attributes
 */
class Processor extends Model
{
    use HasFactory;

    protected $fillable = ['title'];
    public function item(): BelongsTo
    {
        return $this->belongsTo(HasMany::class, 'item_id');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
    }
}
