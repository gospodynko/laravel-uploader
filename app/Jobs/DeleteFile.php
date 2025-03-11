<?php
namespace App\Jobs;

use App\Models\UploadedFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DeleteFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $path;
    protected int $fileId;

    public function __construct(string $path, int $fileId)
    {
        $this->path = $path;
        $this->fileId = $fileId;
    }

    public function handle(): void
    {
        try {
            if (Storage::exists($this->path)) {
                Storage::delete($this->path);
                Log::info("File {$this->path} deleted.");
            } else {
                Log::warning("File {$this->path} not found.");
            }

            UploadedFile::destroy($this->fileId);
            Log::info("Record with ID {$this->fileId} deleted successfully.");

            SendEmailNotificationJob::dispatch($this->path)->onQueue('emails');


        } catch (\Exception $e) {
            Log::error("Error on delete {$this->path}: " . $e->getMessage());
        }
    }
}
