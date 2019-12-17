<?php

declare(strict_types=1);

namespace WTG\Services\Checkout;

use Illuminate\Support\Collection;
use WTG\Contracts\Models\CompanyContract;
use WTG\Contracts\Models\OrderContract;
use WTG\Models\Order;

/**
 * Order service.
 *
 * @package     WTG\Services
 * @subpackage  Checkout
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class OrderService
{
    /**
     * Get the orders for a company.
     *
     * @param CompanyContract $company
     * @return Collection
     */
    public function getForCompany(CompanyContract $company): Collection
    {
        /** @var Order $orderModel */
        $orderModel = app()->make(OrderContract::class);

        return $orderModel->where('company_id', $company->getId())->get();
    }
}
