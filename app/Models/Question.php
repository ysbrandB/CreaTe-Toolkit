<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    #[\Override]
    protected $fillable = ['title', 'description'];

    #[\Override]
    protected $with = ['answers'];

    #[\Override]
    protected $hidden = ['created_at', 'updated_at'];

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
