<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        // Si no se encuentra el modelo
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Registro no encontrado'
            ], 404);
        }

        // Si no se encuentra la ruta
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Ruta no encontrada'
            ], 404);
        }

        return parent::render($request, $exception);
    }
}