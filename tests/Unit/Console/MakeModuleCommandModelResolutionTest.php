<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->modulePath = app_path('Modules/CommandModelResolutionTest');
});

afterEach(function () {
    if (File::isDirectory($this->modulePath)) {
        File::deleteDirectory($this->modulePath);
    }
});

it('generates crud controller using explicit model option', function () {
    Artisan::call('make-module:controller', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'ManagePosts',
        '--crud' => true,
        '--model' => 'Post',
    ]);

    $path = app_path('Modules/CommandModelResolutionTest/Http/Controllers/ManagePostsController.php');
    $contents = File::get($path);

    expect($contents)
        ->toContain('class ManagePostsController')
        ->toContain('use App\Models\CommandModelResolutionTest\Post;')
        ->toContain('StorePostRequest')
        ->toContain('private PostService $service');
});

it('does not duplicate controller suffix in generated file name', function () {
    Artisan::call('make-module:controller', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'PostController',
    ]);

    expect(File::exists(app_path('Modules/CommandModelResolutionTest/Http/Controllers/PostController.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/CommandModelResolutionTest/Http/Controllers/PostControllerController.php')))->toBeFalse();
});

it('generates query class with explicit model option', function () {
    Artisan::call('make-module:query', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'PublishedPostsQuery',
        '--model' => 'Post',
    ]);

    $path = app_path('Modules/CommandModelResolutionTest/Queries/PublishedPostsQuery.php');
    $contents = File::get($path);

    expect($contents)
        ->toContain('class PublishedPostsQuery')
        ->toContain('use App\Models\CommandModelResolutionTest\Post;')
        ->toContain('QueryBuilder::for(Post::class)');
});

it('generates query class using api queryable utility', function () {
    Artisan::call('make-module:query', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'PublishedPostsQuery',
        '--model' => 'Post',
        '--api-queryable' => true,
    ]);

    $path = app_path('Modules/CommandModelResolutionTest/Queries/PublishedPostsQuery.php');
    $contents = File::get($path);

    expect($contents)
        ->toContain('use App\Concerns\ApiQueryable;')
        ->toContain('use ApiQueryable;')
        ->toContain('return $this->buildQuery(')
        ->toContain('subject: Post::query(),');
});

it('generates resource class with media resource utility', function () {
    Artisan::call('make-module:resource', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'PostResource',
        '--media' => true,
    ]);

    $path = app_path('Modules/CommandModelResolutionTest/Http/Resources/PostResource.php');
    $contents = File::get($path);

    expect($contents)
        ->toContain('use App\Http\Resources\MediaResource;')
        ->toContain('method_exists($this->resource')
        ->toContain("getMedia('gallery')")
        ->toContain('new MediaResource($item, $mainConversion, $dimensions, $extraConversions)->toArray(request())');
});

it('generates query filter with dedicated command', function () {
    Artisan::call('make-module:query-filter', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'FeaturedPostsQuery',
        '--model' => 'Post',
    ]);

    $path = app_path('Modules/CommandModelResolutionTest/Queries/FeaturedPostsQuery.php');
    $contents = File::get($path);

    expect($contents)
        ->toContain('class FeaturedPostsQuery')
        ->toContain('use App\Concerns\ApiQueryable;')
        ->toContain('subject: Post::query(),');
});

it('generates media resource with dedicated command', function () {
    Artisan::call('make-module:media-resource', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'PostResource',
        '--collection' => true,
    ]);

    $resourcePath = app_path('Modules/CommandModelResolutionTest/Http/Resources/PostResource.php');
    $collectionPath = app_path('Modules/CommandModelResolutionTest/Http/Resources/PostCollection.php');
    $contents = File::get($resourcePath);

    expect(File::exists($collectionPath))->toBeTrue();
    expect($contents)
        ->toContain('use App\Http\Resources\MediaResource;')
        ->toContain('if (method_exists(')
        ->toContain("getMedia('gallery')");
});

