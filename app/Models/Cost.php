<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    use HasFactory;

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    protected function fullCost(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['name'] . ' - Вартість ' .  $attributes['price'] . ' грн',
        );
    }
}
