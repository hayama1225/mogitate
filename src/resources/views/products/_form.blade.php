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

                {{-- 季節（複数選択） --}}
                <label style="margin-top:16px;display:block;">季節 <span class="req">必須</span></label>

                @php
                // Controllerから渡す: $seasons = Season::select('id','name')->orderBy('id')->get();
                // 選択状態: old() 優先 → 編集時は関連ID
                $selectedSeasons = collect(old('seasons', ($isEdit ?? false) ? $product->seasons->pluck('id')->all() : []))
                ->map(fn($v) => (string)$v) // 文字列に正規化（oldと型を合わせる）
                ->all();
                @endphp

                <div id="season-checkboxes" class="radio-row">
                    @foreach($seasons as $s)
                    <label>
                        <input type="checkbox" name="seasons[]" value="{{ (string)$s->id }}"
                            {{ in_array((string)$s->id, $selectedSeasons, true) ? 'checked' : '' }}>
                        {{ $s->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            @error('seasons') <div class="error">{{ $message }}</div> @enderror

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