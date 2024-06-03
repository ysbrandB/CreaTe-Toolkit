<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string title
 * @property string description
 */
class AttributeType extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }
}
