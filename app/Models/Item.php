<?php

namespace App\Models;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this::findOrFail(Hashids::decode($value));
    }

    public function getPublicIdAttribute(): string
    {
        return Hashids::encode($this->id);
    }


}
