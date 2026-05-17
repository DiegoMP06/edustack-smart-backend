<?php

namespace App\Modules\Media\Actions;

use App\Modules\Media\DTOs\GeneratePresignedURLFormData;
use App\Modules\Media\DTOs\PresignedURLData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GeneratePresignedURLAction
{
    /**
     * Execute the action.
     */
    public function execute(GeneratePresignedURLFormData $data): Collection
    {
        $urls = collect();

        foreach ($data->images as $image) {
            $extension = $image->extension;
            $uuid = (string) Str::uuid();
            $path = "temp/{$uuid}.{$extension}";

            $client = Storage::disk('s3')?->getClient();
            $expiry = '+20 minutes';

            $command = $client->getCommand('PutObject', [
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key' => $path,
                'ContentType' => $image->type,
            ]);

            $url = $client->createPresignedRequest($command, $expiry)->getUri();

            $urls->push(PresignedURLData::from([
                'id' => $image->id,
                'path' => $path,
                'url' => (string) $url,
            ]));
        }

        return $urls;
    }
}
