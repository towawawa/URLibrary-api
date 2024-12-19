<?php

namespace App\Http\Controllers\UrlLibraries;

use App\Http\Requests\UrlLibraries\EditNoteRequest;
use App\Http\Resources\UrlLibraryResource;
use App\Models\UrlLibrary;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class EditNoteController extends Controller
{
    public function __invoke(EditNoteRequest $request)
    {
        try {
            DB::beginTransaction();
            $urlLibrary = UrlLibrary::findOrFail($request->id);
            $urlLibrary->note = $request->note;
            $urlLibrary->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json(config('response.200'));
    }
}
