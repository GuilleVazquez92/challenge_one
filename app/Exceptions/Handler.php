<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Exceptions\NotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\InternalServerErrorHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Resource not found'
            ], 404);
        }

        if ($exception instanceof QueryException) {
            return response()->json([
                'message' => 'Database query error: ' . $exception->getMessage()
            ], 500);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Route not found'
            ], 404);
        }

        if ($exception instanceof NotFoundException) {
            return response()->json([
                'message' => $exception->getMessage() 
            ], $exception->getCode() ?: 404); 
        }

        $this->renderable(function (BadRequestHttpException $e, $request) {
            return response()->json([
                'error' => 'Bad Request: ' . $e->getMessage()
            ], 400);
        });

        $this->renderable(function (InternalServerErrorHttpException $e, $request) {
            return response()->json([
                'error' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        });

        return parent::render($request, $exception);
    }
}
