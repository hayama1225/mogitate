@extends('layouts.default')
@section('title', $product->name . ' の編集')

@section('content')
@include('products._form', [
'mode' => 'edit',
'action' => url('/products/'.$product->id.'/update'),
'httpMethod' => 'POST', // 仕様ルートに合わせて POST。PUT にするなら @method('PUT') を _form 内で分岐可
'product' => $product,
])
@endsection