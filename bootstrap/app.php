<?php

use App\Http\Middleware\ActiveAccount;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\Inactive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function (): void {
            // [module-routes]
            if (file_exists(base_path('app/Modules/FeatureArchitectureTest/routes/article.php'))) {
                require base_path('app/Modules/FeatureArchitectureTest/routes/article.php');
            }
            if (file_exists(base_path('app/Modules/Admin/routes/user.php'))) {
                require base_path('app/Modules/Admin/routes/user.php');
            }
            if (file_exists(base_path('app/Modules/Forms/routes/form.php'))) {
                require base_path('app/Modules/Forms/routes/form.php');
            }
            if (file_exists(base_path('app/Modules/Classroom/routes/course.php'))) {
                require base_path('app/Modules/Classroom/routes/course.php');
            }
            if (file_exists(base_path('app/Modules/Projects/routes/project.php'))) {
                require base_path('app/Modules/Projects/routes/project.php');
            }
            if (file_exists(base_path('app/Modules/Events/routes/event.php'))) {
                require base_path('app/Modules/Events/routes/event.php');
            }
            if (file_exists(base_path('app/Modules/Blog/routes/post.php'))) {
                require base_path('app/Modules/Blog/routes/post.php');
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'inactive' => Inactive::class,
            'active' => ActiveAccount::class,
        ]);

        $middleware->statefulApi();

        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