it('publishes api queryable utility classes for module architecture', function () {
    Artisan::call('make-module:publish-api-queryable', [
        'module' => 'CommandModelResolutionTest',
    ]);

    $apiQueryablePath = app_path('Modules/CommandModelResolutionTest/Concerns/ApiQueryable.php');
    $globalScoutFilterPath = app_path('Modules/CommandModelResolutionTest/Queries/Filters/GlobalScoutFilter.php');

    expect(File::exists($apiQueryablePath))->toBeTrue();
    expect(File::exists($globalScoutFilterPath))->toBeTrue();

    expect(File::get($apiQueryablePath))
        ->toContain('namespace App\\Modules\\CommandModelResolutionTest\\Concerns;')
        ->toContain('use App\\Modules\\CommandModelResolutionTest\\Queries\\Filters\\GlobalScoutFilter;');
});

it('publishes media resource utility classes for module architecture', function () {
    Artisan::call('make-module:publish-media-resource', [
        'module' => 'CommandModelResolutionTest',
    ]);

    $mediaResourcePath = app_path('Modules/CommandModelResolutionTest/Http/Resources/MediaResource.php');
    $mapsMediaPath = app_path('Modules/CommandModelResolutionTest/Http/Resources/Concerns/MapsMedia.php');

    expect(File::exists($mediaResourcePath))->toBeTrue();
    expect(File::exists($mapsMediaPath))->toBeTrue();

    expect(File::get($mapsMediaPath))
        ->toContain('namespace App\\Modules\\CommandModelResolutionTest\\Http\\Resources\\Concerns;')
        ->toContain('use App\\Modules\\CommandModelResolutionTest\\Http\\Resources\\MediaResource;');
});

it('normalizes common class suffixes across module commands', function () {
    Artisan::call('make-module:action', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'PublishPostAction',
    ]);

    Artisan::call('make-module:event', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'PostPublishedEvent',
    ]);

    Artisan::call('make-module:job', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'SyncPostJob',
    ]);

    Artisan::call('make-module:listener', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'SendPostMailListener',
        '--event' => 'PostPublishedEvent',
    ]);

    Artisan::call('make-module:notification', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'PostPublishedNotification',
    ]);

    Artisan::call('make-module:request', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'StorePostRequest',
    ]);

    Artisan::call('make-module:service', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'PostPublishingService',
    ]);

    Artisan::call('make-module:resource', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'PostResource',
    ]);

    expect(File::exists(app_path('Modules/CommandModelResolutionTest/Actions/PublishPostAction.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/CommandModelResolutionTest/Events/PostPublishedEvent.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/CommandModelResolutionTest/Jobs/SyncPostJob.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/CommandModelResolutionTest/Listeners/SendPostMailListener.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/CommandModelResolutionTest/Notifications/PostPublishedNotification.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/CommandModelResolutionTest/Http/Requests/StorePostRequest.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/CommandModelResolutionTest/Services/PostPublishingService.php')))->toBeTrue();
    expect(File::exists(app_path('Modules/CommandModelResolutionTest/Http/Resources/PostResource.php')))->toBeTrue();
});

it('supports nested class paths like Laravel make commands', function () {
    Artisan::call('make-module:controller', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'Collaborators/ProjectCollaboratorsController',
        '--crud' => true,
        '--model' => 'Project',
    ]);

    Artisan::call('make-module:service', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'Collaborators/ProjectCollaboratorsService',
    ]);

    $controllerPath = app_path('Modules/CommandModelResolutionTest/Http/Controllers/Collaborators/ProjectCollaboratorsController.php');
    $servicePath = app_path('Modules/CommandModelResolutionTest/Services/Collaborators/ProjectCollaboratorsService.php');

    expect(File::exists($controllerPath))->toBeTrue();
    expect(File::exists($servicePath))->toBeTrue();

    expect(File::get($controllerPath))
        ->toContain('namespace App\\Modules\\CommandModelResolutionTest\\Http\\Controllers\\Collaborators;');

    expect(File::get($servicePath))
        ->toContain('namespace App\\Modules\\CommandModelResolutionTest\\Services\\Collaborators;');
});

it('generates invokable controller variation', function () {
    Artisan::call('make-module:controller', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'PublishPost',
        '--invokable' => true,
    ]);

    $controllerPath = app_path('Modules/CommandModelResolutionTest/Http/Controllers/PublishPostController.php');
    $contents = File::get($controllerPath);

    expect($contents)
        ->toContain('class PublishPostController extends Controller')
        ->toContain('public function __invoke(): void');
});

