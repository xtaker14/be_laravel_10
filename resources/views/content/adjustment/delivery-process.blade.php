@extends('layouts.main')

@section('styles')
<link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/select2/select2.css') }}" />
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
        margin-top: 30px;
    }
    .search-data label{
        color:#4C4F54;
        font-size: 12px;
        font-style: normal;
        font-weight: 700;
        margin-bottom: 8px;
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
        margin-top: 30px;
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
    .input-adjustment select{
        margin-bottom: 30px;
    }
    .input-adjustment textarea{
        min-height: 124px;
        margin-bottom: 24px;
    }
    .delivered-input input[type="file"]{
        display: none;
    }
    .delivered-input .preview{
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        flex: 1 0 0;
        align-self: stretch;
        border-radius: 8px;
        border: #E5E5E5;
        background: #E5E5E5;
        min-height: 118px;
        cursor: pointer;
        text-align: center;
        flex-direction: column;
    }
    .delivered-input img{
        max-height: 118px;
        max-width: 100%;
    }
    .delivered-input .label-input{
        color:#4C4F54;
        font-size: 12px;
        font-style: normal;
        font-weight: 700;
        line-height: 16px;
        margin-bottom: 8px;
    }
    .delivered-input .label-upload{
        color:#4C4F54;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: 24px;
        margin-bottom: 8px;
        margin-top: 30px;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom" id="card-reporting">
        <div class="card-header d-flex flex-column flex-md-row flex-lg-row">
            <div class="title-card-page d-flex">
                <h5>Delivery Process</h5>
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
                    class="nav-link">
                    Reject Single Waybill
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="{{ route('adjustment.delivery-process') }}"
                    class="nav-link active">
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
            </div>
            <div class="row" id="search-data">
                <div class="col-md-5 search-data">
                    <label for="waybill-number">Waybill Number</label>
                    <input type="text" name="waybill-code" id="waybill-code" placeholder="Waybill Number" class="form-control">
                </div>
                <div class="col-md-5 search-data">
                    <label for="status">Change Status</label>
                    <select name="status_to" id="status_to" class="form-select select2" disabled>
                        <option value="" selected disabled>Select Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->code }}" data-badge="bg-label-{{ $status->label }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">  
                    <button type="button" class="btn btn-primary w-100" id="submitMaster">Submit</button>
                </div>
            </div>
            <div class="row">
                <form action="{{ route('adjustment.delivery-process') }}" method="post" class="form-adjustment d-none needs-validation" id="form-adjustment" enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" name="id" id="input-id">
                    <input type="hidden" name="status_from" id="status-from">
                    <input type="hidden" name="status_change" id="status-change">
                    <div class="col-md-12">
                        <div class="row delivered-input">
                            <div class="col-md-6">
                                <label for="information" class="label-input">Information</label>
                                <select name="information" id="information" class="form-select" disabled>
                                    <option value="" selected disabled>select information</option>
                                    <option value="Penerima Langsung">Penerima Langsung</option>
                                    <option value="Tetangga">Tetangga</option>
                                    <option value="Bapak/Ibu">Bapak/Ibu</option>
                                    <option value="Rumah kosong/tidak ada orang">Rumah kosong/tidak ada orang</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <div class="invalid-feedback">Please select information.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="note" class="label-input">Note</label>
                                <input type="text" name="note" id="note" class="form-control" disabled>
                                <div class="invalid-feedback">Please input note.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="e-signature" class="label-upload">E-Signature</label>
                                <label class="preview">
                                    <img src="{{ asset('assets/icon/upload-icon.png') }}" id="file-ip-1-preview">
                                    <input type="file" id="file-ip-1" accept="image/*" onchange="showPreview(event);" name="e-signature" disabled>
                                    <div class="invalid-feedback">Please upload E-signature.</div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label for="photo" class="label-upload">Photo</label>
                                <label class="preview">
                                    <img src="{{ asset('assets/icon/upload-icon.png') }}" id="file-ip-2-preview">
                                    <input type="file" id="file-ip-2" accept="image/*" onchange="showPreview2(event);" name="photo" disabled>
                                    <div class="invalid-feedback">Please upload Photo.</div>
                                </label>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary float-end mt-4">Update</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h5 class="detail-adjustment mt-4">Waybill Information</h5>
                    </div>
                    <div class="row detail-waybill">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <strong>Order Code:</strong>
                                <span id="order-code">MW0012</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Order Date:</strong>
                                <span id="order-date">14/08/2023 11:00</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Channel:</strong>
                                <span id="channel">TikTok Shop</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Brand:</strong>
                                <span id="brand">Nike Indonesia</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Delivery Record:</strong>
                                <span id="delivery-record">DR-JKT0010000234</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <strong>Courier:</strong>
                                <span id="courier">Hamdani</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>COD:</strong>
                                <span id="cod">1,799,000</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Status:</strong>
                                <span id="status">
                                    <span class="badge bg-label-warning">Shipment</span>
                                </span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Origin Hub:</strong>
                                <span id="origin-hub">Jakarta Selatan</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <strong>Destination Hub:</strong>
                                <span id="destination-hub">Bandung</span>
                            </div>
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
                                    <th>FROM</th>
                                    <th>TO</th>
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
                                        <span class="badge bg-label-{{ $adjustment->statusFrom->label }}">{{ $adjustment->statusFrom->name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{ $adjustment->statusTo->label }}">{{ $adjustment->statusTo->name }}</span>
                                    </td>
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
<script src="{{ asset('template/assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script>
    $('#search-date').change(function()
    {
        var date = $('#search-date').val();
        var hub = $('#origin-filter').val();
        window.location.href = "{{ route('adjustment.delivery-process') }}?date="+date+"&origin_filter="+hub
    });

    $('#origin-filter').change(function()
    {
        var date = $('#search-date').val();
        var hub = $('#origin-filter').val();
        window.location.href = "{{ route('adjustment.delivery-process') }}?date="+date+"&origin_filter="+hub
    });
    $(document).ready(function() {
        var select2 = $('.select2');

        if (select2.length) {
            // custom template to render badge
            function renderBadge(option) {
                if (!option.id) {
                    return option.text;
                }
                var $badge = "<span class='badge " + $(option.element).data('badge') + "'>" + option.text + "</span>";

                return $badge;
            }
            select2.wrap('<div class="position-relative"></div>').select2({
                templateResult: renderBadge,
                templateSelection: renderBadge,
                escapeMarkup: function (es) {
                    return es;
                }
            });
        }

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
            $('#status_to').prop('disabled', true);
            $('#status_to').val('');
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
                $('#status_to').prop('disabled', true);
                $('#status_to').val('');
                $('#search-data').unblock();
            } else {
                var url = "{{ route('adjustment.delivery-process-information') }}";
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
                            $('#status_to').prop('disabled', false);
                            $('#status_to').val(waybill.status_code).trigger('change');

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
                            $("#status-from").val(waybill.status_code);

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
                            $('#status_to').prop('disabled', true);
                            $('#status_to').val('');
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
                        $('#status_to').prop('disabled', true);
                        $('#status_to').val('');
                        $('#search-data').unblock();
                    }
                });
            }
        });

        $('#status_to').change(function()
        {
           var status = $(this).val();

           $("#status-change").val(status);
           
           if (status == 'DELIVERED') {
                $('#information').prop('disabled', false);
                $('#information').prop('required', true);
                $('#note').prop('disabled', false);
                $('#file-ip-1').prop('disabled', false);
                $('#file-ip-1').prop('required', true);
                $('#file-ip-2').prop('disabled', false);
                $('#file-ip-2').prop('required', true);
           } else {
                $('#information').prop('disabled', true);
                $('#information').prop('required', false);
                $('#note').prop('disabled', true);
                $('#file-ip-1').prop('disabled', true);
                $('#file-ip-1').prop('required', false);
                $('#file-ip-2').prop('disabled', true);
                $('#file-ip-2').prop('required', false);
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
                    
                    var status_from = $("#status-from").val();
                    var status_to = $("#status-change").val();

                    if (status_from == status_to) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Please select different status to change status.',
                            icon: 'error',
                            customClass: {
                            confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                    } else {
                        Swal.fire({
                            title: '<strong class="mt-0">Are you sure?</strong>',
                            text: "Are you sure wants to update delivery process?",
                            imageUrl: "{{ asset('assets/icon/danger-alert.png') }}",
                            imageWidth: 100,
                            imageHeight: 100,
                            imageAlt: "danger icon",
                            showCancelButton: true,
                            confirmButtonText: 'Yes, update it!',
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

    function showPreview(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-1-preview");
            preview.src = src;
            preview.style.display = "block";
        }
    }

    function showPreview2(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-2-preview");
            preview.src = src;
            preview.style.display = "block";
        }
    }
</script>
@endsection