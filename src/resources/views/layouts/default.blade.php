<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <header class="site-header">
        <div class="site-header__inner">
            <a class="brand" href="{{ route('products.index') }}">mogitate</a>
        </div>
    </header>

    <h1>@yield('catalogue')</h1>
    @yield('content')
</body>

</html>