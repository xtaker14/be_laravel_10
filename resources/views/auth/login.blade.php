<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-wide customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('template/assets/') }}"
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Template Laravel 10 | Login</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('template/assets/img/website/www-icon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/fonts/tabler-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css"/>
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css"/>
    <link rel="stylesheet" href="{{ asset('template/assets/css/demo.css') }}" />

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

    <style>
        .logo-bg-login{
            width: 200px;
            position: absolute;
            top: 25px;
            left: 25px;
        }
        .authentication-wrapper.authentication-cover .authentication-inner .auth-cover-bg .auth-illustration{
            width: 400px !important;
            height: 400px;
        }
        .welcome-login{
            color:#4C4F54;
            font-size: 24px;
            font-style: normal;
            font-weight: 500;
            line-height: 32px; 
            letter-spacing: 0.25px;
        }
        .shadow1{
          -webkit-box-shadow: 0 10px 6px -6px #777;
            -moz-box-shadow: 0 10px 6px -6px #777;
                  box-shadow: 0 10px 6px -6px #777;
        }
        .alert-heading{
          color: #FF6E5D;
          font-style: normal;
          font-weight: 700;
          line-height: 20px;
        }
    </style>
  </head>

  <body>
    <!-- Content -->

    <div class="authentication-wrapper authentication-cover authentication-bg">
      <div class="authentication-inner row">
        <!-- /Left Text -->
        <div class="d-none d-lg-flex col-lg-7 p-0 auth-cover-bg-color">
            <img
              src="{{ asset('template/assets/img/website/www-icon.png') }}"
              alt="logo-login" class="logo-bg-login"/>
          <div class="auth-cover-bg d-flex justify-content-center align-items-center">
            <img
              src="{{ asset('assets/image/bg-login.svg') }}"
              alt="auth-login-cover"
              class="my-5 auth-illustration" />
          </div>
        </div>
        <!-- /Left Text -->

        <!-- Login -->
        <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
          <div class="w-px-400 mx-auto">
            <h3 class="mb-1 welcome-login">Welcome to Template Laravel 10! 👋🏻</h3>
            <p class="mb-4">Please sign-in to your account and start the adventure</p>

            <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                @csrf
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                  type="text"
                  class="form-control @error('username') is-invalid @enderror"
                  id="username"
                  name="username"
                  placeholder="Enter your username"
                  value="{{ old('username') }}"
                  autofocus />
                  @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
              </div>
              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">Password</label>
                  {{-- <a href="auth-forgot-password-cover.html">
                    <small>Forgot Password?</small>
                  </a> --}}
                </div>
                <div class="input-group input-group-merge">
                  <input
                    type="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password" />
                  <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                </div>
              </div>
              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember-me" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}/>
                  <label class="form-check-label" for="remember-me"> Remember Me </label>
                </div>
              </div>
              <button class="btn btn-primary d-grid w-100">Login</button>
            </form>
            @if ($errors->has('username'))
            <div class="alert alert-danger alert-dismissible" role="alert">
              <h5 class="alert-heading mb-2">Login Failed!</h5>
              <p class="mb-0">
                {{ $errors->first('username') }} Please double-check and try again.
              </p>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if (session()->has('failed'))
            <div class="alert alert-danger alert-dismissible" role="alert">
              <h5 class="alert-heading mb-2">Login Failed!</h5>
              <p class="mb-0">
                {{ session()->get('failed') }}
              </p>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
          </div>
        </div>
        <!-- /Login -->
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{ asset('template/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('template/assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('template/assets/js/pages-auth.js') }}"></script>
  </body>
</html>
