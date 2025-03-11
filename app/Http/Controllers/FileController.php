<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use App\Jobs\ProcessUploadedFile;
use App\Jobs\DeleteFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function index()
    {
        return view('uploads.index');
    }

    public function list()
    {
        $files = UploadedFile::orderBy('created_at', 'desc')->get();
        return view('uploads.list', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:docx,pdf|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('uploads/tmp');
        $filename = $file->getClientOriginalName();
        $userId = auth()->id() ?? 0;

        $uploadedFile = UploadedFile::create([
            'filename' => $filename,
            'path' => $path,
            'user_id' => $userId,
//            'expires_at' => now()->addMinute()
            'expires_at' => now()->addDay()
        ]);

        ProcessUploadedFile::dispatch($uploadedFile->id)->onQueue('uploads');

        return response()->json(['success' => true, 'file_id' => $uploadedFile->id]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $file = UploadedFile::findOrFail($id);
        DeleteFile::dispatch($file->path, $id)->onQueue('uploads');

        return response()->json(['message' => 'File will be deleted']);
    }
}
