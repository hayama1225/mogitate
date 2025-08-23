@php
// 画像パスの方針：
// 1) DB に 'imgs/banana.png' のように "ディレクトリ含み" で保存 → asset('storage/'.$product->image)
// 2) DB に 'banana.png' だけ保存 → asset('storage/imgs/'.$product->image)
$imgPath = str_contains($product->image, 'imgs/')
? 'storage/'.$product->image
: 'storage/imgs/'.$product->image;
@endphp

<a class="card" href="{{ route('products.show', $product) }}">
    <div class="thumb">
        <img src="{{ asset($imgPath) }}" alt="{{ $product->name }}">
    </div>
    <div class="meta">
        <div class="name">{{ $product->name }}</div>
        <div class="price">¥{{ number_format($product->price) }}</div>
    </div>
</a>