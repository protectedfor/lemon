<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PDOException;
use Throwable;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $exception
     * @return JsonResponse
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {

        // is this request asks for json?
        if ($request->header('Content-Type') == 'application/json') {

            /*  is this exception? */

            if (!empty($exception)) {

                // set default error message
                $response = [
                    'success' => false,
                    'error'   => 'Sorry, can not execute your request.'
                ];

                // If debug mode is enabled
                if (config('app.debug')) {
                    // Add the exception class name, message and stack trace to response
                    $response['exception'] = get_class($exception); // Reflection might be better here
                    $response['message'] = $exception->getMessage();
                    $response['trace'] = $exception->getTrace();
                }

                $status = 400;

                // get correct status code

                // is this validation exception
                if ($exception instanceof ValidationException) {

                    return $this->convertValidationExceptionToResponse($exception, $request);

                    // is it authentication exception
                } else if ($exception instanceof ModelNotFoundException) {

                    $status = 404;

                    $response['error'] = 'Model not found!';

                    // is it authentication exception
                } else if ($exception instanceof AuthenticationException) {

                    $status = 401;

                    $response['error'] = 'Can not finish authentication!';

                    //is it DB exception
                } else if ($exception instanceof PDOException) {

                    $status = 500;

                    $response['error'] = 'Can not finish your query request!';

                    // is it http exception (this can give us status code)
                } else if ($this->isHttpException($exception)) {

                    $status = $exception->getStatusCode();

                    $response['error'] = 'Request error!';

                } else {

                    // for all others check do we have method getStatusCode and try to get it
                    // otherwise, set the status to 400
                    $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 400;

                }

                return response()->json($response, $status);

            }
        }

        return parent::render($request, $exception);
    }
}
