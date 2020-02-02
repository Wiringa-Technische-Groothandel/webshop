<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Companies;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Models\Company;

/**
 * Admin API company list data controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function execute(): Response
    {
        $companies = Company::with('customers')
            ->withTrashed()
            ->orderBy('customer_number')
            ->get()
            ->map(
                function (Company $company) {
                    return [
                        'id'              => $company->getId(),
                        'customer_number' => $company->getCustomerNumber() . ($company->trashed() ? ' [!]' : ''),
                        'name'            => $company->getName(),
                        'account_count'   => $company->getCustomers()->count(),
                        'created_at'      => $company->created_at->format('Y-m-d H:i'),
                        'updated_at'      => $company->updated_at->format('Y-m-d H:i'),
                    ];
                }
            );

        return response()->json(
            [
                'companies' => $companies,
            ]
        );
    }
}
