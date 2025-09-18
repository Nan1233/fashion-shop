<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'price', 'description', 
        'image', 'stock', 'sold', 'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function latestReviews()
    {
        return $this->hasMany(Review::class)->latest('updated_at');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items', 'product_id', 'order_id');
    }

    public function decreaseStock(int $quantity)
    {
        $this->stock -= $quantity;
        $this->sold += $quantity;
        $this->status = $this->stock > 0;
        $this->save();
    }

    public function increaseStock(int $quantity)
    {
        $this->stock += $quantity;
        $this->sold -= $quantity;
        if($this->sold < 0) $this->sold = 0;
        $this->status = $this->stock > 0;
        $this->save();
    }
}
