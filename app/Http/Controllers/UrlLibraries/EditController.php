<?php

namespace App\Http\Controllers\UrlLibraries;

use App\Http\Requests\UrlLibraries\EditRequest;
use App\Models\UrlLibrary;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class EditController extends Controller
{
    public function __invoke(EditRequest $request, int $id)
    {
        DB::beginTransaction();
        try {
            $url_library = UrlLibrary::findOrFail($id);
            $url_library->update([
                'title' => $request->title,
                'url' => $request->url,
                'genre_id' => $request->genreId,
                'note' => $request->note,
            ]);
            $url_library->hashTags()->detach();
            $url_library->hashTags()->attach($request->hashTagIds);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return response()->json(config('response.200'));
    }
}
