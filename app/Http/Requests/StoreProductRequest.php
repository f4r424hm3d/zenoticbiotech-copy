<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\PreparesProductData;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    use PreparesProductData;

    public function authorize(): bool
    {
        return true; // route is already guarded by auth:sanctum + admin
    }

    protected function prepareForValidation(): void
    {
        $this->preparedProductPayload();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'segment' => ['nullable', 'in:human,animal'],
            'status' => ['nullable', 'in:published,draft'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string'],
            'order_index' => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'A valid category is required',
            'category_id.exists' => 'A valid category is required',
        ];
    }
}
