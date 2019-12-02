<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use \Guzzle\Http\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ResponseService;

class Handler extends ExceptionHandler
{
  /**
   * Report or log an exception.
   *
   * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
   * @param  Request  $request
   * @param Exception $exception
   * @return \Illuminate\Http\Response|JsonResponse
   */
  public function render($request, Exception $exception)
  {
    if ($request->expectsJson()) {
      $response = [
        'message' => (string) $exception->getMessage(),
        'status' => 400
      ];
      if ($exception instanceof HttpException) {
        $response['message'] = Response::$statusTexts[$exception->getStatusCode()];
        $response['status'] = $exception->getStatusCode();
      }
      if ($exception instanceof \GuzzleHttp\Exception\ClientException) {
        $response['message'] = $exception->getMessage();
        $response['status'] = $exception->getResponse()->getStatusCode();
      }
      if ($this->isDebugMode()) {
        $response['debug'] = [
          'exception' => get_class($exception)
        ];
      }
      return response()->json([
        'ok' => false,
        'data' => null,
        'error' => $response
      ], $response['status']);
    }
    return parent::render($request, $exception);
  }

  /**
   * Determine if the application is in debug mode
   *
   * @return Boolean
   */
  public function isDebugMode()
  {
    return (Boolean) env('APP_DEBUG');
  }
}
