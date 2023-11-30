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
        
        <!-- Helpers -->
        <script src="{{ asset('template/assets/vendor/js/helpers.js') }}"></script>
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <!-- <script src="{{ asset('template/assets/vendor/js/template-customizer.js') }}"></script> -->
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <script src="{{ asset('template/assets/js/config.js') }}"></script>
        <style>
            .invoice-print {
                display: inline-block;
            }
        </style>
        <style type="text/css" media="print">
            @media print {
                @page {size: potrait}
                .print {
                    visibility: hidden;
                }
            }
        </style>
    </head>

    <body>
    <!-- Content -->
    <div class="invoice-print p-5">
        <div class="row d-flex justify-content-between mb-4">
            <div class="col-sm-6 w-50">
                <p class="mb-1">Tanggal : {{ $master->created_date }}</p>
                <p class="mb-1">Dibuat Oleh : {{ $master->created_by }}</p>
            </div>
            <div class="col-sm-6 w-150" style="text-align: center">
                <!-- <div style="width: 50%; margin: 0 auto; text-align: left">{!! DNS1D::getBarcodeHTML($master->code, 'C128') !!}</div> -->
                <div style="width: 50%; margin: 0 auto; text-align: left"><img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($master->code, 'C128') }}" /></div>
                <h6>{{ $master->code }}</h6>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered m-0">
                <tbody>
                <tr>
                    <td rowspan="2">No</td>
                    <td rowspan="2">No Resi</td>
                    <td rowspan="2">No Referensi</td>
                    <td rowspan="2">Layanan</td>
                    <td colspan="3" style="text-align:center">Jumlah</td>
                    <td rowspan="2">Nama Penerima</td>
                    <td rowspan="2">Alamat Penerima</td>
                    <td rowspan="2">Telepon Penerima</td>
                    <td rowspan="2">Status</td>
                </tr>
                <tr>
                    <td>Berat(Kg)</td>
                    <td>Koli</td>
                    <td>COD Amount</td>
                </tr>
                @php $no = 1; @endphp
                @foreach($package as $pack)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $pack->tracking_number }}</td>
                    <td>{{ $pack->reference_number }}</td>
                    <td>{{ $pack->serviceType->name }}</td>
                    <td>{{ $pack->total_weight }}</td>
                    <td>{{ $pack->total_koli }}</td>
                    <td>{{ $pack->cod_price }}</td>
                    <td><p>{{ $pack->recipient_name}}</p></td>
                    <td><p>{{ $pack->recipient_address }}</p></td>
                    <td>{{ $pack->recipient_phone }}</td>
                    <td>{{ $pack->status->name }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="row" style="margin: 30px">
            <div class="col-12" style="text-align: center">
                <button class="btn btn-primary print" onclick="window.print()">Print </button>
            </div>
        </div>
    </div>
    
@include('layouts/sections/scripts')
    </body>
</html>