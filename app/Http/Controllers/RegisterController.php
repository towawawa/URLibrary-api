<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Genre;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // デフォルトジャンルを作成
            $this->createDefaultGenres($user->id);

            DB::commit();

            return response()->json(
                config('response.200') + [
                    'data' => new UserResource($user),
                    'token' => $user->createToken('token')->plainTextToken,
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 新規ユーザーにデフォルトジャンルを作成
     */
    private function createDefaultGenres(int $userId): void
    {
        $defaultGenres = [
            [
                'name' => 'JavaScript',
            ],
            [
                'name' => 'PHP',
            ],
        ];

        foreach ($defaultGenres as $genreData) {
            Genre::create([
                'user_id' => $userId,
                'name' => $genreData['name'],
                'image_path' => null, // デフォルトアイコンはフロントエンドで表示
            ]);
        }
    }
}
