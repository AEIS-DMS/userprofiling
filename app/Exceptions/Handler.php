<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Exception $exception, Request $request) {
            if ($exception instanceof NotFoundHttpException) {
                return new JsonResponse(['error' => Response::HTTP_NOT_FOUND, 'message'  => 'Record Does not exist with the given id']);
            } elseif ($exception instanceof ModelNotFoundException) {
                $model = strtolower(class_basename($exception->getModel()));
                return new JsonResponse(['code' => Response::HTTP_NOT_FOUND, 'message' => "Does not exist any instance of {$model} with the given id"]);
            } elseif ($exception instanceof AuthorizationException) {
                return new JsonResponse(['code' => Response::HTTP_FORBIDDEN, 'message' => $exception->getMessage()]);
            } elseif ($exception instanceof ValidationException) {
                $errors = $exception->validator->errors()->getMessages();
                return new JsonResponse(['code' => Response::HTTP_UNPROCESSABLE_ENTITY, 'message' => $errors]);
            } elseif ($exception instanceof HttpException) {
                $code = $exception->getCode();
                $message = Response::$statusTexts[$code];
                return new JsonResponse(['code'    => $code, 'message' => $message]);
            }

            if (env('APP_DEBUG', false) && env('APP_ENV') == 'production') {
                Log::error('Unexpected', [$exception]);
                return new JsonResponse(['code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => "Unexpected error. Try later."]);
            }

            if (env('APP_DEBUG', false) && env('APP_ENV') == 'local') {
                Log::error('Unexpected', [$exception]);
                return new JsonResponse([
                    'error' => $exception->getMessage(),
                    'code'  => $exception->getCode(),
                    'file'  => $exception->getFile(),
                    'line'  => $exception->getLine(),
                    'previous' => $exception->getPrevious()
                ]);
            }

            return new JsonResponse(['code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => "Unexpected error. Try later."]);
        });
    }
}
