<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        // 現在のトークンを削除
        $request->user()->currentAccessToken()->delete();

        return response()->json(config('response.200'));
    }
}
