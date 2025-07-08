<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

        // Personalizar o tratamento de erros 404
        $this->renderable(function (NotFoundHttpException $e) {
            return response()->view('errors.404', [], 404);
        });

        // Personalizar o tratamento de erros 500
        $this->renderable(function (HttpException $e) {
            if ($e->getStatusCode() === 500) {
                return response()->view('errors.500', [], 500);
            }
        });
    }
}
