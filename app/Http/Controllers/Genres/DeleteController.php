<?php

namespace App\Http\Controllers\Genres;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DeleteController extends Controller
{
    public function __invoke(Request $request, $id)
    {
        // ユーザーが所有するジャンルのみ削除可能
        $genre = Genre::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$genre) {
            return response()->json([
                'status' => 404,
                'message' => 'ジャンルが見つかりません',
            ], 404);
        }

        // 関連するURLライブラリが存在するかチェック
        $relatedUrlLibraries = $genre->urlLibraries()->count();

        if ($relatedUrlLibraries > 0) {
            return response()->json([
                'status' => 400,
                'message' => 'このジャンルを使用しているURLが存在するため削除できません',
            ], 400);
        }

        $genre->delete();

        return response()->json(config('response.200'));
    }
}
