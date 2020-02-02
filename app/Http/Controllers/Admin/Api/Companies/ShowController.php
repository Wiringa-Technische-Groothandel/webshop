<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Companies;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Admin\Controller;
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
        $companyId = $this->request->input('id');

        if (session()->has('new-customer-password') && session()->has('new-customer-password-id')) {
            if (session()->pull('new-customer-password-id') === $companyId) {
                return session()->pull('new-customer-password');
            }
        }

        return null;
    }
}
