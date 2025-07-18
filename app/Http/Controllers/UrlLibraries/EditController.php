<?php

namespace App\Http\Controllers\UrlLibraries;

use App\Http\Requests\UrlLibraries\EditRequest;
use App\Models\UrlLibrary;
use App\Models\HashTag;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EditController extends Controller
{
    public function __invoke(EditRequest $request, int $id)
    {
        DB::beginTransaction();
        try {
            $url_library = UrlLibrary::findOrFail($id);
            $url_library->update([
                'title' => $request->title,
                'url' => $request->url,
                'genre_id' => $request->genreId,
                'note' => $request->note,
            ]);

            // 既存のハッシュタグを削除
            $url_library->hashTags()->detach();

            // ハッシュタグIDsを処理
            $hashTagIds = $request->hashTagIds ?? [];

            // ハッシュタグを関連付け
            if (!empty($hashTagIds)) {
                $url_library->hashTags()->attach(array_unique($hashTagIds));
            }

            // 使われなくなったハッシュタグを削除
            $this->cleanupUnusedHashTags();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return response()->json(config('response.200'));
    }

    /**
     * 使われなくなったハッシュタグを削除
     */
    private function cleanupUnusedHashTags(): void
    {
        // 現在のユーザーのハッシュタグのうち、どのURLライブラリーとも関連付けられていないものを削除
        $unusedTags = HashTag::where('user_id', Auth::id())
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('has_tag_url_libraries')
                    ->whereColumn('has_tag_url_libraries.hash_tag_id', 'hash_tags.id');
            })
            ->get();

        foreach ($unusedTags as $tag) {
            $tag->delete();
        }
    }
}
