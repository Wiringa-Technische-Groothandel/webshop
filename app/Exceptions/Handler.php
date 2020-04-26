<?php

declare(strict_types=1);

namespace WTG\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;
use WTG\Contracts\Models\CustomerContract;

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
     * @var string
     */
    private $referenceId;

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        if (
            ! app()->environment(ENV_LOCAL) &&
            app()->bound('sentry') &&
            $this->shouldReport($exception)
        ) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     * @throws Throwable
     */
//    public function render($request, Throwable $exception)
//    {
//        if ($exception instanceof TokenMismatchException) {
//            return back()->withInput($request->except('password'))
//                ->withErrors(__("Uw sessie is verlopen, probeer het opnieuw."));
//        }
//
//        if ($exception instanceof AuthorizationException) {
//            return back()->withInput($request->except('password'))
//                ->withErrors(__('U hebt onvoldoende rechten om deze actie uit te voeren.'));
//        }
//
//        return parent::render($request, $exception);
//    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson() || in_array('admin', $exception->guards())
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
            $this->referenceId = uniqid();
        } catch (Exception $e) {
            $this->referenceId = null;
        }

        try {
            /** @var null|CustomerContract $customer */
            $customer = auth()->user();

            return [
                'userId'          => auth()->id(),
                'email'           => $customer ? $customer->getContact()->contactEmail() : null,
                'customer_number' => $customer ? $customer->getCompany()->customerNumber() : null,
                'referenceId'     => $this->referenceId,
            ];
        } catch (Exception $e) {
            return [
                'referenceId' => $this->referenceId,
            ];
        }
    }
}
