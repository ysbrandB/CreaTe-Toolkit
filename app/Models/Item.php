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
 * @property string pros
 * @property string cons
 * @property string hardware_considerations
 * @property string software_considerations
 * @property string example_code
 * @property string wiring_photo
 * @property string wiring_instructions
 *
 */

class Item extends Model
{
    use HasFactory;

    /**
     * @var \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    protected $fillable = [
        'title',
        'description',
        'pros',
        'cons',
        'hardware_considerations',
        'software_considerations',
        'example_code',
        'photo',
        'is_actuator',
    ];

    protected $with = ['attributes'];
    protected $appends = ['public_id', 'photo_url', 'wiring_photo_url'];
    public function getPhotoUrlAttribute(): string
    {
        return asset('storage/photos/' . $this->photo);
    }

    public function getWiringPhotoUrlAttribute(): string
    {
        return asset('storage/photos/' . $this->wiring_photo);
    }

    public function getPublicIdAttribute(): string
    {
        return Hashids::encode($this->id);
    }


}
