<?php

namespace App\Modules\Comment\Requests;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            Comment::COLUMN_NAME => 'required|string',
            'entity_id' => 'required|integer',
            'entity_table' => 'required|string',
            'parent_id' => 'nullable|integer',
            'branch_id' => 'nullable|integer',
            'blocked' => 'nullable|boolean',
            'block_reason' => 'nullable|string'
        ];
    }
}
