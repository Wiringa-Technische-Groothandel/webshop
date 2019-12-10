<?php

declare(strict_types=1);

namespace WTG\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Download discount file request.
 *
 * @package     WTG\Http
 * @subpackage  Requests
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class DownloadDiscountFileRequest extends FormRequest
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
            'format' => ['required', 'in:csv,icc'],
            'receive' => ['required', 'in:download,email']
        ];
    }
}
