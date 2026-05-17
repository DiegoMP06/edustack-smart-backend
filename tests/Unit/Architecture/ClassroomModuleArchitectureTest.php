<?php

use Illuminate\Support\Facades\File;
use Tests\TestCase;

uses(TestCase::class);

it('keeps domain layer isolated from http and infrastructure', function () {
    $domainPath = base_path('app/Modules/Classroom/Domain');

    expect(File::isDirectory($domainPath))->toBeTrue();

    $files = File::allFiles($domainPath);

    foreach ($files as $file) {
        $content = File::get($file->getPathname());

        expect($content)->not->toContain('App\\Modules\\Classroom\\Http\\');
        expect($content)->not->toContain('App\\Modules\\Classroom\\Infrastructure\\');
    }
});

it('keeps application layer isolated from http', function () {
    $applicationPath = base_path('app/Modules/Classroom/Application');

    expect(File::isDirectory($applicationPath))->toBeTrue();

    $files = File::allFiles($applicationPath);

    foreach ($files as $file) {
        $content = File::get($file->getPathname());

        expect($content)->not->toContain('App\\Modules\\Classroom\\Http\\');
    }
});

it('keeps controllers from depending on infrastructure classes', function () {
    $controllersPath = base_path('app/Modules/Classroom/Http/Controllers');

    expect(File::isDirectory($controllersPath))->toBeTrue();

    $files = File::allFiles($controllersPath);

    foreach ($files as $file) {
        $content = File::get($file->getPathname());

        expect($content)->not->toContain('App\\Modules\\Classroom\\Infrastructure\\');
    }
});

it('binds classroom contracts to infrastructure implementations', function () {
    $providerPath = base_path('app/Modules/Classroom/Providers/ClassroomProvider.php');
    $content = File::get($providerPath);

    expect($content)->toContain('CourseReadRepository::class, EloquentCourseReadRepository::class');
    expect($content)->toContain('CourseWriteRepository::class, EloquentCourseWriteRepository::class');
    expect($content)->toContain('CourseFormOptionsRepository::class, EloquentCourseFormOptionsRepository::class');
    expect($content)->toContain('AssignmentSubmissionWriteRepository::class, EloquentAssignmentSubmissionWriteRepository::class');
});

it('keeps classroom dtos inside application layer', function () {
    $legacyDtosPath = base_path('app/Modules/Classroom/DTOs');
    $applicationDtosPath = base_path('app/Modules/Classroom/Application/DTOs');

    $legacyDtoFiles = File::isDirectory($legacyDtosPath)
        ? File::glob($legacyDtosPath.'/*.php')
        : [];

    expect($legacyDtoFiles)->toBeEmpty();
    expect(File::isDirectory($applicationDtosPath))->toBeTrue();
});

it('does not use services layer in classroom module', function () {
    $legacyServicesPath = base_path('app/Modules/Classroom/Services');
    $applicationServicesPath = base_path('app/Modules/Classroom/Application/Services');

    $legacyServiceFiles = File::isDirectory($legacyServicesPath)
        ? File::glob($legacyServicesPath.'/*.php')
        : [];

    $applicationServiceFiles = File::isDirectory($applicationServicesPath)
        ? File::glob($applicationServicesPath.'/*.php')
        : [];

    expect($legacyServiceFiles)->toBeEmpty();
    expect($applicationServiceFiles)->toBeEmpty();
});

it('does not use legacy classroom layer directories', function () {
    $legacyDirectories = [
        base_path('app/Modules/Classroom/Actions'),
        base_path('app/Modules/Classroom/DTOs'),
        base_path('app/Modules/Classroom/Queries'),
        base_path('app/Modules/Classroom/Services'),
        base_path('app/Modules/Classroom/Policies'),
    ];

    foreach ($legacyDirectories as $legacyDirectory) {
        $legacyFiles = File::isDirectory($legacyDirectory)
            ? File::glob($legacyDirectory.'/*.php')
            : [];

        expect($legacyFiles)->toBeEmpty();
    }
});

it('organizes use cases in command and query namespaces', function () {
    $commandUseCasesPath = base_path('app/Modules/Classroom/Application/UseCases/Command');
    $queryUseCasesPath = base_path('app/Modules/Classroom/Application/UseCases/Query');
    $legacyUseCasesPath = base_path('app/Modules/Classroom/Application/UseCases');

    expect(File::isDirectory($commandUseCasesPath))->toBeTrue();
    expect(File::isDirectory($queryUseCasesPath))->toBeTrue();

    $legacyUseCaseFiles = File::glob($legacyUseCasesPath.'/*.php');

    expect($legacyUseCaseFiles)->toBeEmpty();
});

it('extracts query options for spatie configuration', function () {
    $queryOptionsPath = base_path('app/Modules/Classroom/Infrastructure/Queries/Options');

    expect(File::exists($queryOptionsPath.'/CourseIndexQueryOptions.php'))->toBeTrue();
});

it('uses declarative provider registration methods', function () {
    $providerPath = base_path('app/Modules/Classroom/Providers/ClassroomProvider.php');
    $content = File::get($providerPath);

    expect($content)->toContain('registerContracts()');
    expect($content)->toContain('registerRoutes()');
});
