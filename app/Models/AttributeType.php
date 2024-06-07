<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string title
 * @property string description
 * @property string color
 */
class AttributeType extends Model
{
    use HasFactory;

    /**
     * @var array|mixed|string[]
     */
    public static mixed $colors = ['red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald', 'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose', 'neutral'];

    protected $fillable = ['title', 'description', 'color'];

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }
}
