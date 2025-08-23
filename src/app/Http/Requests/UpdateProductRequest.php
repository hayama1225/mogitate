<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\ProductRules;

class UpdateProductRequest extends FormRequest
{
    use ProductRules;

    public function authorize(): bool
    {
        return true; #true
    }

    public function rules(): array
    {
        $r = $this->rulesCore();
        $r['image'] = ['nullable', 'mimes:png,jpeg']; #画像は更新時は任意アップロード(必須を nullable に置換)
        return $r;
    }

    public function messages(): array
    {
        return $this->messagesCore();
    }
}
