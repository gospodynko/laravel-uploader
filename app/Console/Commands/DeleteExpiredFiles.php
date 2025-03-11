<?php

namespace App\Console\Commands;

use App\Models\UploadedFile;
use App\Jobs\DeleteFile;
use Illuminate\Console\Command;

class DeleteExpiredFiles extends Command
{
    protected $signature = 'files:cleanup';
    protected $description = 'Delete expired files';

    /**
     * @return void
     */
    public function handle():void
    {
        $expiredFiles = UploadedFile::where('expires_at', '<', now())->get();

        foreach ($expiredFiles as $file) {
            DeleteFile::dispatch($file->path, $file->id)->onQueue('uploads');
        }

        $this->info(count($expiredFiles) . ' files have been sent for deletion.');
    }
}
