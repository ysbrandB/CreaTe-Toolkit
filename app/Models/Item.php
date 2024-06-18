<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\HigherOrderCollectionProxy;
use Vinkla\Hashids\Facades\Hashids;

/**
 * @property string name
 * @property string description
 * @property string card_description
 * @property string public_id
 * @property bool is_actuator
 * @property string photo
 * @property string pros
 * @property string cons
 * @property string hardware_considerations
 * @property string software_considerations
 * @property string example_code
 * @property string wiring_photo
 * @property string wiring_instructions
 * @property string photo_url
 * @property string wiring_photo_url
 * @property mixed json_items
 */
class Item extends Model
{
    use HasFactory;

    /**
     * @var HigherOrderCollectionProxy|mixed
     */
    protected $fillable = [
        'title',
        'description',
        'card_description',
        'pros',
        'cons',
        'hardware_considerations',
        'software_considerations',
        'wiring_instructions',
        'example_code',
        'photo',
        'is_actuator',
        'json_items',
    ];

    protected $with = ['attributes'];

    protected $appends = ['public_id', 'photo_url', 'wiring_photo_url'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'json_items' => 'json',
    ];

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function edges(): HasMany
    {
        return $this->hasMany(Edge::class, 'belongsto_item_id');
    }

    public function getPhotoUrlAttribute(): string
    {
        if (! $this->photo) {
            return '';
        }

        return asset('storage/photos/'.$this->photo);
    }

    public function getWiringPhotoUrlAttribute(): string
    {
        if (! $this->wiring_photo) {
            return '';
        }

        return asset('storage/photos/'.$this->wiring_photo);
    }

    public function getPublicIdAttribute(): string
    {
        return Hashids::encode($this->id);
    }
}
