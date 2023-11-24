<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{ asset('template/assets') }}"
    data-template="vertical-menu-template">
    <head>
        <meta charset="utf-8" />
        <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <title>{{ $siteOrganization->organizationdetail->application_name == "" ? 'Transport Management System' : $siteOrganization->organizationdetail->application_name }} | @yield('title-page', 'Waizly')</title>

        <meta name="description" content="" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('template/assets/img/website/dethix-ico.png') }}" />

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
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{ asset('template/assets/css/demo.css') }}" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/flatpickr/flatpickr.css') }}" />
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/pickr/pickr-themes.css') }}" />
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/select2/select2.css') }}" />
        <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/toastr/toastr.css') }}" />
        <!-- Page CSS -->

        <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/pages/app-logistics-dashboard.css') }}" />

        @yield('styles')

        <style>
            .bg-label-green {
                background-color: #dff7e9 !important;
                color: #28c76f !important;
            }
            
            .card-custom{
                padding: 24px;
                margin-bottom: 30px;
            }
            .card-custom .card-header{
                padding: 0;
                margin-bottom: 30px;
            }
            .card-custom .card-header h5{
                font-size: 18px;
                font-style: normal;
                font-weight: 700;
                letter-spacing: 0.25px; 
                margin: 0px 8px 0px 0px;
            }
            .card-custom .input-search-datatable{
                margin-bottom: 30px;
            }
            .card-custom .button-area-datatable{
                margin-bottom: 30px;
            }
            .card-custom .button-area-datatable .btn-primary{
                border-radius: 6px;
                background: #203864;
                padding: 9px 24px;
                align-items: center;
                gap: 8px; 
                font-size: 16px;
                font-style: normal;
                font-weight: 400; 
            }
            .card-custom .button-area-datatable .btn-outline-secondary{
                padding: 9px 24px;
                align-items: center;
                gap: 8px;  
                color: #203864;
                font-size: 16px;
                font-style: normal;
                font-weight: 400; 
                border-color: transparent !important;
            }
            #DataTableServeSide_wrapper .dataTables_filter{
                display: none !important;
            }
            .table-custom-default{
                border: 1px solid#EBE9F1;
            }
            .table-custom-default thead{
                background-color: #E2EAF4;
            }
            .table-custom-default thead th{
                color:#4C4F54;
                font-size: 12px;
                font-style: normal;
                font-weight: 700;
                text-transform: uppercase;
            }
            .table-custom-default tbody td{
                color: #4C4F54;
                font-size: 14px;
                font-style: normal;
                font-weight: 400; 
            }
            .table-custom-default tbody td .badge{
                font-size: 12px;
                font-style: normal;
                font-weight: 700; 
            }
            .table-custom-default tbody td .btn-warning{
                font-size: 16px;
                font-style: normal;
                font-weight: 400; 
                padding: 9px 24px;
                align-items: center;
                gap: 8px; 
                border-radius: 6px;
                background: #FFB000; 
            }
        </style>

        <!-- Helpers -->
        <script src="{{ asset('template/assets/vendor/js/helpers.js') }}"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <!-- <script src="{{ asset('template/assets/vendor/js/template-customizer.js') }}"></script> -->
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <script src="{{ asset('template/assets/js/config.js') }}"></script>
    </head>

    <body>