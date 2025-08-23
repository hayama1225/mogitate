<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* header 共通（全ページに効く） */
        .site-header {
            padding: 16px 0;
        }

        .site-header__inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
        }

        .site-header .brand,
        .site-header .brand:visited {
            /* 訪問済みでも黄色を維持 */
            font-size: 24px;
            font-weight: 800;
            color: #f4c10f;
            text-decoration: none;
            letter-spacing: .5px;
        }

        .site-header .brand:hover {
            opacity: .85;
        }

        /* === product detail === */
        .detail {
            max-width: 960px;
            margin: 32px auto;
            display: flex;
            gap: 24px;
            align-items: flex-start;
        }

        .detail .thumb {
            width: 360px;
            flex: none;
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
        }

        .detail .thumb img {
            width: 100%;
            height: auto;
            display: block;
        }

        .detail .info h1 {
            font-size: 24px;
            margin: 0 0 8px;
        }

        .detail .price {
            font-weight: 700;
            margin: 8px 0;
        }

        .detail .desc {
            line-height: 1.8;
            margin: 16px 0 24px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 9999px;
            text-decoration: none;
            border: 1px solid #ddd;
        }

        .btn.primary {
            background: #111;
            color: #fff;
            border-color: transparent;
        }

        .btn+.btn {
            margin-left: 8px;
        }
    </style>
    <title>@yield('title')</title>
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