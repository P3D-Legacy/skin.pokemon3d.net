<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') - Pok&eacute;mon 3D: Skin</title>

        <link rel="shortcut icon" href="http://pokemon3d.net/files/images/favicon.png">

        <script type="text/javascript" src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
        <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

        <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

        <style>
            html,
            body {
                height: 100%;
            }

            body {
                display: flex;
                align-items: center;
                padding-top: 40px;
                padding-bottom: 40px;
                background-color: #f5f5f5;
            }
        </style>
    </head>
<body class="text-center">
    <main class="form-signin">

        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-frown mr-2" aria-hidden="true"></i> {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2" aria-hidden="true"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info mr-2" aria-hidden="true"></i> {{ session('info') }}
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation mr-2" aria-hidden="true"></i> {{ session('warning') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-warning mt-5 mb-0" role="alert">
                <strong><i class="fas fa-exclamation-triangle mr-2" aria-hidden="true"></i> Input Warning</strong>
                <ul class="m-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </main>
</body>
</html>