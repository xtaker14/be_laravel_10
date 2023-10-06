@include('layouts/sections/header')
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
<div class="layout-container">
@include('layouts/sections/menu')

<!-- Layout container -->
<div class="layout-page">
@include('layouts/sections/navbar')

<!-- Content wrapper -->
<div class="content-wrapper">
<!-- Content -->
@yield('content')
<!-- / Content -->
@include('layouts/sections/footer')
<div class="content-backdrop fade"></div>
    </div>
        <!-- Content wrapper -->
        </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
        </div>
        <!-- / Layout wrapper -->
@include('layouts/sections/scripts')
@yield('scripts')
</body>
</html>