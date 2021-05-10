<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CoreUI CSS -->
    <link rel="stylesheet" href="https://unpkg.com/@coreui/coreui/dist/css/coreui.min.css" crossorigin="anonymous">
    @stack('css')

    <title>CoreUI</title>
</head>



<body class="c-app">
    @include('partials.sidebar')
    <div class="c-wrapper c-fixed-components">
        @include('partials.header')
        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="fade-in">

                    </div>
                </div>
            </main>
            @include('partials.footer')
        </div>
    </div>

    <script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
    <!--[if IE]><!-->
    <script src="vendors/@coreui/icons/js/svgxuse.min.js"></script>
    <!--<![endif]-->

    <script src="vendors/@coreui/chartjs/js/coreui-chartjs.bundle.js"></script>
    <script src="vendors/@coreui/utils/js/coreui-utils.js"></script>
    <script src="js/main.js"></script>
    @stack('scripts')

</body>

</html>