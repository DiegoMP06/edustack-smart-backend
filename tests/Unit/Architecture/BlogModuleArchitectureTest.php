<?php

use Illuminate\Support\Facades\File;
use Tests\TestCase;

uses(TestCase::class);

it('keeps domain layer isolated from http and infrastructure', function () {
    $domainPath = base_path('app/Modules/Blog/Domain');

    expect(File::isDirectory($domainPath))->toBeTrue();

    $files = File::allFiles($domainPath);

    foreach ($files as $file) {
        $content = File::get($file->getPathname());

        expect($content)->not->toContain('App\\Modules\\Blog\\Http\\');
        expect($content)->not->toContain('App\\Modules\\Blog\\Infrastructure\\');
    }
});

it('keeps application layer isolated from http', function () {
    $applicationPath = base_path('app/Modules/Blog/Application');

    expect(File::isDirectory($applicationPath))->toBeTrue();

    $files = File::allFiles($applicationPath);

    foreach ($files as $file) {
        $content = File::get($file->getPathname());

        expect($content)->not->toContain('App\\Modules\\Blog\\Http\\');
    }
});

it('keeps controllers from depending on infrastructure classes', function () {
    $controllersPath = base_path('app/Modules/Blog/Http/Controllers');

    expect(File::isDirectory($controllersPath))->toBeTrue();

    $files = File::allFiles($controllersPath);

    foreach ($files as $file) {
        $content = File::get($file->getPathname());

        expect($content)->not->toContain('App\\Modules\\Blog\\Infrastructure\\');
    }
});

it('binds blog contracts to infrastructure implementations', function () {
    $providerPath = base_path('app/Modules/Blog/Providers/BlogProvider.php');
    $content = File::get($providerPath);

    expect($content)->toContain('PostReadRepository::class, EloquentPostReadRepository::class');
    expect($content)->toContain('PostWriteRepository::class, EloquentPostWriteRepository::class');
    expect($content)->toContain('PostViewCounter::class, CachePostViewCounter::class');
});

it('keeps blog dtos inside application layer', function () {
    $legacyDtosPath = base_path('app/Modules/Blog/DTOs');
    $applicationDtosPath = base_path('app/Modules/Blog/Application/DTOs');

    $legacyDtoFiles = File::isDirectory($legacyDtosPath)
        ? File::glob($legacyDtosPath.'/*.php')
        : [];

    expect($legacyDtoFiles)->toBeEmpty();
    expect(File::isDirectory($applicationDtosPath))->toBeTrue();
});

it('does not use services layer in blog module', function () {
    $legacyServicesPath = base_path('app/Modules/Blog/Services');
    $applicationServicesPath = base_path('app/Modules/Blog/Application/Services');

    $legacyServiceFiles = File::isDirectory($legacyServicesPath)
        ? File::glob($legacyServicesPath.'/*.php')
        : [];

    $applicationServiceFiles = File::isDirectory($applicationServicesPath)
        ? File::glob($applicationServicesPath.'/*.php')
        : [];

    expect($legacyServiceFiles)->toBeEmpty();
    expect($applicationServiceFiles)->toBeEmpty();
});

it('does not use legacy blog layer directories', function () {
    $legacyDirectories = [
        base_path('app/Modules/Blog/Actions'),
        base_path('app/Modules/Blog/DTOs'),
        base_path('app/Modules/Blog/Queries'),
        base_path('app/Modules/Blog/Services'),
    ];

    foreach ($legacyDirectories as $legacyDirectory) {
        $legacyFiles = File::isDirectory($legacyDirectory)
            ? File::glob($legacyDirectory.'/*.php')
            : [];

        expect($legacyFiles)->toBeEmpty();
    }
});

it('organizes use cases in command namespace', function () {
    $commandUseCasesPath = base_path('app/Modules/Blog/Application/UseCases/Command');
    $legacyUseCasesPath = base_path('app/Modules/Blog/Application/UseCases');

    expect(File::isDirectory($commandUseCasesPath))->toBeTrue();

    $legacyUseCaseFiles = File::glob($legacyUseCasesPath.'/*.php');

    expect($legacyUseCaseFiles)->toBeEmpty();
});

it('encapsulates spatie query configuration in options classes', function () {
    $queryOptionsPath = base_path('app/Modules/Blog/Infrastructure/Queries/Options');

    expect(File::exists($queryOptionsPath.'/PostIndexQueryOptions.php'))->toBeTrue();
    expect(File::exists($queryOptionsPath.'/PublishedPostIndexQuery.php'))->toBeFalse();
    expect(File::exists($queryOptionsPath.'/PublishedPostIndexQueryOptions.php'))->toBeTrue();
});

it('uses a dedicated mapper for data transformation', function () {
    $supportPath = base_path('app/Modules/Blog/Application/Support');

    expect(File::exists($supportPath.'/PostDataMapper.php'))->toBeTrue();
});

it('extracts query options for spatie configuration', function () {
    $queryOptionsPath = base_path('app/Modules/Blog/Infrastructure/Queries/Options');

    expect(File::exists($queryOptionsPath.'/PostIndexQueryOptions.php'))->toBeTrue();
    expect(File::exists($queryOptionsPath.'/PublishedPostIndexQueryOptions.php'))->toBeTrue();
});

it('uses declarative provider registration methods', function () {
    $providerPath = base_path('app/Modules/Blog/Providers/BlogProvider.php');
    $content = File::get($providerPath);

    expect($content)->toContain('registerContracts()');
    expect($content)->toContain('registerPolicies()');
    expect($content)->toContain('registerRoutes()');
});
