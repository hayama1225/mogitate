<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255', 'unique:products,name'],
            'price'       => ['required', 'integer', 'min:0'],
            'image'       => ['required', 'mimes:png,jpeg'],
            'description' => ['required', 'max:120'],
            'seasons'     => ['required', 'array'],
            'seasons.*'   => ['integer', 'exists:seasons,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => '商品名を入力してください',
            'name.unique'    => 'その商品名は既に登録されています',
            'price.required' => '値段を入力してください',
            'price.integer'  => '数値で入力してください',
            'price.min'      => '0以上で入力してください',
            'image.required' => '商品画像を登録してください',
            'image.mimes'    => '「.png」または「.jpeg」形式でアップロードしてください',
            'description.required' => '商品説明を入力してください',
            'description.max'      => '120文字以内で入力してください',
            'seasons.required'     => '季節を選択してください',
            'seasons.array'        => '季節の指定が不正です',
            'seasons.*.exists'     => '存在しない季節が含まれています',
        ];
    }
}
