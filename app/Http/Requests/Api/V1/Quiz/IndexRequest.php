<?php

namespace App\Http\Requests\Api\V1\Quiz;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string|null $search
 * @property int $page
 * @property int $page_size
 */
class IndexRequest extends FormRequest
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
            'search' => 'nullable|string',
            'page' => 'required|numeric',
            'page_size' => 'nullable|numeric',
        ];
    }
}
