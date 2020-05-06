<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserRequest;
use App\User;
use Illuminate\Http\Request;


class UsersController extends Controller
{

    public function getUser()
    {
        return auth('api')->user();
    }

    public function putUpdateUser(EditUserRequest $request)
    {
        $user = auth()->user();

        $favoriteCoffee = $request->get('favorite_coffee');
        $flavorNotes = $request->get('flavor_notes');
        $profileVisibility = $request->get('profile_visibility');
        $city = $request->get('city');
        $state = $request->get('state');

        if ($favoriteCoffee) {
            $user->favorite_coffee = $favoriteCoffee;
        }

        if ($flavorNotes) {
            $user->flavor_notes = $flavorNotes;
        }

        //$profileVisibility ===0 也会判断为false
        if (!is_null($profileVisibility)) {
            $user->profile_visibility = $profileVisibility;
        }

        if ($city) {
            $user->city = $city;
        }

        if ($state) {
            $user->state = $state;
        }

        $user->save();

        return response()->json(['user_updated' => true], 201);
    }
}
