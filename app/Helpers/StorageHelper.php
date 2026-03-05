<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    public static function temporaryUrl(string $path, int $minutes = 30): string
    {
        $disk = config('filesystems.default');

        if ($disk === 's3') {
            $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
            $command = $client->getCommand('GetObject', [
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key'    => $path,
            ]);
            $request = $client->createPresignedRequest($command, '+' . $minutes . ' minutes');
            return (string) $request->getUri();
        }

        // For local/public disk — use Storage::url() which handles path correctly
        return Storage::disk('public')->url($path);
    }
}
