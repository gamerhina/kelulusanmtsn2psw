<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'primary_color',
        'secondary_color',
        'bg_gradient_start',
        'bg_gradient_end',
        'logo_image',
        'slider_images',
        'slider_interval',
        'slider_animation',
        'foreground_animation',
        'app_name',
    ];

    protected $casts = [
        'slider_images' => 'array',
    ];
}
