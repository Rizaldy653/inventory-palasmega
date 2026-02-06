<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'name',
        'code',
        'type',
        'brand',
        'load',
        'unit',
        'category_id',
        'color',
        'status',
        'added_at',
        'is_active',
        'note',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}
