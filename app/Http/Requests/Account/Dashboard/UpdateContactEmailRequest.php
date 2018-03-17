<?php

namespace WTG\Http\Requests\Account\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Update contact email request.
 *
 * @package     WTG\Http
 * @subpackage  Requests\Account\Dashboard
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class UpdateContactEmailRequest extends FormRequest
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
            'email' => ['required', 'email']
        ];
    }
}
