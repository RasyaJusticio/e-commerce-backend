<?php

namespace App\Http\Requests\Admin\Product;

use App\Http\Requests\BaseFormRequest;

class ProductIndexRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', 'min:1'],
            'sort_by' => ['string', 'in:id,name,price,stock,created_at,updated_at'],
            'sort_order' => ['string', 'in:asc,desc'],
            'filter' => ['array'],
            'filter.*' => ['string'],
        ];
    }
}
