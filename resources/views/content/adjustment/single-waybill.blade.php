@extends('layouts.main')

@section('styles')
<link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
<style>
    html:not([dir="rtl"]) .datePickerGroup .form-control:not(:first-child){
        border-left: 1px solid #dbdade;
        border-top-left-radius: 0.375rem !important;
        border-bottom-left-radius: 0.375rem !important;
        padding: 0px 0.75rem !important;
        height: 38px;
    }
    html:not([dir="rtl"]) .datePickerGroup2 .form-control:not(:first-child){
        border-left: 1px solid #dbdade;
        border-top-left-radius: 0.375rem !important;
        border-bottom-left-radius: 0.375rem !important;
        padding: 0px 0.75rem !important;
        height: 38px;
    }
    .form-label{
        color: #4C4F54;
        font-size: 12px;
        font-style: normal;
        font-weight: 700;
        line-height: 16px;
    }
    .filter-card .label-filter-card{
        font-size: 14px;
        font-style: normal;
        font-weight: 700; 
        margin: 0px 16px;
    }
    .title-adjustment{
        color:#4C4F54;
        font-size: 18px;
        font-style: normal;
        font-weight: 700;
        line-height: 24px;
        margin-bottom: 30px;
    }
    .form-adjustment{
        margin-bottom: 30px;
    }
    .search-data{
        margin-bottom: 30px;
    }
    .search-data strong{
        color:#4C4F54;
        font-size: 14px;
        font-style: normal;
        font-weight: 700;
        line-height: 25px;
    }
    .search-data input{
        margin-left: 16px;
        margin-right: 24px;
    }
    .detail-adjustment{
        color:#4C4F54;
        font-size: 14px;
        font-style: normal;
        font-weight: 700;
        line-height: 20px;
        margin-bottom: 30px;
    }
    .history-adjustment{
        color:#4C4F54;
        font-size: 18px;
        font-style: normal;
        font-weight: 700;
        line-height: 24px;
        letter-spacing: 0.25px;
        margin-bottom: 30px;
    }
    .table-history thead{
        background: #E2EAF4;
        height: 40px;
    }
    .table:not(.table-dark) thead:not(.table-dark) th{
        color: #4C4F54;
        font-size: 12px;
        font-style: normal;
        font-weight: 700; 
    }
    .table-history tbody td{
        color: #4C4F54; 
        font-size: 14px;
        font-style: normal;
        font-weight: 400; 
        padding: 20px 16px 17px 16px;
    }
    .table-history tbody td .badge{
        font-size: 12px;
        font-style: normal;
        font-weight: 700; 
    }
    .table-history tbody td .btn{
        border-radius: 6px;
        padding: 9px 24px;
        align-items: center; 
        font-size: 16px;
        font-weight: 400; 
    }
    .table-history tbody td .btn-warning{
        background: #FFB000; 
    }
    .table-history tbody td .btn i{
        margin-right: 8px;
    }
    .detail-waybill{
        margin-bottom: 30px;
    }
    .detail-waybill strong{
        color:#4C4F54;
        font-size: 14px;
        font-style: normal;
        font-weight: 700;
        line-height: 25px;
        margin-right: 5px;
    }
    .detail-waybill span{
        color:#4C4F54;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 20px;
    }
    .detail-waybill .badge{
        border-radius: 4px;
        background: rgba(255, 159, 67, 0.12);
        width: 75px;
        height: 20px;
        padding: 2px 9px;
        text-align: center;
        font-size: 12px;
        font-style: normal;
        font-weight: 700;
        line-height: 16px;
    }
    .input-adjustment label{
        color:#4C4F54;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: 24px;
    }
    .input-adjustment textarea{
        min-height: 124px;
        margin-bottom: 24px;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom" id="card-reporting">
        <div class="card-header d-flex flex-column flex-md-row flex-lg-row">
            <div class="title-card-page d-flex">
                <h5>Adjustment</h5>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="popover"
                data-bs-placement="right"
                data-bs-content="This is a very beautiful popover, show some love.">
                    <path d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z" stroke="#E5E5E5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <form action="" class="filter-card ms-auto d-flex align-items-center flex-column flex-md-row flex-lg-row">
                <label class="label-filter-card" for="date-filter">Date:</label>
                <div class="input-group input-group-merge datePickerGroup">
                    <input
                    type="text"
                    class="form-control" name="date-filter" id="search-date" placeholder="DD/MM/YYYY" value="{{$date}}" data-input/>
                    <span class="input-group-text" data-toggle>
                    <i class="ti ti-calendar-event cursor-pointer"></i>
                    </span>
                </div>
                <label class="label-filter-card" for="origin-filter">Hub:</label>
                <select id="origin-filter" class="form-select" name="origin-filter">
                    <option value="" {{ request()->get('origin_filter') == "" ? 'selected' : '' }}>All Hub</option>
                    @foreach ($hubs as $hub)
                        <option value="{{ $hub->hub_id }}" {{ request()->get('origin_filter') == $hub->hub_id ? 'selected' : '' }}>{{ $hub->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-header pt-0">
            <ul class="nav nav-pills card-header-pills" role="tablist">
                <li class="nav-item">
                    <a
                    href="{{ route('adjustment.master-waybill') }}"
                    class="nav-link">
                    Reject Master Waybill
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="{{ route('adjustment.single-waybill') }}"
                    class="nav-link active">
                    Reject Single Waybill
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="{{ route('adjustment.delivery-process') }}"
                    class="nav-link">
                    Delivery Process
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="title-adjustment">Reject Single Waybill</h5>
                </div>
                <div class="col-md-12">
                    <div class="d-flex align-items-center d-flex justify-content-between search-data" id="search-data">
                        <strong class="flex-fill">Waybill&nbsp;Number</strong>
                        <input type="text" name="waybill-code" placeholder="Waybill Number" id="waybill-code" class="form-control flex-fill">
                        <button type="button" class="btn btn-primary flex-fill" id="submitMaster">Submit</button>
                    </div>
                </div>
                <form action="{{ route('adjustment.single-waybill') }}" method="post" class="form-adjustment d-none needs-validation" id="form-adjustment" novalidate>
                    @csrf
                    <input type="hidden" name="id" id="input-id">
                    <div class="col-md-12">
                        <h5 class="detail-adjustment">Waybill Information</h5>
                    </div>
                    <div class="row detail-waybill">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <strong>Order Code:</strong>
                                <span id="order-code"></span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Order Date:</strong>
                                <span id="order-date"></span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Channel:</strong>
                                <span id="channel"></span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Brand:</strong>
                                <span id="brand"></span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Delivery Record:</strong>
                                <span id="delivery-record"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <strong>Courier:</strong>
                                <span id="courier"></span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>COD:</strong>
                                <span id="cod"></span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Status:</strong>
                                <span id="status">
                                    
                                </span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Origin Hub:</strong>
                                <span id="origin-hub"></span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Destination Hub:</strong>
                                <span id="destination-hub"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-adjustment">
                        <div class="col-md-12 pb-4">
                            <label for="" class="form-label">Reason <span class="text-danger">*</span></label>
                            <select name="reason" id="" class="form-select" required>
                                <option value="" selected disabled>Select Reason</option>
                                <option value="Informasi Tidak Lengkap atau Tidak Akurat">Informasi Tidak Lengkap atau Tidak Akurat</option>
                                <option value="Barang Dilarang atau Tidak Sesuai Ketentuan">Barang Dilarang atau Tidak Sesuai Ketentuan</option>
                                <option value="Kondisi Barang Rusak atau Tidak Lengkap">Kondisi Barang Rusak atau Tidak Lengkap</option>
                                <option value="Jadwal atau Rute Pengiriman Tidak Tersedia">Jadwal atau Rute Pengiriman Tidak Tersedia</option>
                                <option value="Masalah Pembayaran atau Tagihan">Masalah Pembayaran atau Tagihan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <div class="invalid-feedback">Please select reason.</div>
                        </div>
                        <div class="col-md-12">
                            <label for="" class="form-label">Remark</label>
                            <textarea class="form-control" name="remark" placeholder="Remark"></textarea>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-danger float-end">Reject Waybill</button>
                        </div>
                    </div>
                </form>
                <div class="col-md-12">
                    <h5 class="history-adjustment">Reject History</h5>
                </div>
                <div class="col-md-12">
                    <div class="card-datatable table-responsive">
                        <table id="DataTableHistory" class="table table-history">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>WAYBILL NUMBER</th>
                                    <th>STATUS</th>
                                    <th>TIMESTAMP</th>
                                    <th>MODIFIED BY</th>
                                    <th class="no-sort">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($adjustments as $key => $adjustment)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $adjustment->code }}</td>
                                    <td>
                                        <span class="badge bg-label-{{ $adjustment->statusTo->label }}">{{ $adjustment->statusTo->name }}</span>
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($adjustment->created_date)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $adjustment->created_by }}</td>
                                    <td>
                                        <a href="" target="_blank" class="btn btn-warning waves-effect waves-light">
                                            <i class="ti ti-book cursor-pointer"></i>
                                            Print
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('template/assets/vendor/libs/block-ui/block-ui.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script>
    $('#search-date').change(function()
    {
        var date = $('#search-date').val();
        var hub = $('#origin-filter').val();
        window.location.href = "{{ route('adjustment.single-waybill') }}?date="+date+"&origin_filter="+hub
    });

    $('#origin-filter').change(function()
    {
        var date = $('#search-date').val();
        var hub = $('#origin-filter').val();
        window.location.href = "{{ route('adjustment.single-waybill') }}?date="+date+"&origin_filter="+hub
    });

    $(document).ready(function() {
        $('#DataTableHistory').DataTable({
            "lengthChange": false,
            "searching": false,
            "paging": false,
            "info": false,
            "columnDefs": [{
                "targets": "no-sort",
                "orderable": false
            }]
        });

        $("#submitMaster").click(function(){
            $("#form-adjustment").addClass("d-none");
            $('#search-data').block({
                message: '<div class="spinner-border text-white" role="status"></div>',
                css: {
                    backgroundColor: 'transparent',
                    border: '0'
                },
                    overlayCSS: {
                    opacity: 0.5
                }
            });
            var waybillCode = $("#waybill-code").val();

            if (waybillCode.length == 0) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please input waybill number!',
                    icon: 'error',
                    customClass: {
                    confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
                $('#search-data').unblock();
            } else {
                var url = "{{ route('adjustment.single-waybill-information') }}";
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        code:waybillCode
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.success) {
                            var waybill = data.data;

                            $("#order-code").html(waybill.order_code);
                            $("#order-date").html(waybill.order_date);
                            $("#channel").html(waybill.channel);
                            $("#brand").html(waybill.brand);
                            $("#delivery-record").html(waybill.delivery_record);
                            $("#courier").html(waybill.courier);
                            $("#cod").html(waybill.cod);
                            $("#status").html("<span class='badge bg-label-"+waybill.status_label+"'>"+waybill.status_name+"</span>");
                            $("#origin-hub").html(waybill.origin_hub);
                            $("#destination-hub").html(waybill.destination_hub);
                            $("#input-id").val(waybill.package_id);

                            $("#form-adjustment").removeClass("d-none");
                            $('#search-data').unblock();
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.error,
                                icon: 'error',
                                customClass: {
                                confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                            $('#search-data').unblock();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        Swal.fire({
                            title: 'Error!',
                            text: error,
                            icon: 'error',
                            customClass: {
                            confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        $('#search-data').unblock();
                    }
                });
            }
        });

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const bsValidationForms = document.querySelectorAll('.needs-validation');

        // Loop over them and prevent submission
        Array.prototype.slice.call(bsValidationForms).forEach(function (form) {
        form.addEventListener(
            'submit',
            function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();

                    Swal.fire({
                        title: '<strong class="mt-0">Are you sure?</strong>',
                        text: "Are you sure wants to reject Single Waybill?",
                        imageUrl: "{{ asset('assets/icon/danger-alert.png') }}",
                        imageWidth: 100,
                        imageHeight: 100,
                        imageAlt: "danger icon",
                        showCancelButton: true,
                        confirmButtonText: 'Yes, reject it!',
                        customClass: {
                            confirmButton: 'btn btn-danger me-3',
                            cancelButton: 'btn btn-label-secondary'
                        },
                        buttonsStyling: false
                    }).then(function (result) {
                        if (result.value) {
                            $("#form-adjustment").submit();
                        }
                    });
                }

                form.classList.add('was-validated');
            },
            false
        );
        });
    });

    const datePickerGroup = document.querySelector('.datePickerGroup');
    if (datePickerGroup) {
        datePickerGroup.flatpickr({
            monthSelectorType: 'static',
            maxDate: 'today',
            wrap: true,
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            disableMobile: "true"
        });
    }
</script>
@endsection