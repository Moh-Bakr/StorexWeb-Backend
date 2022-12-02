<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'rate',
        'image',
        'image_public_id',
        'category_id',
    ];
    
    protected $hidden = [
        // 'image_public_id',
    ];
}