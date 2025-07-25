<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class GuestController extends Controller
{
    /**
     * ゲストアカウントを生成
     */
    public function createGuest(Request $request)
    {
        DB::beginTransaction();
        try {
            // ゲストセッションIDを生成
            $guestSessionId = Str::uuid()->toString();

            // ゲストユーザーを作成（30日間有効）
            $guestUser = User::create([
                'name' => 'ゲストユーザー',
                'email' => null,
                'password' => null,
                'is_guest' => true,
                'guest_expires_at' => now()->addDays(30),
                'guest_session_id' => $guestSessionId,
            ]);

            // デフォルトジャンルを作成
            $this->createDefaultGenres($guestUser->id);

            DB::commit();

            return response()->json(
                config('response.200') + [
                    'data' => new UserResource($guestUser),
                    'token' => $guestUser->createToken('guest-token')->plainTextToken,
                    'guest_session_id' => $guestSessionId,
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * ゲストアカウントを本登録に変換
     */
    public function convertToRegular(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        // ゲストユーザー以外は変換不可
        if (!$user->isGuest()) {
            throw ValidationException::withMessages([
                'user' => ['ゲストユーザーではありません。'],
            ]);
        }

        // 期限切れチェック
        if ($user->isGuestExpired()) {
            throw ValidationException::withMessages([
                'user' => ['ゲストアカウントの有効期限が切れています。'],
            ]);
        }

        DB::beginTransaction();
        try {
            // ゲストから本登録ユーザーに変換
            $user->convertFromGuest([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 新しいトークンを発行（古いトークンは無効化）
            $user->tokens()->delete();
            $token = $user->createToken('token')->plainTextToken;

            DB::commit();

            return response()->json(
                config('response.200') + [
                    'data' => new UserResource($user->fresh()),
                    'token' => $token,
                    'message' => 'アカウントの本登録が完了しました！',
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * ゲストアカウントの有効期限を確認
     */
    public function checkGuestStatus(Request $request)
    {
        $user = $request->user();

        if (!$user->isGuest()) {
            return response()->json([
                'is_guest' => false,
                'expires_at' => null,
                'days_remaining' => null,
            ]);
        }

        $expiresAt = $user->guest_expires_at;
        $daysRemaining = $expiresAt ? now()->diffInDays($expiresAt, false) : null;

        return response()->json([
            'is_guest' => true,
            'expires_at' => $expiresAt,
            'days_remaining' => max(0, $daysRemaining),
            'is_expired' => $user->isGuestExpired(),
        ]);
    }

    /**
     * 新規ユーザーにデフォルトジャンルを作成
     */
    private function createDefaultGenres(int $userId): void
    {
        $defaultGenres = [
            ['name' => 'JavaScript'],
            ['name' => 'PHP'],
            ['name' => 'お気に入り'],
        ];

        foreach ($defaultGenres as $genreData) {
            Genre::create([
                'user_id' => $userId,
                'name' => $genreData['name'],
            ]);
        }
    }
}
