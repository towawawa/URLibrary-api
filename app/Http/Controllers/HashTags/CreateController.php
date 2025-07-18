<?php

namespace App\Http\Controllers\HashTags;

use App\Http\Resources\HashTagResource;
use App\Models\HashTag;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CreateController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'バリデーションエラー',
                'errors' => $validator->errors(),
            ], 422);
        }

        // 同じ名前のハッシュタグが既に存在するかチェック
        $existing = HashTag::where('user_id', Auth::id())
            ->where('name', $request->name)
            ->first();

        if ($existing) {
            return response()->json(
                config('response.200') + [
                    'data' => new HashTagResource($existing),
                ]
            );
        }

        // 新しいハッシュタグを作成
        $hashTag = HashTag::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
        ]);

        return response()->json(
            config('response.201') + [
                'data' => new HashTagResource($hashTag),
            ]
        );
    }
}
