<?php

namespace App\Http\Controllers\UrlLibraries;

use App\Models\UrlLibrary;
use App\Models\HashTag;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteController extends Controller
{
    public function __invoke(int $id)
    {
        DB::beginTransaction();
        try {
            $url_library = UrlLibrary::findOrFail($id);
            $url_library->hashTags()->detach();
            $url_library->delete();

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
