<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->modulePath = app_path('Modules/FeatureArchitectureTest');
});

afterEach(function () {
    if (File::isDirectory($this->modulePath)) {
        File::deleteDirectory($this->modulePath);
    }
});

it('generates decoupled content architecture files', function () {
    Artisan::call('make-module:content', [
        'module' => 'FeatureArchitectureTest',
        'model' => 'Article',
        '--request' => true,
    ]);

    expect(File::exists(app_path('Modules/FeatureArchitectureTest/DTOs/ArticleContentData.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/FeatureArchitectureTest/Actions/UpdateArticleContentAction.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/FeatureArchitectureTest/Services/ArticleContentService.php')))->toBeTrue();

    $controllerContents = File::get(app_path('Modules/FeatureArchitectureTest/Http/Controllers/ArticleContentController.php'));

    expect($controllerContents)
        ->toContain('private ArticleContentService $contentService')
        ->toContain('ArticleContentData::fromArray($request->validated())');
});

it('generates decoupled status architecture files', function () {
    Artisan::call('make-module:status', [
        'module' => 'FeatureArchitectureTest',
        'model' => 'Article',
    ]);

    expect(File::exists(app_path('Modules/FeatureArchitectureTest/DTOs/ArticleStatusData.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/FeatureArchitectureTest/Actions/ToggleArticleStatusAction.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/FeatureArchitectureTest/Services/ArticleStatusService.php')))->toBeTrue();

    $controllerContents = File::get(app_path('Modules/FeatureArchitectureTest/Http/Controllers/ArticleStatusController.php'));

    expect($controllerContents)
        ->toContain('private ArticleStatusService $statusService')
        ->toContain('$this->statusService->toggle($article);');
});

it('generates decoupled media architecture files', function () {
    Artisan::call('make-module:media', [
        'module' => 'FeatureArchitectureTest',
        'model' => 'Article',
    ]);

    expect(File::exists(app_path('Modules/FeatureArchitectureTest/DTOs/ArticleMediaData.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/FeatureArchitectureTest/DTOs/ArticleMediaDeletionData.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/FeatureArchitectureTest/Actions/StoreArticleMediaAction.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/FeatureArchitectureTest/Actions/DeleteArticleMediaAction.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/FeatureArchitectureTest/Services/ArticleMediaService.php')))->toBeTrue();

    $controllerContents = File::get(app_path('Modules/FeatureArchitectureTest/Http/Controllers/ArticleGalleryController.php'));

    expect($controllerContents)
        ->toContain('private ArticleMediaService $mediaService')
        ->toContain('ArticleMediaData::fromArray($request->validated())')
        ->toContain('$this->mediaService->destroy($article, $media);');
});

it('generates bounded context with api-queryable and media-resource stubs', function () {
    Artisan::call('make-module:crud-all', [
        'module' => 'FeatureArchitectureTest',
        'model' => 'Article',
        '--minimal' => true,
    ]);

    Artisan::call('make-module:crud-all', [
        'module' => 'FeatureArchitectureTest',
        'model' => 'Article',
        '--no-model' => true,
        '--no-requests' => true,
        '--api-queryable' => true,
        '--media-resource' => true,
        '--force' => true,
    ]);

    $queryPath = app_path('Modules/FeatureArchitectureTest/Queries/ArticleQuery.php');
    $resourcePath = app_path('Modules/FeatureArchitectureTest/Http/Resources/ArticleResource.php');

    expect(File::get($queryPath))->toContain('use App\Concerns\ApiQueryable;');
    expect(File::get($resourcePath))->toContain('use App\Http\Resources\MediaResource;');
});
