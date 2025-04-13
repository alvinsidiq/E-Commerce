<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'slug',
        'image',
        'is_active'
    ];

    // satu barand memiliki banyak product 

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected static function booted(): void
{
    static::creating(function ($brand) {
        if (empty($brand->slug)) {
            $brand->slug = Str::slug($brand->name);
        }
    });
}

}
