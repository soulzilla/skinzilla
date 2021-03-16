<?php

namespace App\Modules\Roulette\Requests;

use App\Models\Roulette;
use Illuminate\Foundation\Http\FormRequest;

class RouletteRequest extends FormRequest
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
            Roulette::COLUMN_NAME => 'required|string',
            'name_canonical' => 'required|string',
            'website' => 'required|string',
            'promo_code' => 'string',
            'promo_code_description' => 'string',
            'ref_link' => 'string',
            'logo' => 'string',
            'order' => 'integer',
            'published' => 'boolean',
            'assessment' => 'string',
            'recommendation_level' => 'integer'
        ];
    }
}
