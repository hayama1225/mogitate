@php
// $seasons が未提供でも安全に動くフォールバック
$seasonOptions = $seasons ?? [
['key' => 'spring', 'label' => '春'],
['key' => 'summer', 'label' => '夏'],
['key' => 'autumn', 'label' => '秋'],
['key' => 'winter', 'label' => '冬'],
];

$isEdit = ($mode ?? '') === 'edit';
// 値の初期化（old優先 → 編集時は既存値）
$val = fn($name) => old($name, $isEdit && isset($product) ? $product->{$name} : '');
// 画像の既存パス（編集時のみ）
$currentImage = $isEdit && !empty($product->image) ? asset('storage/'.$product->image) : null;
@endphp

<style>
    .form-wrap {
        max-width: 900px;
        margin: 24px auto;
        padding: 0 16px
    }

    .h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 8px 0 20px
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px
    }

    label .req {
        display: inline-block;
        margin-left: 6px;
        background: #ff4d4f;
        color: #fff;
        border-radius: 4px;
        padding: 2px 6px;
        font-size: 12px
    }

    .input,
    .select,
    .textarea {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 12px;
        background: #fff
    }

    .textarea {
        min-height: 160px;
        resize: vertical
    }

    .hint {
        font-size: 12px;
        color: #777;
        margin-top: 6px
    }

    .error {
        color: #d93025;
        font-size: 12px;
        margin-top: 6px
    }

    .radio-row {
        display: flex;
        gap: 22px;
        align-items: center
    }

    .preview {
        margin-top: 10px;
        border-radius: 8px;
        border: 1px solid #eee;
        max-width: 360px;
        overflow: hidden
    }

    .preview img {
        display: block;
        width: 100%;
        height: auto
    }

    .actions {
        display: flex;
        gap: 16px;
        justify-content: center;
        margin: 28px 0
    }

    .btn {
        display: inline-block;
        border: none;
        border-radius: 10px;
        padding: 12px 26px;
        font-weight: 700;
        box-shadow: 0 4px 16px rgba(0, 0, 0, .06);
        cursor: pointer
    }

    .btn-gray {
        background: #e5e5e5
    }

    .btn-yellow {
        background: #f3c617
    }

    .btn-danger {
        background: #ff5a5f;
        color: #fff;
        padding: 10px 14px;
        border-radius: 8px
    }

    .btn:hover {
        opacity: .95
    }

    .badge-note {
        margin-left: 8px;
        font-size: 12px;
        color: #777
    }

    .breadcrumb {
        margin: 6px 0 14px
    }

    .delete-area {
        display: flex;
        justify-content: flex-end
    }

    .is-hidden {
        display: none;
    }
</style>

<div class="form-wrap">
    <div class="breadcrumb">
        <a href="{{ url('/products') }}">商品一覧</a>
        @if($isEdit) 〉{{ $product->name }} @endif
    </div>

    <!-- <h1 class="h1">{{ $isEdit ? '商品情報の変更' : '商品登録' }}</h1> -->

    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" onsubmit="return beforeSubmit()">
        @csrf
        @if(($httpMethod ?? 'POST') !== 'POST')
        @method($httpMethod)
        @endif

        <div class="grid">
            {{-- 左：画像 --}}
            <div>
                <label>商品画像 <span class="req">必須</span></label>
                <input class="input" type="file" name="image" accept=".png,.jpeg,image/png,image/jpeg" onchange="preview(this)">
                <div class="hint">「.png」または「.jpeg」形式</div>
                @error('image') <div class="error">{{ $message }}</div> @enderror

                <div id="imgPreview" class="preview {{ $currentImage ? '' : 'is-hidden' }}">
                    <img id="imgPreviewTag" src="{{ $currentImage ?? '' }}" alt="preview">
                </div>
            </div>

            {{-- 右：テキスト群 --}}
            <div>
                {{-- 商品名 --}}
                <label>商品名 <span class="req">必須</span></label>
                <input class="input" type="text" name="name" value="{{ $val('name') }}"
                    placeholder="{{ $isEdit ? '' : '商品名を入力' }}">
                @error('name') <div class="error">{{ $message }}</div> @enderror

                {{-- 値段 --}}
                <label style="margin-top:16px;display:block;">値段 <span class="req">必須</span></label>
                <input class="input" type="text" name="price" value="{{ $val('price') }}"
                    placeholder="{{ $isEdit ? '' : '値段を入力' }}">
                @error('price') <div class="error">{{ $message }}</div> @enderror

                {{-- 季節 --}}
                <label style="margin-top:16px;display:block;">季節 <span class="req">必須</span></label>
                <div class="radio-row">
                    @php $selectedSeason = old('season', $isEdit ? $product->season ?? null : null); @endphp
                    @foreach($seasonOptions as $opt)
                    <label>
                        <input type="radio" name="season" value="{{ $opt['key'] }}"
                            {{ (string)$selectedSeason === (string)$opt['key'] ? 'checked' : '' }}>
                        {{ $opt['label'] }}
                    </label>
                    @endforeach
                </div>
                @error('season') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- 商品説明 --}}
        <div style="margin-top:22px">
            <label>商品説明 <span class="req">必須</span></label>
            <textarea class="textarea" name="description"
                placeholder="{{ $isEdit ? '' : '商品の説明を入力' }}">{{ old('description', $isEdit ? ($product->description ?? '') : '') }}</textarea>
            @error('description') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="actions">
            <a class="btn btn-gray" href="{{ url('/products') }}">戻る</a>
            <button class="btn btn-yellow" type="submit">{{ $isEdit ? '変更を保存' : '登録' }}</button>
        </div>
    </form>

    {{-- 削除（編集時のみ表示） --}}
    @if($isEdit)
    <div class="delete-area">
        <form method="POST" action="{{ url('/products/'.$product->id.'/delete') }}"
            onsubmit="return confirm('商品を削除します。よろしいですか？')">
            @csrf
            <button type="submit" class="btn-danger" title="削除">🗑</button>
        </form>
    </div>
    @endif
</div>

<script>
    function preview(input) {
        const file = input.files && input.files[0];
        if (!file) return;
        const url = URL.createObjectURL(file);
        const img = document.getElementById('imgPreviewTag');
        const box = document.getElementById('imgPreview');
        img.src = url;
        box.style.display = '';
    }

    function beforeSubmit() {
        return true;
    }
</script>