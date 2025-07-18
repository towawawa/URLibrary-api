<?php

namespace App\Http\Controllers\Genres;

use App\Http\Requests\Genres\CreateRequest;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateController extends Controller
{
    public function __invoke(CreateRequest $request)
    {
        $imagePath = null;

        // 画像がアップロードされた場合
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('genres', $filename, 'images');
            $imagePath = Storage::disk('images')->url($imagePath);
        }

        $genre = Genre::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'imagePath' => $imagePath,
        ]);

        return response()->json(
            config('response.200') + [
                'data' => new GenreResource($genre),
            ]
        );
    }
}
