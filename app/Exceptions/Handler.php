<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

use ErrorException;
use RuntimeException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }

        if ($exception instanceof QueryException) {
            Log::critical("[Exception Handler][QueryException] {$exception->getMessage()}");
            return $this->errorResponse('Internal Server Error...🤯', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            Log::error('[Exception Handler][MethodNotAllowedHttpException]');
            return $this->errorResponse('Method Not Allowed...🤫', Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof NotFoundHttpException) {
            Log::error('[Exception Handler][NotFoundHttpException]');
            return $this->errorResponse('Oops! It Seems like you are lost...🤔', Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof RuntimeException) {
            Log::critical('[Exception Handler][RuntimeException] '. $exception->getMessage());
            return $this->errorResponse('Internal Server Error...🤯', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        if ($exception instanceof ModelNotFoundException) {
            Log::critical('[Exception Handler][ModelNotFoundException] '. $exception->getMessage());
            return $this->errorResponse('Internal Server Error...🤯', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($exception instanceof ErrorException) {
            Log::critical('[Exception Handler][ErrorException] '. $exception->getMessage());
            return $this->errorResponse('Internal Server Error...🤯', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Log::critical('[Exception Handler][Exception] '. $exception->getMessage());
        return $this->errorResponse('Internal Server Error...🤯', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
