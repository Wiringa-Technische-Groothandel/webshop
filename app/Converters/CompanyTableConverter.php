<?php

namespace WTG\Converters;

use WTG\Models\Company;
use Illuminate\Database\Eloquent\Model;

/**
 * Company table converter.
 *
 * @package     WTG\Converters
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CompanyTableConverter extends AbstractTableConverter
{
    /**
     * @var array
     */
    protected $csvFields = [
        'id',
        'login',
        'company',
        'street',
        'postcode',
        'city',
        'email',
        'active',
        'created_at',
        'updated_at'
    ];

    /**
     * Create a new entity.
     *
     * @param  array  $data
     * @return Model
     */
    public function createModel(array $data): ?Model
    {
        $company = new Company;

        $company->setAttribute('customer_number', $data['login']);
        $company->setAttribute('name', $data['company']);
        $company->setAttribute('street', $data['street']);
        $company->setAttribute('postcode', $data['postcode']);
        $company->setAttribute('city', $data['city']);
        $company->setAttribute('active', $data['active']);

        return $company;
    }
}