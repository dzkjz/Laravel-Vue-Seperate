<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CafePhoto extends Model
{
    protected $table = 'cafes_photos';//数据表是cafes_photos

    protected $fillable = [
        'cafe_id',
        'uploaded_by',
        'file_url',
    ];

    public function cafe()
    {
        return $this->belongsTo(Cafe::class, 'cafe_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id');
    }

}
