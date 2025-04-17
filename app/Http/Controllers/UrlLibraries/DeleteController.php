<?php

namespace App\Http\Controllers\UrlLibraries;

use App\Models\UrlLibrary;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DeleteController extends Controller
{
    public function __invoke(int $id)
    {
        DB::beginTransaction();
        try {
            $url_library = UrlLibrary::findOrFail($id);
            $url_library->hashTags()->detach();
            $url_library->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return response()->json(config('response.200'));
    }
}
