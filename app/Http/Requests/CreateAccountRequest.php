<?php

namespace WTG\Http\Requests;

use WTG\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateAccountRequest
 *
 * @package     WTG\Http
 * @subpackage  Requests
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CreateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var Customer $customer */
        $customer = $this->user();

        return $customer->hasAnyRole([
            Customer::CUSTOMER_ROLE_MANAGER,
            Customer::CUSTOMER_ROLE_ADMIN,
            Customer::CUSTOMER_ROLE_SUPER_ADMIN
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'  => ['required'],
            'email'     => ['required', 'email'],
            'password'  => ['required', 'confirmed'],
            'role'      => ['required']
        ];
    }
}
