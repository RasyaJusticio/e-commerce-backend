<?php

namespace App\Http\Requests\Admin\Product;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProductImageStoreRequest extends BaseFormRequest
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
            'image' => [
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
            ]
        ];
    }
}
