<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\PreparesProductData;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    use PreparesProductData;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->preparedProductPayload();
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'category_id' => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'segment' => ['sometimes', 'in:human,animal'],
            'status' => ['sometimes', 'in:published,draft'],
            'image_url' => ['sometimes', 'nullable', 'string', 'max:2048'],
            'features' => ['sometimes', 'array'],
            'features.*' => ['string'],
            'order_index' => ['sometimes', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => 'A valid category is required',
        ];
    }
}
