<?php

namespace App\Modules\Admin\DTOs;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UpdateUserRoleFormData extends Data
{
    public function __construct(
        public string $role,
    ) {}
}
