<?php

namespace App\Modules\Media\Http\Controllers;

use App\Modules\Media\Actions\GeneratePresignedURLAction;
use App\Modules\Media\DTOs\GeneratePresignedURLFormData;
use App\Modules\Media\Http\Requests\StoreGeneratePresignedURLRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class GeneratePresignedURLController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(
        StoreGeneratePresignedURLRequest $request,
        GeneratePresignedURLAction $action,
    ): JsonResponse {
        $data = GeneratePresignedURLFormData::from($request->validated());

        $urls = $action->execute($data);

        return response()->json($urls);
    }
}