it('generates singleton controller variation', function () {
    Artisan::call('make-module:controller', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'Profile',
        '--singleton' => true,
        '--model' => 'Profile',
    ]);

    $controllerPath = app_path('Modules/CommandModelResolutionTest/Http/Controllers/ProfileController.php');
    $contents = File::get($controllerPath);

    expect($contents)
        ->toContain('public function show(Profile $profile): void')
        ->toContain('public function update(UpdateProfileRequest $request, Profile $profile): void')
        ->not->toContain('public function index(');
});

it('generates nested resource controller variation', function () {
    Artisan::call('make-module:controller', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'ProjectTasks',
        '--nested-resource' => true,
        '--model' => 'Task',
        '--parent' => 'Project',
    ]);

    $controllerPath = app_path('Modules/CommandModelResolutionTest/Http/Controllers/ProjectTasksController.php');
    $contents = File::get($controllerPath);

    expect($contents)
        ->toContain('use App\Models\CommandModelResolutionTest\Project;')
        ->toContain('public function index(Project $project): void')
        ->toContain('public function show(Project $project, Task $task): void');
});

it('generates plain controller variation', function () {
    Artisan::call('make-module:controller', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'HealthCheck',
        '--plain' => true,
    ]);

    $controllerPath = app_path('Modules/CommandModelResolutionTest/Http/Controllers/HealthCheckController.php');
    $contents = File::get($controllerPath);

    expect($contents)
        ->toContain('class HealthCheckController extends Controller')
        ->not->toContain('public function index(')
        ->not->toContain('use App\\Models\\');
});

it('generates modelless controller variation', function () {
    Artisan::call('make-module:controller', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'WebhookEndpoint',
        '--modelless' => true,
    ]);

    $controllerPath = app_path('Modules/CommandModelResolutionTest/Http/Controllers/WebhookEndpointController.php');
    $contents = File::get($controllerPath);

    expect($contents)
        ->toContain('public function index(): void')
        ->toContain('public function show(string $id): void')
        ->not->toContain('StoreWebhookEndpointRequest')
        ->not->toContain('use App\\Models\\');
});

it('generates api resource controller variation', function () {
    Artisan::call('make-module:controller', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'Post',
        '--api-resource' => true,
    ]);

    $controllerPath = app_path('Modules/CommandModelResolutionTest/Http/Controllers/PostController.php');
    $contents = File::get($controllerPath);

    expect($contents)
        ->toContain('public function index(): void')
        ->toContain('public function store(Request $request): void')
        ->toContain('public function show(string $id): void')
        ->toContain('public function update(Request $request, string $id): void')
        ->toContain('public function destroy(string $id): void')
        ->not->toContain('public function create(')
        ->not->toContain('public function edit(');
});

it('supports force and dry run options', function () {
    Artisan::call('make-module:service', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'SyncProjects',
    ]);

    $servicePath = app_path('Modules/CommandModelResolutionTest/Services/SyncProjectsService.php');
    $initialContents = File::get($servicePath);

    Artisan::call('make-module:service', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'SyncProjects',
        '--dry-run' => true,
    ]);

    expect(File::get($servicePath))->toBe($initialContents);

    Artisan::call('make-module:service', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'SyncProjects',
        '--force' => true,
    ]);

    expect(File::exists($servicePath))->toBeTrue();
});

it('generates service without empty constructor when no actions are provided', function () {
    Artisan::call('make-module:service', [
        'module' => 'CommandModelResolutionTest',
        'name' => 'NotifyStudents',
    ]);

    $servicePath = app_path('Modules/CommandModelResolutionTest/Services/NotifyStudentsService.php');
    $contents = File::get($servicePath);

    expect($contents)
        ->not->toContain('public function __construct()')
        ->toContain('class NotifyStudentsService');
});

it('generates crud query and resource with api-queryable and media-resource stubs', function () {
    Artisan::call('make-module:crud-simple', [
        'module' => 'CommandModelResolutionTest',
        'model' => 'Post',
        '--queries' => true,
        '--resources' => true,
        '--api-queryable' => true,
        '--media-resource' => true,
    ]);

    $queryPath = app_path('Modules/CommandModelResolutionTest/Queries/PostQuery.php');
    $resourcePath = app_path('Modules/CommandModelResolutionTest/Http/Resources/PostResource.php');

    expect(File::get($queryPath))->toContain('use App\Concerns\ApiQueryable;');
    expect(File::get($resourcePath))->toContain('use App\Http\Resources\MediaResource;');
});
