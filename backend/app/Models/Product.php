<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name', 
        'description1', 
        'description2', 
        'link', 
        'photo', 
        'rating'
    ];

    // Many-to-Many relationship with Category
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // Many-to-Many relationship with Tag
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}