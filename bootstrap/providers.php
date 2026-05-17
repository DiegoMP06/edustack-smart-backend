<?php

use App\Modules\Blog\Providers\BlogProvider;
use App\Modules\Classroom\Providers\ClassroomProvider;
use App\Modules\Forms\Providers\FormsProvider;
use App\Modules\Media\Providers\MediaProvider;
use App\Modules\Projects\Providers\ProjectsProvider;
use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\Modules\EventProvider;
use App\Providers\TypeScriptTransformerServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    MediaProvider::class,
    BlogProvider::class,
    EventProvider::class,
    ProjectsProvider::class,
    FormsProvider::class,
    ClassroomProvider::class,
    TypeScriptTransformerServiceProvider::class,
];
