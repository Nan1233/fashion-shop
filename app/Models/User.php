<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function reviews() {
    return $this->hasMany(Review::class);
}
public function wishlistItems()
{
    return $this->hasMany(Wishlist::class);
}
public function cartItems()
{
    return $this->hasMany(Cart::class); // hoặc CartItem::class tùy tên model
}
public function orders()
{
    return $this->hasMany(Order::class); // Order là model tương ứng với bảng orders
}

}
