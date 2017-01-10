<?php

namespace App\Exceptions;

use Redirect;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        NotFoundHttpException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ProductNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @throws \Exception $e
     * @return void
     */
    public function report(\Exception $e)
    {
        // Create a sentry variable
        $sentry = app('sentry');

        // Add the user login if someone is logged in
        if (auth()->check()) {
            $sentry->user_context([
                'id'        => auth()->user()->company_id,
                'username'  => auth()->user()->username,
            ]);
        }

        // Send reportable errors with level 'Error'
        if ($this->shouldReport($e) && app()->environment('production')) {
            $sentry->captureException($e);
        // Else send them with warning and only if someone is logged in
        } elseif (auth()->check()) {
            $sentry->captureException($e, [
                'level' => 'warning',
            ]);
        }

        $trace = $e->getTraceAsString();
        $class = get_class($e);

        if (app()->environment('production') &&
             ! $e instanceof ModelNotFoundException &&
             ! $e instanceof MethodNotAllowedHttpException &&
             ! $e instanceof TokenMismatchException &&
             ! $e instanceof ProductNotFoundException &&
             ! $e instanceof NotFoundHttpException) {
            \Mail::send('email.exception', ['trace' => $trace, 'class' => $class], function ($message) {
                $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

                $message->to('thomas.wiringa@gmail.com');

                $message->subject('[WTG Webshop] Whoops, looks like something went wrong');
            });
        }

        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Exception $e)
    {
        if ($e instanceof ModelNotFoundException || $e instanceof MethodNotAllowedHttpException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        if ($e instanceof TokenMismatchException) {
            return redirect('/')
                ->withErrors('Uw sessie is verlopen, ververs de pagina of log opnieuw in, en probeer het opnieuw');
        }

        if ($this->isUnauthorizedException($e)) {
            $e = new HttpException(403, $e->getMessage());
        }

        if ($this->isHttpException($e)) {
            return $this->toIlluminateResponse($this->renderHttpException($e), $e);
        } else {
            // Display a custom 500 error screen if the app is in production
            if (app()->environment() === 'production') {
                return response()->view('errors.500', ['exception' => $e], 500);
            } else {
                return $this->toIlluminateResponse($this->convertExceptionToResponse($e), $e);
            }
        }
    }
}
