<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e): JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        $status = $e instanceof HttpExceptionInterface
            ? $e->getStatusCode()
            : 500;

        return $this->fail($e, $status);

    }
}
