<?php

declare(strict_types=1);

namespace WTG\Http\Requests\Account\Address;

use Illuminate\Foundation\Http\FormRequest;

/**
 * update default address request.
 *
 * @package     WTG\Http
 * @subpackage  Requests\Account\Address
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class UpdateDefaultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address' => ['required', 'exists:addresses,id']
        ];
    }
}
