<?php

namespace WTG\Exceptions;

use WTG\Constant;
use WTG\Contracts\Models\CustomerContract;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

/**
 * Exception handler.
 *
 * @package     WTG
 * @subpackage  Exceptions
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     * @throws \Exception
     */
    public function report(\Exception $exception)
    {
        if (app()->environment() === Constant::ENV_PROD) {
            if (app()->bound('sentry') && $this->shouldReport($exception)) {
                app('sentry')->captureException($exception);
            }
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, \Exception $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            return back()->withErrors(__("Uw sessie is verlopen, probeer het opnieuw."));
        }

        if ($exception instanceof AuthorizationException) {
            return back()->withErrors(__('U hebt onvoldoende rechten om deze actie uit te voeren.'));
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['message' => 'Unauthenticated.'], 401)
            : redirect()->guest(route('auth.login'));
    }

    /**
     * Get the default context variables for logging.
     *
     * @return array
     */
    protected function context()
    {
        try {
            /** @var null|CustomerContract $customer */
            $customer = auth()->user();

            return array_filter([
                'userId' => auth()->id(),
                'email' => $customer ? $customer->getContact()->contactEmail() : null,
                'customer_number' => $customer ? $customer->getCompany()->customerNumber() : null,
            ]);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpException  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $e)
    {
        $status = $e->getStatusCode();

        view()->replaceNamespace('errors', [
            resource_path('views/pages/errors'),
            __DIR__.'/views',
        ]);

        if (view()->exists($view = "errors::{$status}")) {
            return response()->view($view, ['exception' => $e], $status, $e->getHeaders());
        }

        return $this->convertExceptionToResponse($e);
    }

    /**
     * Prepare exception for rendering.
     *
     * @param  \Exception  $e
     * @return \Exception
     */
    protected function prepareException(\Exception $e)
    {
        if ($e instanceof TokenMismatchException) {
            return $e;
        }

        return parent::prepareException($e);
    }
}
