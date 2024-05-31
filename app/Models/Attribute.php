<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property string name
 * @property string description
 */
class Attribute extends Model
{
    use HasFactory;
    public function items()
    {
        return $this->belongsToMany(Item::class, 'attribute_items');
    }
}
