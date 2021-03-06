<?php

declare(strict_types=1);

namespace WTG\Http\Requests\Account\Profile;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Create address request.
 *
 * @package     WTG\Http
 * @subpackage  Requests\Account\Profile
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class UpdateRequest extends FormRequest
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
            'contact-email' => ['email'],
            'order-email'   => ['email'],
        ];
    }
}
