<?php

namespace App\Http\Requests\Traits;

trait ProductRules
{
    protected function rulesCore(): array
    {
        return [
            'name'        => ['required'],
            'price'       => ['required', 'integer', 'between:0,10000'],
            // season はラジオ1つ想定。値の集合が決まっているなら in: を追加
            // 例) 'season' => ['required', 'in:spring,summer,autumn,winter'],
            'season'      => ['required'],
            'description' => ['required', 'max:120'],
            // 作成時は必須。更新時は Update 側で nullable に上書きします
            'image'       => ['required', 'mimes:png,jpeg'],
        ];
    }

    protected function messagesCore(): array
    {
        return [
            'name.required'        => '商品名を入力してください',
            'price.required'       => '値段を入力してください',
            'price.integer'        => '数値で入力してください',
            'price.between'        => '0~10000円以内で入力してください',
            'season.required'      => '季節を選択してください',
            'description.required' => '商品説明を入力してください',
            'description.max'      => '120文字以内で入力してください',
            'image.required'       => '商品画像を登録してください',
            'image.mimes'          => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }
}
