<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-wide customizer-hide"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{ asset('template/assets') }}"
    data-template="vertical-menu-template">
    <head>
        <meta charset="utf-8" />
        <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <title>Transport Management System | Waizly</title>

        <meta name="description" content="" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('template/assets/img/favicon/favicon.ico') }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

        <!-- Icons -->
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/fonts/fontawesome.css') }}" />
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/fonts/tabler-icons.css') }}" />
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/fonts/flag-icons.css') }}" />

        <!-- Core CSS -->
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css"/>
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css"/>
        <link rel="stylesheet" href="{{ asset('template/assets/css/demo.css') }}" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/node-waves/node-waves.css') }}" />
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/typeahead-js/typeahead.css') }}" />
        <!-- Vendor -->
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
        <!-- Page CSS -->
        <!-- Page -->
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/pages/page-auth.css') }}" />

        <!-- Helpers -->
        <script src="{{ asset('template/assets/vendor/js/helpers.js') }}"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <!-- <script src="{{ asset('template/assets/vendor/js/template-customizer.js') }}"></script> -->
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <script src="{{ asset('template/assets/js/config.js') }}"></script>
    </head>

    <body>
        <!-- Content -->

        <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 p-0">
            <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                <img
                src="{{ asset('template/assets/img/illustrations/auth-login-illustration-light.png') }}"
                alt="auth-login-cover"
                class="img-fluid my-5 auth-illustration"
                data-app-light-img="{{ asset('template/illustrations/auth-login-illustration-light.png') }}"
                data-app-dark-img="{{ asset('template/illustrations/auth-login-illustration-dark.png') }}" />

                <img
                src="{{ asset('template/assets/img/illustrations/bg-shape-image-light.png') }}"
                alt="auth-login-cover"
                class="platform-bg"
                data-app-light-img="{{ asset('template/illustrations/bg-shape-image-light.png') }}"
                data-app-dark-img="{{ asset('template/illustrations/bg-shape-image-dark.png') }}" />
            </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
            <div class="w-px-400 mx-auto">
                <!-- Logo -->
                <div class="app-brand mb-4">
                <a href="index.html" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo">
                    <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                        fill="#7367F0" />
                        <path
                        opacity="0.06"
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                        fill="#161616" />
                        <path
                        opacity="0.06"
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                        fill="#161616" />
                        <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                        fill="#7367F0" />
                    </svg>
                    </span>
                </a>
                </div>
                <!-- /Logo -->
                <h3 class="mb-1">Welcome to Waizly Transport Management System👋</h3>
                <p class="mb-4">Please sign-in to your account and start the adventure</p>

                <form id="formAuthentication" class="mb-3" action="{{ route('login-validation') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" autofocus>
                    </div>
                    <div class="mb-3 form-password-toggle">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">Password</label>
                        </div>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                        </div>
                    </div>
                    <button class="btn btn-primary d-grid w-100">Sign in</button>
                </form>
                <!-- Alert -->
                @if($message = Session::get('failed'))
                <div class="alert alert-danger alert-dismissible d-flex align-items-baseline" role="alert">
                    <span class="alert-icon alert-icon-lg text-danger me-2">
                        <i class="ti ti-user ti-sm"></i>
                    </span>
                    <div class="d-flex flex-column ps-1">
                        <h5 class="alert-heading mb-2">Login Failed!</h5>
                        <p class="mb-0">{{ $message }}</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                </div>
                @endif
                <!-- Alert -->
            </div>
            </div>
            <!-- /Login -->
        </div>
        </div>

        <!-- / Content -->

        <!-- Core JS -->
        <!-- build:js assets/vendor/js/core.js -->

        <script src="{{ asset('template/assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/libs/hammer/hammer.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/libs/i18n/i18n.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/js/menu.js') }}"></script>

        <!-- endbuild -->

        <!-- Vendors JS -->
        <script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
        <script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>

        <!-- Main JS -->
        <!-- <script src="{{ asset('template/assets/js/main.js') }}"></script> -->

        <!-- Page JS -->
        <script src="{{ asset('template/assets/js/pages-auth.js') }}"></script>
    </body>
</html>