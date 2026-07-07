<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Hanya izinkan kolom name dan slug yang masuk ke database
    protected $fillable = [
        'name',
        'slug',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}