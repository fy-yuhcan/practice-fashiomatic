<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'upper_image_id',
        'lower_image_id',
        'saved_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function upperImage()
    {
        return $this->belongsTo(Image::class, 'upper_image_id');
    }

    public function lowerImage()
    {
        return $this->belongsTo(Image::class, 'lower_image_id');
    }
}

