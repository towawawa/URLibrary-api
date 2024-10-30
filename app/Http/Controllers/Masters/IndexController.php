<?php

namespace App\Http\Controllers\Masters;

use App\Models\Genre;
use App\Models\HashTag;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __invoke()
    {
        $authId = Auth::id();
        $hashTags = HashTag::select(['id', 'name', 'user_id'])
            ->where('user_id', $authId)
            ->get()
            ->toArray();

        $genres = Genre::select(['id', 'name', 'user_id'])
            ->where('user_id', $authId)
            ->get()
            ->toArray();

        return response()->json(
            config('response.200') + [
                'data' => [
                    'hashTags' => $hashTags,
                    'genres' => $genres,
                ],
            ]
        );
    }
}
