<?php

namespace App\Http\Controllers;

use App\Models\Product; #Eloquentを使うため
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    // 季節の選択肢（必要に応じて DB から取得に差し替え可）
    private const SEASONS = [
        ['key' => 'spring', 'label' => '春'],
        ['key' => 'summer', 'label' => '夏'],
        ['key' => 'autumn', 'label' => '秋'],
        ['key' => 'winter', 'label' => '冬'],
    ];

    /** 一覧（検索・並び替え対応、6件ごと） */
    public function index(Request $request)
    {
        $products = $this->buildListQuery($request)->paginate(6)->withQueryString();
        return view('products.index', compact('products'));
    }

    /** 検索ページ（UIの都合で別ルートだが実処理は一覧と同じ） */
    public function search(Request $request)
    {
        $products = $this->buildListQuery($request)->paginate(6)->withQueryString();
        return view('products.index', compact('products'));
    }

    // 詳細ページ（読む用途）
    public function show(Product $product)
    {
        // 詳細画面で複数季節を表示するために eager load
        $product->load('seasons');

        return view('products.show', compact('product'));
    }

    // 編集ページ（書く用途）
    public function edit(Product $product)
    {
        // 既存の関連(seasons)をロードして、フォームで既存チェック反映
        $product->load('seasons');

        $seasons = Season::select('id', 'name')->orderBy('id')->get();

        return view('products.edit', [
            'product'  => $product,
            'seasons'  => $seasons,
        ]);
    }

    /** 登録画面表示 */
    public function create()
    {
        // DBのseasonsから id,name を取得して渡す
        $seasons = Season::select('id', 'name')->orderBy('id')->get();

        return view('products.create', [
            'seasons' => $seasons,
        ]);
    }

    /** 登録処理 */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $data['image'] = $request->file('image')->store('imgs', 'public');

        // Product本体を作成
        $product = Product::create($data);

        // seasons を pivot に保存
        $product->seasons()->sync($data['seasons']);

        return redirect('/products');
    }

    /** 更新処理 */
    public function update(UpdateProductRequest $request, int $productId)
    {
        $product = Product::findOrFail($productId);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('imgs', 'public');
        } else {
            unset($data['image']);
        }

        $product->update($data);

        // seasons を pivot 更新
        $product->seasons()->sync($data['seasons'] ?? []);

        return redirect('/products');
    }

    /** 削除処理 */
    public function destroy(int $productId)
    {
        $product = Product::findOrFail($productId);
        // 画像も削除したい場合は以下のコメントを外す
        // if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return redirect('/products');
    }

    /** 一覧/検索の共通ビルダー */
    private function buildListQuery(Request $request)
    {
        $qb = Product::query();

        // 部分一致検索（商品名）
        $kw = trim((string) $request->input('q', ''));
        if ($kw !== '') {
            $qb->where('name', 'like', "%{$kw}%");
        }

        // 並び替え（価格）
        $sort = (string) $request->input('sort', '');
        if ($sort === 'price_asc') {
            $qb->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $qb->orderBy('price', 'desc');
        } else {
            // デフォルトは新しい順（id降順）
            $qb->orderByDesc('id');
        }

        // 一覧で使う列だけ取得（任意）
        return $qb->select(['id', 'name', 'price', 'image']);
    }
}
