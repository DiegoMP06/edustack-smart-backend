<?php

namespace App\Modules\Media\DTOs;

use Spatie\LaravelData\Data;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class MediaData extends Data
{
    public function __construct(
        public int $id,
        #[LiteralTypeScriptType('Record<string, string|null>')]
        public array $urls,
        #[LiteralTypeScriptType('Record<string, ImageDimensionsData>')]
        public array $dimensions,
        public ResponsiveImagesData $responsive,
        public bool $is_processed,
        #[LiteralTypeScriptType('Record<string, unknown>')]
        public array $custom_properties,
    ) {}

    public static function fromModel(
        Media $media,
        string $mainConversion = 'main',
        array $dimensions = [],
        array $extraConversions = [],
    ): self {
        $isProcessed = $media->hasGeneratedConversion($mainConversion);
        $responsiveUrls = $media->getResponsiveImageUrls($mainConversion);

        $imageSizes = ['xl', 'lg', 'base', 'md', 'sm', 'xs'];
        $responsiveMapped = [];
        foreach ($imageSizes as $index => $label) {
            $responsiveMapped[$label] = $responsiveUrls[$index] ?? null;
        }

        $urls = [
            $mainConversion => $isProcessed ? $media->getUrl($mainConversion) : null,
            'original' => $media->getUrl(),
        ];
        foreach ($extraConversions as $conversion) {
            $urls[$conversion] = $media->getUrl($conversion);
        }

        $dimensionDtos = collect($dimensions)->mapWithKeys(fn ($dimension, $key) => [
            $key => new ImageDimensionsData($dimension['width'], $dimension['height']),
        ])->toArray();

        return new self(
            id: $media->id,
            urls: $urls,
            dimensions: $dimensionDtos,
            responsive: ResponsiveImagesData::from($responsiveMapped),
            is_processed: $isProcessed,
            custom_properties: $media->custom_properties ?? [],
        );
    }
}
