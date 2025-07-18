<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        $user = Auth::user();

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // パスワードが入力されている場合のみ更新
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json(
            config('response.200') + [
                'data' => new UserResource($user),
            ]
        );
    }
}
