<?php

declare(strict_types=1);

namespace WTG\Http\Requests\Checkout\Cart;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Add product to cart request.
 *
 * @package     WTG\Http
 * @subpackage  Requests\Checkout\Cart
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AddProductRequest extends FormRequest
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
            'product'  => ['required'],
            'quantity' => ['required', 'numeric', 'min:1'],
        ];
    }
}
