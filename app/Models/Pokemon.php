<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $fillable = [
        'pokemon_id',
        'name',
        'height',
        'weight',
        'types',
        'abilities',
        'image_url',
    ];
}
