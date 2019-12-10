<?php

declare(strict_types=1);

namespace WTG\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Admin login request.
 *
 * @package     WTG\Http
 * @subpackage  Requests\Admin\Carousel
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
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
        return $this->user('admin') === null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required'],
            'password' => ['required'],
        ];
    }
}
