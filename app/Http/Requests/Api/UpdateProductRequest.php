<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends AbstractRequest
{

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ];
    }
}
