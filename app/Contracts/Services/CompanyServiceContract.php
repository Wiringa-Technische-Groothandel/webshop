<?php

declare(strict_types=1);

namespace WTG\Contracts\Services;

use WTG\Contracts\Models\CompanyContract;

/**
 * Company service contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface CompanyServiceContract
{
    /**
     * Create a new company.
     *
     * @param  array  $data
     * @return CompanyContract
     */
    public function createCompany(array $data): CompanyContract;

    /**
     * Update a company.
     *
     * @param array $data
     * @return CompanyContract
     */
    public function updateCompany(array $data): CompanyContract;
}