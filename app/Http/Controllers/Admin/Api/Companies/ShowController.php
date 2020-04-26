<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Companies;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Controller;
use WTG\Models\Company;

/**
 * Admin API company data controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ShowController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * ShowController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return JsonResponse
     */
    public function execute(): Response
    {
        $company = Company::with(['customers' => fn($query) => $query->withTrashed()])
            ->withTrashed()
            ->where('id', $this->request->input('id'))
            ->first();

        return response()->json(
            [
                'company'         => $company,
                'initialPassword' => $this->getInitialPassword(),
            ]
        );
    }

    /**
     * @return string|null
     */
    protected function getInitialPassword(): ?string
    {
        $companyId = (string) $this->request->input('id');

        try {
            if (cache()->has('new-customer-password') && cache()->has('new-customer-password-id')) {
                if ((string)cache()->pull('new-customer-password-id') === $companyId) {
                    return cache()->pull('new-customer-password');
                }
            }
        } catch (InvalidArgumentException $e) {
            //
        }

        return null;
    }
}
