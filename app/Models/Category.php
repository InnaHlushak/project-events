<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Specify a one-to-many relationship: one category is associated with many events
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
