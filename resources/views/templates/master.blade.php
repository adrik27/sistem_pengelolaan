<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistem Pengelolaan Stok dan Keuangan">
    <meta name="author" content="MT">

    <title>Pesan Sedia</title>

    <!-- Fonts -->
    {{--
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""> --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <!-- End fonts -->

    <!-- core:css -->
    <link rel="stylesheet" href="{{ url('template/assets/vendors/core/core.css') }}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ url('template/assets/vendors/flatpickr/flatpickr.min.css') }}">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ url('template/assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ url('template/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ url('template/assets/css/demo1/style.min.css') }}">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="{{ url('template/assets/images/favicon.png') }}">

    {{-- datatable --}}
    <link href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.min.css">
    {{-- end datatable --}}


    {{-- select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- end select2 --}}


    <style>
        .buy-now-wrapper {
            display: none;
        }
    </style>

    @yield('css')
</head>

<body>
    <div class="main-wrapper">

        <!-- partial:partials/_sidebar.html -->
        @include('templates.layouts.partials.sidebar')
        <!-- partial -->

        <div class="page-wrapper">

            <!-- partial:partials/_navbar.html -->
            @include('templates.layouts.partials.navbar')
            <!-- partial -->

            <div class="page-content">

                @yield('content')

            </div>

            <!-- partial:partials/_footer.html -->
            @include('templates.layouts.partials.footer')
            <!-- partial -->

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- core:js -->
    <script src="{{ url('template/assets/vendors/core/core.js') }}"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <script src="{{ url('template/assets/vendors/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ url('template/assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="{{ url('template/assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ url('template/assets/js/template.js') }}"></script>
    <!-- endinject -->

    <!-- Custom js for this page -->
    <script src="{{ url('template/assets/js/dashboard-light.js') }}"></script>
    <!-- End custom js for this page -->

    {{-- Datatable --}}
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.min.js"></script>
    {{-- End Datatable --}}

    {{-- select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- end select2 --}}

    @yield('js')
</body>

</html>