<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    /**
     * Generate a temporary URL for R2/S3 — works in Laravel 8
     */
    public static function temporaryUrl(string $path, int $minutes = 30): string
    {
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();

        $command = $client->getCommand('GetObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key'    => $path,
        ]);

        $request = $client->createPresignedRequest($command, '+' . $minutes . ' minutes');

        return (string) $request->getUri();
    }
}