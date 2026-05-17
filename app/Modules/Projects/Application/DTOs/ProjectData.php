<?php

namespace App\Modules\Projects\Application\DTOs;

use App\Models\Projects\Project;
use App\Modules\Admin\DTOs\UserData;
use App\Modules\Media\DTOs\MediaData;
use DateTimeInterface;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript]
class ProjectData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string $description,
        #[LiteralTypeScriptType('Record<string, unknown>')]
        public array $content,
        public string $repository_url,
        public string $demo_url,
        #[TypeScriptType('Array<string>')]
        public array $tech_stack,
        public string $version,
        public string $license,
        public bool $is_featured,
        public bool $is_published,
        #[TypeScriptType('string')]
        public ?DateTimeInterface $published_at,
        public int $project_status_id,
        public int $user_id,
        #[TypeScriptType('string')]
        public DateTimeInterface $created_at,
        #[TypeScriptType('string')]
        public ?DateTimeInterface $updated_at,
        #[TypeScriptType('ProjectStatusData|null')]
        public Lazy|ProjectStatusData|null $status = null,
        #[DataCollectionOf(ProjectCategoryData::class)]
        #[TypeScriptType('Array<ProjectCategoryData>|null')]
        public Lazy|DataCollection|null $categories = null,
        #[TypeScriptType('UserData|null')]
        public Lazy|UserData|null $author = null,
        #[DataCollectionOf(ProjectCollaboratorData::class)]
        #[TypeScriptType('Array<ProjectCollaboratorData>|null')]
        public Lazy|DataCollection|null $collaborators = null,
        /** @var array<MediaData> */
        #[DataCollectionOf(MediaData::class)]
        #[TypeScriptType('Array<MediaData>|null')]
        public Lazy|DataCollection|null $media = null,
    ) {}

    public static function fromModel(Project $project): self
    {
        return new self(
            id: $project->id,
            name: $project->name,
            slug: $project->slug,
            description: $project->description,
            content: $project->content ?? [],
            repository_url: $project->repository_url,
            demo_url: $project->demo_url,
            tech_stack: $project->tech_stack ?? [],
            version: $project->version,
            license: $project->license,
            is_featured: (bool) $project->is_featured,
            is_published: (bool) $project->is_published,
            published_at: $project->published_at,
            project_status_id: $project->project_status_id,
            user_id: $project->user_id,
            created_at: $project->created_at,
            updated_at: $project->updated_at,
            status: Lazy::create(fn () => ProjectStatusData::from($project->status)),
            author: Lazy::create(fn () => UserData::from($project->author)),
            categories: Lazy::create(fn () => ProjectCategoryData::collect($project->categories)),
            collaborators: Lazy::create(fn () => UserData::collect(
                $project->collaborators->map(fn ($user) => ProjectCollaboratorData::fromModelWithPivot($user))
            )),
            media: Lazy::create(fn () => MediaData::collect(
                $project->getMedia('screenshots')->map(fn ($media) => MediaData::fromModel(
                    $media,
                    'main',
                    ['main' => ['width' => 1200, 'height' => 620], 'screenshot' => ['width' => 1920, 'height' => 1080]],
                    ['screenshot'],
                ))
            )),
        );
    }
}
