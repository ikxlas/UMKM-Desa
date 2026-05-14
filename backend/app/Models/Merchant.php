<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'owner_name', 'phone', 'address', 'description', 'logo', 'social_media'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
