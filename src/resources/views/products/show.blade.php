@extends('layouts.default')

@section('content')
@php
// 画像パスの吸収（DBの保存方針に合わせてどちらでも動くように）
$imgPath = str_contains($product->image, 'imgs/')
? 'storage/'.$product->image
: 'storage/imgs/'.$product->image;
@endphp

<div class="detail">
    <div class="thumb">
        <img src="{{ asset($imgPath) }}" alt="{{ $product->name }}">
    </div>

    <div class="info">
        <h1>{{ $product->name }}</h1>
        <p class="price">価格：¥{{ number_format($product->price) }}</p>

        {{-- 複数季節の表示 --}}
        <p class="season">
            季節：
            @forelse($product->seasons as $season)
            {{ $season->name }}@if(!$loop->last) 、@endif
            @empty
            ー
            @endforelse
        </p>

        <p class="desc">{{ $product->description }}</p>

        <a href="{{ route('products.edit', $product) }}" class="btn primary">この商品を編集する</a>
        <a href="{{ route('products.index') }}" class="btn">一覧へ戻る</a>
    </div>
</div>
@endsection