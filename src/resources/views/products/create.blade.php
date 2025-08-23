@extends('layouts.default')

@section('title', '商品登録')

@section('content')
@include('products._form', [
'mode' => 'create',
'action' => url('/products/register'),
'httpMethod' => 'POST',
'product' => null, // create なので null
// seasons はコントローラから渡す配列例: [['key'=>'spring','label'=>'春'], ...]
])
@endsection