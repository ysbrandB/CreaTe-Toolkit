<?php

namespace App\Models;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string name
 * @property string description
 * @property string public_id
 * @property boolean is_actuator
 * @property string photo
 *
 */

class Item extends Model
{
    use HasFactory;

    /**
     * @var \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    protected $fillable = [
        'name',
        'description',
        'photo',
        'is_actuator'
    ];

    public function getPublicIdAttribute(): string
    {
        return Hashids::encode($this->id);
    }


}
