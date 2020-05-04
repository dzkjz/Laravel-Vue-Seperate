<?php

namespace App\Models;

use App\User;
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
            'roaster',
            'website',
            'description',
            'added_by',
            'location_name',
        ];

    public function brewMethods()
    {
        return $this->belongsToMany(BrewMethod::class, 'cafes_brew_methods', 'cafe_id', 'brew_method_id');
    }

    // 关联分店
    public function children()
    {
        return $this->hasMany(Cafe::class, 'parent', 'id');
    }

    // 归属总店
    public function parent()
    {
        return $this->hasOne(Cafe::class, 'id', 'parent');
    }

    // 与 User 间的多对对关联
    public function likesBy()
    {
        return $this->belongsToMany(
            User::class,
            'users_cafes_likes',
            'cafe_id',
            'user_id')
            ->withTimestamps();
    }

    /**
     * 该关联方法用于标识登录用户是否已经喜欢/取消喜欢指定咖啡店，以便我们可以正确初始化状态。
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function authUserLike()
    {
        return $this->belongsToMany(User::class,
            'users_cafes_likes',
            'cafe_id',
            'user_id')
            ->where('user_id', auth()->id());
    }


    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'cafes_users_tags',
            'cafe_id',
            'tag_id');
    }
}
