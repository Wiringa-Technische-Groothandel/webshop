<?php

namespace WTG\Http\Requests\Admin\Carousel;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Add slide request.
 *
 * @package     WTG\Http
 * @subpackage  Requests\Admin\Carousel
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AddSlideRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'     => ['required'],
            'caption'   => ['required'],
            'image'     => ['required', 'image']
        ];
    }
}
