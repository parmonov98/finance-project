<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @livewireStyles
    @stack('css')
    <style>
        th {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            z-index: 2;
            background-color: white;
            white-space: nowrap;
        }

        
        .span-error{
            color: #c22e00  ;
            font-size: 12px;
        }
    </style>

    <title>Finance App</title>
</head>

<body class="c-app">
    @include('partials.sidebar')
    <div class="c-wrapper c-fixed-components" style="max-height: 100%;">
        @include('partials.header')
        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="fade-in">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
        @include('partials.footer')
    </div>

    <script src="js/main.js"></script>
    @livewireScripts
    @stack('scripts')



</body>

</html>