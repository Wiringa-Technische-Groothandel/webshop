<?php

namespace WTG\GraphQL\Queries;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use WTG\Models\Customer;
use WTG\Models\Invoice as InvoiceModel;

/**
 * Invoice resolver.
 *
 * @package     WTG\GraphQL\Queries
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Invoice
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @param GraphQLContext $context
     * @return InvoiceModel|null
     */
    public function __invoke($_, array $args, GraphQLContext $context): ?InvoiceModel
    {
        /** @var Customer $customer */
        $customer = $context->user();

        if (! $customer) {
            return null;
        }

        return $customer->getCompany()->invoices()->where('invoice_number', $args['invoiceNumber'])->first();
    }
}
