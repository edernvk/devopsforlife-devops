<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Auth\AuthenticationException;
use League\Csv\CannotInsertRecord;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        // Not found exception handler
        if($exception instanceof NotFoundHttpException) {
            return response()->json([
                'error' => [
                    'description' => 'URI Inválida',
                    'message' => $exception->getMessage()
                ]
            ], 404);
        }

        // Method not allowed exception handler
        if($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'error' => [
                    'description' => 'Método não permitido',
                    'message' => $exception->getMessage()
                ]
            ], 405);
        }

        // Unauthorized Exception  handler
        if($exception instanceof UnauthorizedHttpException) {
            return response()->json([
                'error' => [
                    'description' => 'Ação não autorizada',
                    'message' => $exception->getMessage()
                ]
            ], 405);
        }

        // Authentification Exception  handler
        if($exception instanceof AuthenticationException) {
            return response()->json([
                'error' => [
                    'description' => 'Não foi possível realizar a autentificação',
                    'message' => $exception->getMessage()
                ]
            ], 401);
        }

        if($exception instanceof CannotInsertRecord) {
            return response()->json([
                'error' => [
                    'description' => 'Não foi possível criar o arquivo do relatório',
                    'message' => $exception->getMessage()
                ]
            ], 500);
        }

        if($exception instanceof HttpException) {
            return response()->json([
                'error' => [
                    'description' => 'Erro HTTP',
                    'message' => $exception->getMessage()
                ]
            ], $exception->getStatusCode());
        }

        return parent::render($request, $exception);
    }
}
