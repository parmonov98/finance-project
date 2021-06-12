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

    <script src="{{ asset('https://unpkg.com/@coreui/coreui/dist/js/coreui.bundle.min.js') }}"></script>

    <script src="js/main.js"></script>
    @livewireScripts
    @stack('scripts')

    <script src="{{ asset('https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js') }}"></script>
    <script src="{{ asset('https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/masking-input.js') }}" data-autoinit="true"></script>


</body>

</html>