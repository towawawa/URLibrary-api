<?php

namespace App\Http\Controllers\UrlLibraries;

use App\Http\Resources\UrlLibraryResource;
use App\Models\UrlLibrary;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GetController extends Controller
{
    public function __invoke(int $id)
    {
        $urlLibrary = UrlLibrary::with(['hashTags', 'genre'])
            ->findOrFail($id);

        return response()->json(
            config('response.200') + [
                'data' => new UrlLibraryResource($urlLibrary),
            ]
        );
    }
}
