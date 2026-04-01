<?php

use App\Http\Resources\Classroom\CourseCollection;
use App\Http\Resources\Classroom\CourseResource;
use App\Http\Resources\Events\EventActivityCollection;
use App\Http\Resources\Events\EventActivityResource;
use App\Http\Resources\Events\EventCollection;
use App\Http\Resources\Events\EventResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

if (! defined('IMAGE_SIZES')) {
    define('IMAGE_SIZES', []);
}

it('formats event media in event resource', function () {
    $resource = new EventResource(new class
    {
        public int $id = 1;

        public function toArray(): array
        {
            return ['id' => $this->id];
        }

        public function getMedia(string $collection)
        {
            return collect([$this->fakeMedia()]);
        }

        private function fakeMedia(): object
        {
            return new class
            {
                public int $id = 100;

                public array $custom_properties = [];

                public function hasGeneratedConversion(string $conversion): bool
                {
                    return $conversion === 'thumbnail';
                }

                public function getResponsiveImageUrls(string $conversion): array
                {
                    return [];
                }

                public function getUrl(string $conversion = ''): string
                {
                    return $conversion !== ''
                        ? "https://cdn.example.com/{$conversion}.jpg"
                        : 'https://cdn.example.com/original.jpg';
                }
            };
        }
    });

    $data = $resource->resolve(Request::create('/'));

    $media = $data['media'][0];

    if ($media instanceof JsonResource) {
        $media = $media->resolve(Request::create('/'));
    }

    expect($data)->toHaveKey('media')
        ->and($data['media'])->toHaveCount(1)
        ->and($media['urls'])->toHaveKey('thumbnail');
});

it('formats classroom media in course resource', function () {
    $resource = new CourseResource(new class
    {
        public int $id = 1;

        public function toArray(): array
        {
            return ['id' => $this->id];
        }

        public function getMedia(string $collection)
        {
            return collect([$this->fakeMedia()]);
        }

        private function fakeMedia(): object
        {
            return new class
            {
                public int $id = 200;

                public array $custom_properties = [];

                public function hasGeneratedConversion(string $conversion): bool
                {
                    return false;
                }

                public function getResponsiveImageUrls(string $conversion): array
                {
                    return [];
                }

                public function getUrl(string $conversion = ''): string
                {
                    return $conversion !== ''
                        ? "https://cdn.example.com/{$conversion}.jpg"
                        : 'https://cdn.example.com/original.jpg';
                }
            };
        }
    });

    $data = $resource->resolve(Request::create('/'));

    $media = $data['media'][0];

    if ($media instanceof JsonResource) {
        $media = $media->resolve(Request::create('/'));
    }

    expect($data)->toHaveKey('media')
        ->and($data['media'])->toHaveCount(1)
        ->and($media['urls'])->toHaveKey('original');
});

it('uses explicit resources in event and classroom collections', function () {
    $eventCollection = new EventCollection([new stdClass]);
    $activityCollection = new EventActivityCollection([new stdClass]);
    $courseCollection = new CourseCollection([new stdClass]);

    expect($eventCollection->first())->toBeInstanceOf(EventResource::class)
        ->and($activityCollection->first())->toBeInstanceOf(EventActivityResource::class)
        ->and($courseCollection->first())->toBeInstanceOf(CourseResource::class);
});
