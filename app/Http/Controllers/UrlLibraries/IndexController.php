<?php

namespace App\Http\Controllers\UrlLibraries;

use App\Http\Resources\UrlLibraryResource;
use App\Models\UrlLibrary;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $urlLibraries = UrlLibrary::with(['hashTags', 'genre'])
            ->when(isset($request->hashTagId), function ($query) use ($request) {
                $query->whereRelation('hashTags', 'id', $request->hashTagId);
            })
            ->when(isset($request->startCreatedAt), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->startCreatedAt);
            })
            ->when(isset($request->endCreatedAt), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->endCreatedAt);
            })
            ->when(isset($request->keyword), function ($query) use ($request) {
                $query->where('title', 'like', "%{$request->keyword}%");
            })
            ->get();

        return response()->json(
            config('response.200') + [
                'data' => UrlLibraryResource::collection($urlLibraries),
            ]
        );
    }
}
