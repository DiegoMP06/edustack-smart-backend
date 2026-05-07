<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->bootstrapPath = base_path('bootstrap/app.php');
    $this->originalBootstrapContents = File::get($this->bootstrapPath);
    $this->modulePath = app_path('Modules/RouteRegistrationTest');
    $this->modelRoutePath = app_path('Modules/RouteRegistrationTest/routes/article.php');
});

afterEach(function () {
    File::put($this->bootstrapPath, $this->originalBootstrapContents);

    if (File::isDirectory($this->modulePath)) {
        File::deleteDirectory($this->modulePath);
    }
});

it('registers module routes in bootstrap when generating crud routes', function () {
    Artisan::call('make-module:crud-simple', [
        'module' => 'RouteRegistrationTest',
        'model' => 'Article',
        '--routes' => true,
    ]);

    $bootstrapContents = File::get($this->bootstrapPath);

    expect($bootstrapContents)
        ->toContain('// [module-routes]')
        ->toContain('app/Modules/RouteRegistrationTest/routes/article.php');
});

it('links feature routes into the module base route file', function () {
    Artisan::call('make-module:crud-simple', [
        'module' => 'RouteRegistrationTest',
        'model' => 'Article',
        '--routes' => true,
    ]);

    Artisan::call('make-module:feature-routes', [
        'module' => 'RouteRegistrationTest',
        'model' => 'Article',
    ]);

    $moduleRoutesContents = File::get($this->modelRoutePath);

    expect($moduleRoutesContents)
        ->toContain('app/Modules/RouteRegistrationTest/routes/article.features.php');
});

it('registers module routes in bootstrap with make module routes command', function () {
    Artisan::call('make-module:routes', [
        'module' => 'RouteRegistrationTest',
        'model' => 'Article',
    ]);

    $bootstrapContents = File::get($this->bootstrapPath);

    expect($bootstrapContents)
        ->toContain('app/Modules/RouteRegistrationTest/routes/article.php');
});

it('links rest routes into the module base route file', function () {
    Artisan::call('make-module:routes', [
        'module' => 'RouteRegistrationTest',
        'model' => 'Article',
    ]);

    Artisan::call('make-module:rest-routes', [
        'module' => 'RouteRegistrationTest',
        'model' => 'Article',
    ]);

    $moduleRoutesContents = File::get($this->modelRoutePath);

    expect($moduleRoutesContents)
        ->toContain('app/Modules/RouteRegistrationTest/routes/article.rest.php');

    $restRoutesContents = File::get(app_path('Modules/RouteRegistrationTest/routes/article.rest.php'));

    expect($restRoutesContents)
        ->toContain("middleware(['api', 'auth:sanctum'])")
        ->toContain("prefix('api/articles')")
        ->toContain("name('api.articles.')")
        ->toContain("Route::match(['put', 'patch']");
});

it('generates and links rest routes when crud option is enabled', function () {
    Artisan::call('make-module:crud-simple', [
        'module' => 'RouteRegistrationTest',
        'model' => 'Article',
        '--routes' => true,
        '--rest-routes' => true,
    ]);

    $moduleRoutesContents = File::get($this->modelRoutePath);

    expect($moduleRoutesContents)
        ->toContain('app/Modules/RouteRegistrationTest/routes/article.rest.php');
});

it('generates and links feature routes when crud option is enabled', function () {
    Artisan::call('make-module:crud-simple', [
        'module' => 'RouteRegistrationTest',
        'model' => 'Article',
        '--routes' => true,
        '--features-routes' => true,
    ]);

    $moduleRoutesContents = File::get($this->modelRoutePath);

    expect($moduleRoutesContents)
        ->toContain('app/Modules/RouteRegistrationTest/routes/article.features.php');
});

it('generates and links rest routes by default in bounded context command', function () {
    Artisan::call('make-module:crud-all', [
        'module' => 'RouteRegistrationTest',
        'model' => 'Article',
    ]);

    $moduleRoutesContents = File::get($this->modelRoutePath);

    expect($moduleRoutesContents)
        ->toContain('app/Modules/RouteRegistrationTest/routes/article.rest.php');
});
