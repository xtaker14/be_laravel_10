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