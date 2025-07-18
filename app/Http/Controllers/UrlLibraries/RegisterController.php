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

            // 新しいハッシュタグ名を処理
            if ($request->hashTagNames) {
                foreach ($request->hashTagNames as $tagName) {
                    // 既存のタグをチェック
                    $existingTag = HashTag::where('user_id', Auth::id())
                        ->where('name', $tagName)
                        ->first();

                    if ($existingTag) {
                        // 既存のタグがあれば、IDsに追加
                        if (!in_array($existingTag->id, $hashTagIds)) {
                            $hashTagIds[] = $existingTag->id;
                        }
                    } else {
                        // 新しいタグを作成
                        $newTag = HashTag::create([
                            'user_id' => Auth::id(),
                            'name' => $tagName,
                        ]);
                        $hashTagIds[] = $newTag->id;
                    }
                }
            }

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
