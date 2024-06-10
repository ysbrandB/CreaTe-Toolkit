<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Edge extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_item_id',
        'to_item_id',
        'belongsto_item_id'
    ];

    public function fromItem(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'from_item_id');
    }

    public function toItem(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'to_item_id');
    }

    public function belongsToItem(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'belongsto_item_id');
    }
}
