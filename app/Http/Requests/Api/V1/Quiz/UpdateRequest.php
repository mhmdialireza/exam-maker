<?php

namespace App\Http\Requests\Api\V1\Quiz;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $description
 * @property mixed $start_date
 * @property int $duration
 * @property bool $is_active
 */
class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'id' => 'required|numeric',
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date_format:Y-m-d H:i:s',
            'duration' => 'required|integer',
            'is_active' => 'required|bool',
        ];
    }
}
