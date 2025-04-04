<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'slug',
        'image',
        'is_active'
    ];

    // membuat relasi one to many : satu kategori memiliki banyak Produk 

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // membuat slug otomatis 

    public static function boot()
    {
        parent::boot();
        static ::  creating(function($category){
            $category->slug = Str::slug($category->name);
        });
    }
}
