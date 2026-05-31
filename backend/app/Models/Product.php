<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id', 'category_id', 'name', 'description', 'price', 
        'stock', 'unit', 'image', 'gallery_images', 'is_active', 'is_featured', 'buy_links'
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'buy_links' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
