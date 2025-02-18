<?php

namespace App\Http\Requests\Admin\Product;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProductStoreRequest extends BaseFormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:512'],
            'slug' => ['nullable', 'string', 'min:3', 'max:512', 'unique:products,slug'],
            'description' => ['required', 'string', 'min:3', 'max:65535'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'categories' => ['nullable', 'array', 'min:1'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'images' => ['nullable', 'array', 'min:1', 'max:7'],
            'images.*' => [
                'required',
                File::image()
                    ->max('5mb')
                    ->dimensions(
                        Rule::dimensions()
                            ->minWidth(500)
                            ->minHeight(500)
                            ->maxWidth(2000)
                            ->maxHeight(2000)
                    ),
            ],
        ];
    }
}
