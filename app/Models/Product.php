<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'image',
        'price',
        'stock',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'image_url'
    ];

    public function getImageUrlAttribute(){
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
