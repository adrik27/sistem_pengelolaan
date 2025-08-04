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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <style>
        .buy-now-wrapper {
            display: none;
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">

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

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')

                {{-- changePasswordModal --}}
                <div class="modal fade" id="changePasswordModal" tabindex="-1"
                    aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="changePasswordForm" action="{{ url('change-password') }}" method="post">
                                    @csrf

                                    <div class="mb-3 position-relative">
                                        <label for="password" class="form-label">New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password"
                                                required autocomplete="off">
                                            <span class="input-group-text toggle-password" data-target="#password"
                                                style="cursor: pointer;">
                                                <i class="fa fa-eye-slash"></i>
                                            </span>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 position-relative">
                                        <label for="password_confirmation" class="form-label">New Password
                                            Confirmation</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" required autocomplete="off">
                                            <span class="input-group-text toggle-password"
                                                data-target="#password_confirmation" style="cursor: pointer;">
                                                <i class="fa fa-eye-slash"></i>
                                            </span>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <button type="submit" form="changePasswordForm" class="btn btn-primary"
                                        onclick="Updateform(this)">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#changePasswordForm').on('submit', function(e) {
                const password = $('#password').val().trim();
                const confirmPassword = $('#password_confirmation').val().trim();

                // Reset error
                $('.is-invalid').removeClass('is-invalid');

                if (password === '') {
                    $('#password').addClass('is-invalid');

                    Swal.fire({
                        title: "Peringatan!",
                        text: "Password tidak boleh kosong!",
                        icon: "warning",
                        cancelButtonColor: "#3085d6",
                        cancelButtonText: "Batal"
                    })

                    e.preventDefault();
                    return;
                }

                if (confirmPassword === '') {
                    $('#password_confirmation').addClass('is-invalid');

                    Swal.fire({
                        title: "Peringatan!",
                        text: "Konfirmasi password tidak boleh kosong!",
                        icon: "warning",
                        cancelButtonColor: "#3085d6",
                        cancelButtonText: "Batal"
                    })

                    e.preventDefault();
                    return;
                }

                if (password !== confirmPassword) {
                    $('#password_confirmation').addClass('is-invalid');
                    Swal.fire({
                        title: "Peringatan!",
                        text: "Kombinasi password tidak cocok!",
                        icon: "warning",
                        cancelButtonColor: "#3085d6",
                        cancelButtonText: "Batal"
                    })
                    e.preventDefault();
                    return;
                }

                // Lolos validasi, form akan tetap dikirim
            });

            // Toggle password visibility
            $('.toggle-password').on('click', function() {
                const targetInput = $($(this).data('target'));
                const icon = $(this).find('i');

                if (targetInput.attr('type') === 'password') {
                    targetInput.attr('type', 'text');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    targetInput.attr('type', 'password');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });

        });

        function Updateform(button) {
            event.preventDefault(); // cegah klik default tombol

            const form = button.closest('form');

            Swal.fire({
                title: "Apakah Anda yakin merubah password?",
                text: "Data yang dirubah tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, update!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit form jika user konfirmasi
                }
            });
        }

        function logoutForm(button) {
            event.preventDefault(); // cegah klik default tombol

            const form = button.closest('form');

            Swal.fire({
                title: "Apakah Anda yakin ingin log out?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, log out!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit form jika user konfirmasi
                }
            });
        }
    </script>
    @yield('js')
</body>

</html>
