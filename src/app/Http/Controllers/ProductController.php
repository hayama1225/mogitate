<?php

namespace App\Http\Controllers;

use App\Models\Product; #Eloquentを使うため
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
    public function show(\App\Models\Product $product)
    {
        return view('products.show', compact('product'));
    }

    // 編集ページ（書く用途）
    public function edit(\App\Models\Product $product)
    {
        return view('products.edit', [
            'product' => $product,
            'seasons' => self::SEASONS, // 既存の定数をそのまま利用
        ]);
    }

    /** 登録画面表示 */
    public function create()
    {
        return view('products.create', ['seasons' => self::SEASONS]);
    }

    /** 登録処理 */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        // 画像保存: storage/app/public/imgs に保存 → DB には相対パスを保存
        $data['image'] = $request->file('image')->store('imgs', 'public'); #'imgs/xxx.png' がDBに入る

        Product::create([
            'name'        => $data['name'],
            'price'       => $data['price'],
            'season'      => $data['season'] ?? null,
            'description' => $data['description'],
            'image'       => $data['image'],
        ]);

        return redirect('/products');
    }

    /** 更新処理 */
    public function update(UpdateProductRequest $request, int $productId)
    {
        $product = Product::findOrFail($productId);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('imgs', 'public');
            // 既存画像も消したい場合は以下のコメントを外す
            // if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $newPath;
        } else {
            unset($data['image']); // 未選択時は上書きしない
        }

        $product->update($data);
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
