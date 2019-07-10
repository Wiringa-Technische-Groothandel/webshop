<?php

namespace WTG\Http\Requests;

use WTG\Models\Customer as CustomerModel;
use WTG\Models\Role as RoleModel;

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
        /** @var CustomerModel $customer */
        $customer = $this->user();

        return $customer->getRole()->getLevel() === RoleModel::ROLE_MANAGER;
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
