<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistem Pengelolaan Stok dan Keuangan">
    <meta name="author" content="MT">

    <title>Log in - Sistem Pengelolaan Stok dan Keuangan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <!-- core:css -->
    <link rel="stylesheet" href="{{ url('template/assets/vendors/core/core.css') }}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ url('template/assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ url('template/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ url('template/assets/css/demo1/style.min.css') }}">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="{{ url('template/assets/images/favicon.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        .buy-now-wrapper {
            display: none;
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">

                <div class="row w-100 mx-0 auth-page">
                    <div class="col-md-8 col-xl-6 mx-auto">
                        <div class="row">
                            <div class="col-12">
                                @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @endif
                                @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="card">
                            <div class="row">
                                <div class="col-md-4 pe-md-0">
                                    <div class="auth-side-wrapper">

                                    </div>
                                </div>
                                <div class="col-md-8 ps-md-0">
                                    <div class="auth-form-wrapper px-4 py-5">
                                        <a href="#" class="noble-ui-logo d-block mb-1">STOKEU</a>
                                        <h6 class="text-muted fw-normal mb-4">Sistem Pengelolaan Stok dan Keuangan.</h6>
                                        <form class="forms-sample" method="POST" action="{{ url('/login') }}"
                                            enctype="multipart/form-data">
                                            @csrf

                                            <div class="mb-3">
                                                <label for="userEmail" class="form-label">Email address</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="userEmail" name="email" placeholder="Email" autocomplete="off"
                                                    autofocus required>
                                                @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="mb-3 position-relative">
                                                <label for="userPassword" class="form-label">Password</label>
                                                <div class="input-group">
                                                    <input type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        id="userPassword" name="password"
                                                        autocomplete="current-password" placeholder="Password" required>
                                                    <span class="input-group-text" id="togglePassword"
                                                        style="cursor: pointer;">
                                                        <i class="fa fa-solid fa-eye" id="togglePasswordIcon"></i>
                                                    </span>
                                                </div>

                                                @error('password')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div>
                                                <button type="submit"
                                                    class="btn btn-primary me-2 mb-2 mb-md-0 text-white">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- core:js -->
    <script src="{{ url('template/assets/vendors/core/core.js') }}"></script>
    <!-- endinject -->

    <!-- inject:js -->
    <script src="{{ url('template/assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ url('template/assets/js/template.js') }}"></script>
    <!-- endinject -->

    <script>
        const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('userPassword');
            const icon = document.getElementById('togglePasswordIcon');
        
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
        
                // Ganti ikon mata / mata dicoret
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
    </script>
</body>

</html>