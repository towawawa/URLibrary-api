<?php

namespace App\Http\Controllers\UrlLibraries;

use App\Http\Requests\UrlLibraries\RegisterRequest;
use App\Http\Resources\UrlLibraryResource;
use App\Models\UrlLibrary;
use App\Models\HashTag;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $url_library = UrlLibrary::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'url' => $request->url,
                'genre_id' => $request->genreId,
                'note' => $request->note,
            ]);

            // ハッシュタグIDsを処理
            $hashTagIds = $request->hashTagIds ?? [];

            // ハッシュタグを関連付け
            if (!empty($hashTagIds)) {
                $url_library->hashTags()->attach(array_unique($hashTagIds));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return response()->json(config('response.200'));
    }
}
