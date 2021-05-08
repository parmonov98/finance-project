<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CoreUI CSS -->
    <link rel="stylesheet" href="https://unpkg.com/@coreui/coreui/dist/css/coreui.min.css" crossorigin="anonymous">

    <title>CoreUI</title>
</head>

<body class="c-app">
    <!-- Sidebar -->
    @include('partials.sidebar')
    <!-- End sidebar -->
    


    <div class="c-wrapper c-fixed-components" style="max-height: 100%;">
        <!-- Header -->
        @include('partials.header')
        <!-- End header -->

        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="fade-in">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe, ab dignissimos assumenda sit dolorum eveniet odit sunt. Praesentium dolore odio iste, delectus ut aperiam cum repellat repudiandae totam explicabo amet.
                    </div>
                </div>
             </main>
         </div>           
        <!-- Footer -->
        @include('partials.footer')
        <!-- End footer -->
    </div>



    <!-- Optional JavaScript -->
    <!-- Popper.js first, then CoreUI JS -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/@coreui/coreui/dist/js/coreui.min.js"></script>
</body>

</html>