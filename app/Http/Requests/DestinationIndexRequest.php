<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestinationIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|exists:categories,id',
            'district_id' => 'sometimes|exists:districts,id',
            'type' => 'sometimes|string',
            'search' => 'sometimes|string|max:255',
            'featured' => 'sometimes|boolean',
            'sort_by' => 'sometimes|in:name,created_at,entrance_fee',
            'sort_order' => 'sometimes|in:asc,desc',
            'per_page' => 'sometimes|integer|min:1|max:50'
        ];
    }
}
