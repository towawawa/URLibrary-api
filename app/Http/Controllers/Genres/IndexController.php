<?php

namespace App\Http\Controllers\Genres;

use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __invoke()
    {
        $genres = Genre::where('user_id', Auth::id())->get();

        return response()->json(
            config('response.200') + [
                'data' => GenreResource::collection($genres),
            ]
        );
    }
}
