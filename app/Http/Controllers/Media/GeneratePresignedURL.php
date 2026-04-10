<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreGeneratePresignedURLRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GeneratePresignedURL extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreGeneratePresignedURLRequest $request)
    {
        $data = $request->validated();

        $urls = [];

        foreach ($data['images'] as $image) {
            $extension = $image['extension'];
            $uuid = (string) Str::uuid();
            $path = "temp/{$uuid}.{$extension}";
            $client = Storage::disk('s3')?->getClient();
            $expiry = '+20 minutes';

            $command = $client->getCommand('PutObject', [
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key' => $path,
                'ContentType' => $request->input('type'),
            ]);

            $url = $client->createPresignedRequest($command, $expiry)->getUri();

            $urls[] = [
                'id' => $image['id'],
                'path' => $path,
                'url' => (string) $url
            ];
        }

        return response()->json($urls);
    }
}
