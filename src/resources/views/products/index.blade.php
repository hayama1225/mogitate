@extends('layouts.default')

@section('title', '商品一覧')

@section('content')
<style>
    /* ===== Layout ===== */
    .page-wrap {
        position: relative;
        max-width: 1200px;
        margin: 32px auto;
        padding: 0 16px;
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 28px;
    }

    /* ★ 商品一覧と横並びにするツールバー（右カラムの先頭に置く） */
    .main-head {
        display: flex;
        justify-content: flex-end;
        /* 右寄せ */
        align-items: center;
        margin: 6px 0 14px;
    }

    .sidebar h2 {
        font-size: 24px;
        margin: 8px 0 16px;
    }

    /* ★ 追加ボタン（固定配置をやめる） */
    .add-btn {
        display: inline-flex;
        align-items: center;
        background: #FFC400;
        color: #3a3a3a;
        font-weight: 700;
        border-radius: 8px;
        padding: 10px 16px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
        text-decoration: none;
    }

    .add-btn:hover {
        opacity: .9;
        text-decoration: none;
    }

    /* ===== Search ===== */
    .search-box input[type="text"] {
        width: 100%;
        height: 38px;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 0 12px;
        background: #fff;
    }

    .search-box button {
        width: 100%;
        margin-top: 10px;
        height: 38px;
        border-radius: 8px;
        border: none;
        background: #f3c617;
        font-weight: 700;
        box-shadow: 0 3px 10px rgba(0, 0, 0, .06);
    }

    .section-title {
        font-size: 14px;
        color: #555;
        margin: 18px 0 6px
    }

    /* ===== Sort ===== */
    .sort-form select {
        width: 100%;
        height: 36px;
        border: 1px solid #e4e4e4;
        border-radius: 8px;
        background: #fff;
        padding: 0 10px;
    }

    .chips {
        margin-top: 10px;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff9e3;
        border: 1px solid #ffe08a;
        color: #5c4a00;
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 12px;
    }

    .chip .close {
        display: inline-block;
        padding: 0 4px;
        border-radius: 50%;
        background: #ffcf5a;
        color: #fff;
        line-height: 1
    }

    .chip:hover {
        text-decoration: none;
        opacity: .9
    }

    /* ===== Grid & Card ===== */
    .result-title {
        font-size: 22px;
        margin: 0 0 10px
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 22px;
    }

    .card {
        display: block;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 6px 20px rgba(0, 0, 0, .06);
        overflow: hidden;
        transition: transform .08s ease;
    }

    .card:hover {
        transform: translateY(-2px)
    }

    .thumb {
        height: 210px;
        background: #fafafa;
        overflow: hidden
    }

    .thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block
    }

    .meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 14px;
        border-top: 1px solid #f3f3f3
    }

    .name {
        font-size: 16px;
    }

    .price {
        font-size: 16px;
        font-weight: 700;
        color: #5a5a5a
    }

    /* ===== Empty & Pager ===== */
    .empty {
        margin: 16px 0 8px;
        color: #888
    }

    /* ===== Pager（小さく横並びに）===== */
    .paginator {
        margin-top: 16px;
    }

    .paginator ul {
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .paginator .page {
        min-width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 6px;
        border: 1px solid #e5e7eb;
        border-radius: 9999px;
        font-size: 14px;
        line-height: 1;
        background: #fff;
    }

    .paginator .page a {
        color: inherit;
        text-decoration: none;
    }

    .paginator .page.is-current {
        background: #f4c10f;
        color: #fff;
        border-color: transparent;
        font-weight: 700;
    }

    .paginator .page.disabled {
        opacity: .4;
        pointer-events: none;
    }

    .paginator .page:hover a {
        text-decoration: underline;
    }

    .pager {
        display: flex;
        justify-content: center;
        margin: 24px 0
    }

    /* Small screen */
    @media (max-width: 900px) {
        .page-wrap {
            grid-template-columns: 1fr
        }

        .grid {
            grid-template-columns: repeat(2, minmax(0, 1fr))
        }
    }

    @media (max-width: 560px) {
        .grid {
            grid-template-columns: 1fr
        }
    }
</style>

<div class="page-wrap">
    {{-- ===== 左カラム：検索 & 並び替え ===== --}}
    <aside class="sidebar">
        <h2>商品一覧</h2>

        {{-- 検索 --}}
        <form class="search-box" method="GET" action="{{ url('/products/search') }}">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="商品名で検索">
            <button type="submit">検索</button>
        </form>

        {{-- 並び替え --}}
        <div class="section-title">価格順で表示</div>
        <form class="sort-form" method="GET"
            action="{{ request()->is('products/search') ? url('/products/search') : url('/products') }}">
            {{-- 検索結果に対する並び替えでも検索語を維持 --}}
            @if(request('q') !== null)
            <input type="hidden" name="q" value="{{ request('q') }}">
            @endif

            <select name="sort" onchange="this.form.submit()">
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
@endsection