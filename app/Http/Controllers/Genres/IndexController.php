<?php

namespace App\Http\Controllers\Genres;

use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $genres = Genre::get();

        return response()->json(
            config('response.200') + [
                'data' => GenreResource::collection($genres),
            ]
        );
    }
}
