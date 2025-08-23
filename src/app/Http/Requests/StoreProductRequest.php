<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\ProductRules;

class StoreProductRequest extends FormRequest
{
    use ProductRules;

    public function authorize(): bool
    {
        return true; #true
    }

    public function rules(): array
    {
        return $this->rulesCore();
    }

    public function messages(): array
    {
        return $this->messagesCore();
    }
}
