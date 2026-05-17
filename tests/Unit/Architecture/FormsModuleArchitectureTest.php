<?php

use Illuminate\Support\Facades\File;
use Tests\TestCase;

uses(TestCase::class);

it('keeps domain layer isolated from http and infrastructure', function () {
    $domainPath = base_path('app/Modules/Forms/Domain');

    expect(File::isDirectory($domainPath))->toBeTrue();

    $files = File::allFiles($domainPath);

    foreach ($files as $file) {
        $content = File::get($file->getPathname());

        expect($content)->not->toContain('App\\Modules\\Forms\\Http\\');
        expect($content)->not->toContain('App\\Modules\\Forms\\Infrastructure\\');
    }
});

it('keeps application layer isolated from http', function () {
    $applicationPath = base_path('app/Modules/Forms/Application');

    expect(File::isDirectory($applicationPath))->toBeTrue();

    $files = File::allFiles($applicationPath);

    foreach ($files as $file) {
        $content = File::get($file->getPathname());

        expect($content)->not->toContain('App\\Modules\\Forms\\Http\\');
    }
});

it('keeps controllers from depending on infrastructure classes', function () {
    $controllersPath = base_path('app/Modules/Forms/Http/Controllers');

    expect(File::isDirectory($controllersPath))->toBeTrue();

    $files = File::allFiles($controllersPath);

    foreach ($files as $file) {
        $content = File::get($file->getPathname());

        expect($content)->not->toContain('App\\Modules\\Forms\\Infrastructure\\');
    }
});

it('binds forms contracts to infrastructure implementations', function () {
    $providerPath = base_path('app/Modules/Forms/Providers/FormsProvider.php');
    $content = File::get($providerPath);

    expect($content)->toContain('FormReadRepository::class, EloquentFormReadRepository::class');
    expect($content)->toContain('FormWriteRepository::class, EloquentFormWriteRepository::class');
    expect($content)->toContain('FormFormOptionsRepository::class, EloquentFormFormOptionsRepository::class');
});

it('keeps forms dtos inside application layer', function () {
    $legacyDtosPath = base_path('app/Modules/Forms/DTOs');
    $applicationDtosPath = base_path('app/Modules/Forms/Application/DTOs');

    $legacyDtoFiles = File::isDirectory($legacyDtosPath)
        ? File::glob($legacyDtosPath.'/*.php')
        : [];

    expect($legacyDtoFiles)->toBeEmpty();
    expect(File::isDirectory($applicationDtosPath))->toBeTrue();
});

it('does not use services layer in forms module', function () {
    $legacyServicesPath = base_path('app/Modules/Forms/Services');
    $applicationServicesPath = base_path('app/Modules/Forms/Application/Services');

    $legacyServiceFiles = File::isDirectory($legacyServicesPath)
        ? File::glob($legacyServicesPath.'/*.php')
        : [];

    $applicationServiceFiles = File::isDirectory($applicationServicesPath)
        ? File::glob($applicationServicesPath.'/*.php')
        : [];

    expect($legacyServiceFiles)->toBeEmpty();
    expect($applicationServiceFiles)->toBeEmpty();
});

it('does not use legacy forms layer directories', function () {
    $legacyDirectories = [
        base_path('app/Modules/Forms/Actions'),
        base_path('app/Modules/Forms/DTOs'),
        base_path('app/Modules/Forms/Queries'),
        base_path('app/Modules/Forms/Services'),
        base_path('app/Modules/Forms/Policies'),
    ];

    foreach ($legacyDirectories as $legacyDirectory) {
        $legacyFiles = File::isDirectory($legacyDirectory)
            ? File::glob($legacyDirectory.'/*.php')
            : [];

        expect($legacyFiles)->toBeEmpty();
    }
});

it('organizes use cases in command and query namespaces', function () {
    $commandUseCasesPath = base_path('app/Modules/Forms/Application/UseCases/Command');
    $queryUseCasesPath = base_path('app/Modules/Forms/Application/UseCases/Query');
    $legacyUseCasesPath = base_path('app/Modules/Forms/Application/UseCases');

    expect(File::isDirectory($commandUseCasesPath))->toBeTrue();
    expect(File::isDirectory($queryUseCasesPath))->toBeTrue();

    $legacyUseCaseFiles = File::glob($legacyUseCasesPath.'/*.php');

    expect($legacyUseCaseFiles)->toBeEmpty();
});

it('uses a dedicated mapper for data transformation', function () {
    $supportPath = base_path('app/Modules/Forms/Application/Support');

    expect(File::exists($supportPath.'/FormDataMapper.php'))->toBeTrue();
});

it('extracts query options for spatie configuration', function () {
    $queryOptionsPath = base_path('app/Modules/Forms/Infrastructure/Queries/Options');

    expect(File::exists($queryOptionsPath.'/FormIndexQueryOptions.php'))->toBeTrue();
    expect(File::exists($queryOptionsPath.'/FormResponseIndexQueryOptions.php'))->toBeTrue();
});

it('uses declarative provider registration methods', function () {
    $providerPath = base_path('app/Modules/Forms/Providers/FormsProvider.php');
    $content = File::get($providerPath);

    expect($content)->toContain('registerContracts()');
    expect($content)->toContain('registerPolicies()');
    expect($content)->toContain('registerRoutes()');
});
