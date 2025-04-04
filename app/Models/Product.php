<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'images',
        'description',
        'price',
        'is_active',
        'is_featured',
        'in_stock',
        'on_sale',
    ];
    //merubah image menjadi array 
    protected $casts = [
        'images' => 'array',
    ];

    // satu category memiliki banyak product

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    // satu brand memiliki banyak product
    public function Brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // satu product memiliki banyak order
    public function OrderItems()
    {
        return $this->hasMany(OrderItem :: class);
    }
}
