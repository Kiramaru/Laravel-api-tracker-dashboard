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

    protected $casts = [//Указываем, что поле 'types' должно быть преобразовано в массив при сохранении и извлечении из базы данных

        'types' => 'array',
    ];
}
