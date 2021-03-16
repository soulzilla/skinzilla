<?php

namespace App\Modules\Skin\Requests;

use App\Models\Skin;
use Illuminate\Foundation\Http\FormRequest;

class SkinRequest extends FormRequest
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
            Skin::COLUMN_NAME => 'required|string',
        ];
    }
}
