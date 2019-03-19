<?php

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
}