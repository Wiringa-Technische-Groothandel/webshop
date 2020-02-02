<?php

declare(strict_types=1);

namespace WTG\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Login request.
 *
 * @package     WTG\Auth
 * @subpackage  Requests
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "company"  => 'required',
            "username" => 'required',
            "password" => 'required',
        ];
    }
}
