<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;

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

    public $message = 'Error desconocido.';
    public $statusCode = 400;

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (\Request::is('api/*') && $exception instanceof \Symfony\Component\Debug\Exception\FatalThrowableError) {
            $this->message = 'Error fatal.';
            $this->statusCode = 500;
            return response()->json($this->exceptionResponse($exception), $this->statusCode);
        }

        if (\Request::is('api/*') && $exception instanceof \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException) {
            $this->message = 'Muchas peticiones.';
            $this->statusCode = 429;
            return response()->json($this->exceptionResponse($exception), $this->statusCode);
        }

        if (\Request::is('api/*') && $exception instanceof \Illuminate\Validation\ValidationException) {
            $this->message = 'Errores de validación.';
            $this->statusCode = 422;
            return response()->json($this->exceptionResponse($exception, $exception->validator->errors()->messages()), $this->statusCode);
        }

        if (\Request::is('api/*') && $exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            $this->message = 'Método no permitido.';
            $this->statusCode = 405;
            return response()->json($this->exceptionResponse($exception), $this->statusCode);
        }

        if (\Request::is('api/*') && $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            $this->message = 'Página no encontrada.';
            $this->statusCode = 404;
            return response()->json($this->exceptionResponse($exception), $this->statusCode);
        }

        if (\Request::is('api/*') && $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $this->message = 'Sin resultados para la consulta.';
            $this->statusCode = 404;
            return response()->json($this->exceptionResponse($exception), $this->statusCode);
        }

        if (\Request::is('api/*') && $exception instanceof AuthorizationException) {
            $this->message = 'Credenciales no validas.';
            $this->statusCode = 404;
            return response()->json($this->exceptionResponse($exception), $this->statusCode);
        }

        return parent::render($request, $exception);
    }

    private function exceptionResponse($exception, $errors = [])
    {
        if (!count($errors)) {
            $errors = config('app.debug') ? [
                'code' => 'CÓDIGO: ' . $this->statusCode,
                'message' => 'MENSAJE: ' . $this->message,
                'file' => 'ARCHIVO: ' . $exception->getFile(),
                'line' => 'LÍNEA: ' . $exception->getLine(),
            ] : [];
        }

        $json = [
            'status' => 1,
            'message' => $this->message,
            'response' => [
                'errors' => $errors,
            ],
        ];

        return $json;
    }
}
