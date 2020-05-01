<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthenticationController extends Controller
{
    public function getSocialRedirect($account)
    {
        try {
            return Socialite::driver($account)->redirect();
        } catch (\InvalidArgumentException $exception) {
            return redirect('/login');
        }
    }

    public function getSocialCallback($account)
    {
        //从第三方OAuth回调中获取用户信息
        $socialUser = Socialite::driver($account)->user();
        //在本地users表中查询该用户来判断是否已存在
        $user = User::where('provider_id', '=', $socialUser->id)
            ->where('provider', '=', $account)
            ->first();

        if ($user == null) {
            //如果该用户不存在则将其保存到users 表
            $newUser = new User();
            $newUser->name = $socialUser->getName();
            $newUser->email = $socialUser->getEmail() == '' ? '' : $socialUser->getEmail();
            $newUser->avatar = $socialUser->getAvatar();
            $newUser->password = '';
            $newUser->provider = $account;
            $newUser->provider_id = $socialUser->getId();

            $newUser->save();
            $user = $newUser;
        }

        //手动登录该用户
        Auth::login($user);

        //登录成过后将用户重定向到首页
        return redirect('/#/home');
    }
}
