<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'image',
        'rating',
        'features'
    ];

    // Relasi balik: Satu barang dimiliki oleh satu Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
