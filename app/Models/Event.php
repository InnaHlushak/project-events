<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name','category_id','deadline','venue','description',];

    /**
     * Specify the one-to-one relationship: one event is associated with one category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}