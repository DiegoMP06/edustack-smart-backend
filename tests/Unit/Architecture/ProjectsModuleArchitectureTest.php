<?php

use Illuminate\Support\Facades\File;
use Tests\TestCase;

uses(TestCase::class);

it('keeps domain layer isolated from http and infrastructure', function () {
    $domainPath = base_path('app/Modules/Projects/Domain');

    expect(File::isDirectory($domainPath))->toBeTrue();

    $files = File::allFiles($domainPath);

    foreach ($files as $file) {
        $content = File::get($file->getPathname());

        expect($content)->not->toContain('App\\Modules\\Projects\\Http\\');
        expect($content)->not->toContain('App\\Modules\\Projects\\Infrastructure\\');
    }
});

it('keeps application layer isolated from http', function () {
    $applicationPath = base_path('app/Modules/Projects/Application');

    expect(File::isDirectory($applicationPath))->toBeTrue();

    $files = File::allFiles($applicationPath);

    foreach ($files as $file) {
        $content = File::get($file->getPathname());

        expect($content)->not->toContain('App\\Modules\\Projects\\Http\\');
    }
});

it('keeps controllers from depending on infrastructure classes', function () {
    $controllersPath = base_path('app/Modules/Projects/Http/Controllers');

    expect(File::isDirectory($controllersPath))->toBeTrue();

    $files = File::allFiles($controllersPath);

    foreach ($files as $file) {
        $content = File::get($file->getPathname());

        expect($content)->not->toContain('App\\Modules\\Projects\\Infrastructure\\');
    }
});

it('binds projects contracts to infrastructure implementations', function () {
    $providerPath = base_path('app/Modules/Projects/Providers/ProjectsProvider.php');
    $content = File::get($providerPath);

    expect($content)->toContain('ProjectReadRepository::class, EloquentProjectReadRepository::class');
    expect($content)->toContain('ProjectWriteRepository::class, EloquentProjectWriteRepository::class');
    expect($content)->toContain('ProjectFormOptionsRepository::class, EloquentProjectFormOptionsRepository::class');
});

it('keeps projects dtos inside application layer', function () {
    $legacyDtosPath = base_path('app/Modules/Projects/DTOs');
    $applicationDtosPath = base_path('app/Modules/Projects/Application/DTOs');

    $legacyDtoFiles = File::isDirectory($legacyDtosPath)
        ? File::glob($legacyDtosPath.'/*.php')
        : [];

    expect($legacyDtoFiles)->toBeEmpty();
    expect(File::isDirectory($applicationDtosPath))->toBeTrue();
});

it('does not use services layer in projects module', function () {
    $legacyServicesPath = base_path('app/Modules/Projects/Services');
    $applicationServicesPath = base_path('app/Modules/Projects/Application/Services');

    $legacyServiceFiles = File::isDirectory($legacyServicesPath)
        ? File::glob($legacyServicesPath.'/*.php')
        : [];

    $applicationServiceFiles = File::isDirectory($applicationServicesPath)
        ? File::glob($applicationServicesPath.'/*.php')
        : [];

    expect($legacyServiceFiles)->toBeEmpty();
    expect($applicationServiceFiles)->toBeEmpty();
});

it('does not use legacy projects layer directories', function () {
    $legacyDirectories = [
        base_path('app/Modules/Projects/Actions'),
        base_path('app/Modules/Projects/DTOs'),
        base_path('app/Modules/Projects/Queries'),
        base_path('app/Modules/Projects/Services'),
        base_path('app/Modules/Projects/Policies'),
    ];

    foreach ($legacyDirectories as $legacyDirectory) {
        $legacyFiles = File::isDirectory($legacyDirectory)
            ? File::glob($legacyDirectory.'/*.php')
            : [];

        expect($legacyFiles)->toBeEmpty();
    }
});

it('organizes use cases in command namespace', function () {
    $commandUseCasesPath = base_path('app/Modules/Projects/Application/UseCases/Command');
    $legacyUseCasesPath = base_path('app/Modules/Projects/Application/UseCases');

    expect(File::isDirectory($commandUseCasesPath))->toBeTrue();

    $legacyUseCaseFiles = File::glob($legacyUseCasesPath.'/*.php');

    expect($legacyUseCaseFiles)->toBeEmpty();
});

it('uses a dedicated mapper for data transformation', function () {
    $supportPath = base_path('app/Modules/Projects/Application/Support');

    expect(File::exists($supportPath.'/ProjectDataMapper.php'))->toBeTrue();
});

it('extracts query options for spatie configuration', function () {
    $queryOptionsPath = base_path('app/Modules/Projects/Infrastructure/Queries/Options');

    expect(File::exists($queryOptionsPath.'/ProjectIndexQueryOptions.php'))->toBeTrue();
    expect(File::exists($queryOptionsPath.'/PublishedProjectIndexQueryOptions.php'))->toBeTrue();
});

it('uses declarative provider registration methods', function () {
    $providerPath = base_path('app/Modules/Projects/Providers/ProjectsProvider.php');
    $content = File::get($providerPath);

    expect($content)->toContain('registerContracts()');
    expect($content)->toContain('registerPolicies()');
    expect($content)->toContain('registerRoutes()');
});
