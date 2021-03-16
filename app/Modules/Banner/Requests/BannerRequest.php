<?php

namespace App\Modules\Banner\Requests;

use App\Models\Banner;
use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            Banner::COLUMN_NAME => 'required|string',
            'content' => 'required|string',
            'image' => 'string',
            'published' => 'boolean',
            'url' => 'required|string',
            'blank' => 'boolean'
        ];
    }
}
