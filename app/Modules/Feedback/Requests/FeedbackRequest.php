<?php

namespace App\Modules\Feedback\Requests;

use App\Models\Feedback;
use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
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
            Feedback::COLUMN_NAME => 'required|string',
        ];
    }
}
