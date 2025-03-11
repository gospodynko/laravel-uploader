<?php

namespace App\Jobs;

use App\Models\UploadedFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessUploadedFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $fileId;

    public function __construct(int $fileId)
    {
        $this->fileId = $fileId;
    }

    public function handle(): void
    {
        $file = UploadedFile::find($this->fileId);
        if (!$file) return;

        $newPath = str_replace('uploads/tmp', 'uploads/processed', $file->path);
        Storage::move($file->path, $newPath);

        $file->update(['path' => $newPath]);
    }
}
