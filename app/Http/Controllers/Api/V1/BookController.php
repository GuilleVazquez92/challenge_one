<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\BookService;
use App\Http\Requests\V1\BookRequest;
use App\Http\Resources\V1\BookResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index(Request $request)
    {
        try {
            $books = $this->bookService->getAll($request);
            return BookResource::collection($books);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function store(BookRequest $request): JsonResponse
    {
        try {
            $book = $this->bookService->create($request->validated());
            return response()->json(new BookResource($book), 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $book = $this->bookService->getById($id);
            return response()->json(new BookResource($book));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function update(BookRequest $request, int $id): JsonResponse
    {
        try {
            $book = $this->bookService->update($id, $request->validated());
            return response()->json(new BookResource($book));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->bookService->delete($id);
            return response()->json(['message' => 'Book deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
