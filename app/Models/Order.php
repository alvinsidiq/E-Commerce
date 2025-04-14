<?php

namespace App\Models;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'grand_total',
        'payment_method',
        'payment_status',
        'status',
        'currency',
        'shipping_amount',
        'shipping_method',
        'notes',
    ];

    // satu order memiliki satu user
    public function user()
    {
        return $this->belongsTo(User::class);   
    }

    // satu order memiliki banyak order item
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }


    // satu order memiliki satu address
    public function address()
    {
        return $this->hasOne(Addres::class);
    }
}
