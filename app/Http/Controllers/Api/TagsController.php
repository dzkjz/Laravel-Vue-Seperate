<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function getTags()
    {
        $query = Request::get('search');

        if ($query == null || $query == '') {
            $tags = Tag::all();
        } else {
            $tags = Tag::where('name', 'LIKE', $query . '%')->get();
        }

        return response()->json($tags);


    }
}
