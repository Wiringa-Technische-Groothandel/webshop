<?php

declare(strict_types=1);

namespace WTG\Converters;

use WTG\Models\Address;
use WTG\Models\Company;
use Illuminate\Database\Eloquent\Model;

/**
 * Address table converter.
 *
 * @package     WTG
 * @subpackage  Converters
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AddressTableConverter extends AbstractTableConverter
{
    /**
     * @var array
     */
    protected $csvFields = [
        'id',
        'name',
        'street',
        'city',
        'postcode',
        'telephone',
        'mobile',
        'User_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Create a new entity.
     *
     * @param  array  $data
     * @return Model|null
     */
    public function createModel(array $data): ?Model
    {
        $company = Company::with('addresses')->customerNumber($data['User_id'])->first();

        if ($company === null) {
            \Log::warning('[Address table conversion] No company was found for customer number '.$data['User_id']);

            return null;
        }

        $address = new Address;

        $address->setAttribute('company_id', $company->getAttribute('id'));
        $address->setAttribute('name', $data['name']);
        $address->setAttribute('street', $data['street']);
        $address->setAttribute('postcode', $data['postcode']);
        $address->setAttribute('city', $data['city']);
        $address->setAttribute('phone', $data['telephone']);
        $address->setAttribute('mobile', $data['mobile']);
        $address->setAttribute('is_default', $company->addresses->count() === 0);

        $address->save();

        return null;
    }
}