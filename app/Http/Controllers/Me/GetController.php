<?php

namespace App\Http\Controllers\Me;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class GetController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json(
            config('response.200') + [
                'data' => new UserResource(Auth::user()),
            ]
        );
    }
}
