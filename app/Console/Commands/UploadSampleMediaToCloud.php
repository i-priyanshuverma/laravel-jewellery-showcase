<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UploadSampleMediaToCloud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloud:sync-media {--disk=s3 : Target cloud storage disk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload all sample jewellery studio images to Cloud Object Storage bucket';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $targetDisk = $this->option('disk') ?: 's3';
        $sampleDir = storage_path('app/public/sample-products');

        if (! File::isDirectory($sampleDir)) {
            $this->error("Local sample directory not found: {$sampleDir}");

            return self::FAILURE;
        }

        $files = File::files($sampleDir);
        $total = count($files);

        if ($total === 0) {
            $this->warn("No sample images found in {$sampleDir}");

            return self::SUCCESS;
        }

        $this->info("Found {$total} sample jewellery photos. Uploading to [{$targetDisk}] storage bucket...");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $uploaded = 0;
        foreach ($files as $file) {
            $filename = $file->getFilename();
            $targetPath = "sample-products/{$filename}";
            $content = File::get($file->getPathname());

            Storage::disk($targetDisk)->put($targetPath, $content, 'public');
            $uploaded++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✓ Successfully synced {$uploaded} sample images to [{$targetDisk}] cloud bucket!");

        return self::SUCCESS;
    }
}
