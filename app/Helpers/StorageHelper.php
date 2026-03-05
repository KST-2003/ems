<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    public static function temporaryUrl(string $path, int $minutes = 30): string
    {
        // ✅ If local disk, just return normal public URL
        if (config('filesystems.default') === 'local' || config('filesystems.default') === 'public') {
            return asset('storage/' . $path);
        }

        // ✅ Only use S3 presigned URL when disk is actually s3
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();

        $command = $client->getCommand('GetObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key'    => $path,
        ]);

        $request = $client->createPresignedRequest($command, '+' . $minutes . ' minutes');

        return (string) $request->getUri();
    }
}