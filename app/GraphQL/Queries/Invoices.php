<?php

namespace WTG\GraphQL\Queries;

use Illuminate\Support\Collection;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use WTG\Models\Customer;
use WTG\Models\Invoice;

/**
 * Invoice resolver.
 *
 * @package     WTG\GraphQL\Queries
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Invoices
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @param GraphQLContext $context
     * @return Collection|Invoice[]
     */
    public function __invoke($_, array $args, GraphQLContext $context): Collection
    {
        /** @var Customer $customer */
        $customer = $context->user();

        if (! $customer) {
            return collect();
        }

        return $customer->getCompany()->getInvoices();
    }
}
