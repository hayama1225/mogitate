<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Product;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // ルートパラメータから「自分のID」を安全に取得（{product} or {productId} どちらでも対応）
        $id = $this->route('product');
        if ($id instanceof Product) {
            $id = $id->id;
        } elseif (!$id) {
            $id = $this->route('productId');
        }

        return [
            // ★ 自分自身は重複OK、他の商品と重複はNG
            'name'        => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($id)],
            'price'       => ['required', 'integer', 'min:0'],
            // ★ 更新時の画像は任意
            'image'       => ['nullable', 'mimes:png,jpeg'],
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
            'image.mimes'    => '「.png」または「.jpeg」形式でアップロードしてください',
            'description.required' => '商品説明を入力してください',
            'description.max'      => '120文字以内で入力してください',
            'seasons.required'     => '季節を選択してください',
            'seasons.array'        => '季節の指定が不正です',
            'seasons.*.exists'     => '存在しない季節が含まれています',
        ];
    }
}
