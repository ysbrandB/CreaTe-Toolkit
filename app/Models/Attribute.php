<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string title
 * @property string description
 */
class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'attribute_type_id'];
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class);
    }

    public function attributeType(): BelongsTo
    {
        return $this->belongsTo(AttributeType::class);
    }
}
