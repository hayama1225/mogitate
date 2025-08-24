@extends('layouts.default')

@section('title', '商品一覧')

@section('content')
<link rel="stylesheet" href="{{ asset('css/products-index.css') }}">

<div class="page-wrap">
    {{-- ===== 左カラム：検索 & 並び替え ===== --}}
    <aside class="sidebar">
        <h2>商品一覧</h2>

        {{-- 検索 --}}
        <form class="search-box" method="GET" action="{{ url('/products/search') }}">
            <input id="searchQuery" type="text" name="q" value="{{ request('q') }}" placeholder="商品名で検索">
            <button type="submit">検索</button>
        </form>

        {{-- 並び替え --}}
        <div class="section-title">価格順で表示</div>
        <form class="sort-form" method="GET"
            action="{{ request()->is('products/search') ? url('/products/search') : url('/products') }}">
            {{-- ★ ここは“常に” hidden を持たせる（初期値は request('q')） --}}
            <input type="hidden" name="q" id="sortHiddenQ" value="{{ request('q') }}">

            <select name="sort" onchange="return submitSortWithQuery(this)">
                <option value="" disabled {{ request('sort') ? '' : 'selected' }}>価格で並べ替え</option>
                <option value="price_desc" {{ request('sort')==='price_desc' ? 'selected' : '' }}>高い順に表示</option>
                <option value="price_asc" {{ request('sort')==='price_asc'  ? 'selected' : '' }}>低い順に表示</option>
            </select>
            <noscript><button type="submit" style="margin-top:8px;width:100%;">適用</button></noscript>
        </form>

        {{-- 並び替えのタグ表示 + クリア --}}
        @if(request('sort'))
        @php
        $label = request('sort')==='price_desc' ? '高い順に表示' : '低い順に表示';
        $resetUrl = url()->current() . ( ($qs = http_build_query(request()->except('sort','page'))) ? '?'.$qs : '' );
        @endphp
        <div class="chips">
            <a class="chip" href="{{ $resetUrl }}">{{ $label }} <span class="close">×</span></a>
        </div>
        @endif
    </aside>

    {{-- ===== 右カラム：結果一覧 ===== --}}
    <main>
        <div class="main-head">
            <a class="add-btn" href="{{ url('/products/register') }}">＋ 商品を追加</a>
        </div>

        @if(request()->is('products/search') && request()->filled('q'))
        <div class="result-title">“{{ e(request('q')) }}”の商品一覧</div>
        @endif

        @if ($products->count())
        <div class="grid">
            @each('components.product-card', $products, 'product')
        </div>

        <div class="pager">
            {{ $products->onEachSide(1)->withQueryString()->links('vendor.pagination.mogitate') }}
        </div>
        @else
        <p class="empty">該当する商品は見つかりませんでした。</p>
        <a href="{{ url('/products') }}">商品一覧へ戻る</a>
        @endif
    </main>
</div>

<script>
    function submitSortWithQuery(el) {
        var qInput = document.getElementById('searchQuery');
        var hidden = document.getElementById('sortHiddenQ');
        if (qInput && hidden) hidden.value = qInput.value || '';
        el.form.submit();
        return false;
    }
</script>
@endsection