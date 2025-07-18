<?php

namespace App\Http\Controllers\Genres;

use App\Http\Requests\Genres\CreateRequest;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
{
    public function __invoke(CreateRequest $request)
    {
        $genre = Genre::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
        ]);

        return response()->json(
            config('response.200') + [
                'data' => new GenreResource($genre),
            ]
        );
    }
}
