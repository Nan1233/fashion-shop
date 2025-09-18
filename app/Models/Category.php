<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    // Quan hệ 1 category có nhiều products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

