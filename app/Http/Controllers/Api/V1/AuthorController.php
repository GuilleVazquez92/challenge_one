<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AuthorService;
use App\Http\Requests\V1\AuthorRequest;
use App\Http\Resources\V1\AuthorResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;


class AuthorController extends Controller
{
    protected $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    public function index(Request $request)
    {
        try {
            return AuthorResource::collection($this->authorService->getAll($request));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function store(AuthorRequest $request): JsonResponse
    {
        try {
            $author = $this->authorService->create($request->validated());
            return response()->json(new AuthorResource($author));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $author = $this->authorService->getById($id);
            return response()->json(new AuthorResource($author));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function update(AuthorRequest $request, int $id): JsonResponse
    {
        try {
            $author = $this->authorService->update($id, $request->validated());
            return response()->json(new AuthorResource($author));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->authorService->delete($id);
            return response()->json(['message' => 'Author deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
