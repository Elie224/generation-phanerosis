<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teaching extends Model
{
    protected $fillable = [
        'title',
        'content',
        'pastor',
        'teaching_date',
        'media_path',
        'image_path',
    ];

    protected $dates = [
        'teaching_date',
        'created_at',
        'updated_at',
    ];
}
