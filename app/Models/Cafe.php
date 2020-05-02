<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
    protected $fillable =
        [
            'name',
            'address',
            'city',
            'state',
            'zip',
            'latitude',
            'longitude',
        ];

    public function brewMethods()
    {
        return $this->belongsToMany(BrewMethod::class, 'cafes_brew_methods', 'cafe_id', 'brew_method_id');
    }
}
