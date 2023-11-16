@extends('layouts.main')

@section('styles')
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
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom" id="card-reporting">
        <div class="card-header d-flex">
            <h5>Waybill</h5>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="popover"
            data-bs-placement="right"
            data-bs-content="This is a very beautiful popover, show some love.">
                <path d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z" stroke="#E5E5E5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="card-header pt-0">
            <ul class="nav nav-pills card-header-pills" role="tablist">
                <li class="nav-item">
                    <button
                    type="button"
                    class="nav-link active"
                    role="tab"
                    data-bs-toggle="tab"
                    data-bs-target="#navs-pills-waybill-transaction"
                    aria-controls="navs-pills-waybill-transaction"
                    aria-selected="true">
                    Waybill Transaction
                    </button>
                </li>
                <li class="nav-item">
                    <button
                    type="button"
                    class="nav-link"
                    role="tab"
                    data-bs-toggle="tab"
                    data-bs-target="#navs-pills-waybill-history"
                    aria-controls="navs-pills-waybill-history"
                    aria-selected="false">
                    Waybill History
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content p-0">
                <div class="tab-pane fade show active" id="navs-pills-waybill-transaction" role="tabpanel">
                    <div class="mb-3">
                        <label class="form-label" for="waybill-transaction-date">Filter Date</label>
                        <div class="input-group input-group-merge datePickerGroup">
                            <input
                            type="text"
                            class="form-control" name="waybill-transaction-date" id="waybill-transaction-date" placeholder="DD/MM/YYYY" value="" data-input/>
                            <span class="input-group-text" data-toggle>
                            <i class="ti ti-calendar-event cursor-pointer"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="waybill-transaction-hub">Hub</label>
                        <select name="waybill-transaction-hub" id="waybill-transaction-hub" class="form-select">
                            <option value="" selected>All hub</option>
                            @foreach ($hubs as $hub)
                            <option value="{{ $hub->hub_id }}">{{ $hub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="waybill-transaction-status">Status</label>
                        <select name="waybill-transaction-status" id="waybill-transaction-status" class="form-select">
                            <option value="" selected>All waybill status</option>
                            @foreach ($status as $data)
                            <option value="{{ $data->status_id }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="waybill-transaction-payment">Payment Type</label>
                        <select name="waybill-transaction-payment" id="waybill-transaction-payment" class="form-select">
                            <option value="" selected>All waybill payment type</option>
                            <option value="cod">COD</option>
                            <option value="non_cod">NON COD</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary float-end" id="download-waybill-transaction">Download</button>
                </div>
                <div class="tab-pane fade" id="navs-pills-waybill-history" role="tabpanel">
                    <div class="mb-3">
                        <label class="form-label" for="waybill-history-date">Filter Date</label>
                        <div class="input-group input-group-merge datePickerGroup2">
                            <input
                            type="text"
                            class="form-control" name="waybill-history-date" id="waybill-history-date" placeholder="DD/MM/YYYY" value="" data-input/>
                            <span class="input-group-text" data-toggle>
                            <i class="ti ti-calendar-event cursor-pointer"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="waybill-history-hub">Hub</label>
                        <select name="waybill-history-hub" id="waybill-history-hub" class="form-select">
                            <option value="" selected>All Hub</option>
                            @foreach ($hubs as $hub)
                            <option value="{{ $hub->hub_id }}">{{ $hub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="waybill-history-status">Status</label>
                        <select name="waybill-history-status" id="waybill-history-status" class="form-select">
                            <option value="" selected>All waybill status</option>
                            @foreach ($status as $data)
                            <option value="{{ $data->status_id }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="waybill-history-payment">Payment Type</label>
                        <select name="waybill-history-payment" id="waybill-history-payment" class="form-select">
                            <option value="" selected>All waybill payment type</option>
                            <option value="cod">COD</option>
                            <option value="non_cod">NON COD</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary float-end" id="download-waybill-history">Download</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('template/assets/vendor/libs/block-ui/block-ui.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#download-waybill-transaction").click(function() {
            var date = $("#waybill-transaction-date").val();
            var hub = $("#waybill-transaction-hub").val();
            var status = $("#waybill-transaction-status").val();
            var payment = $("#waybill-transaction-payment").val();

            if (date.length == 0) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please select date!',
                    icon: 'error',
                    customClass: {
                    confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            } else {
                $('#card-reporting').block({
                    message: '<div class="spinner-border text-white" role="status"></div>',
                    css: {
                        backgroundColor: 'transparent',
                        border: '0'
                    },
                        overlayCSS: {
                        opacity: 0.5
                    }
                });

                var url = "{{ route('report.waybill-transaction') }}";
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        date:date,
                        hub:hub,
                        status:status,
                        payment:payment
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        var blob = new Blob([data]);
                        var link = document.createElement('a');
                        var user_id = "{{ Auth::user()->users_id }}"; 
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "reporting_waybill_transaction_"+Date.now()+"_"+user_id+".xlsx";
                        link.click();

                        Swal.fire({
                            title: 'Success!',
                            text: 'Success download reporting waybill transaction',
                            icon: 'success',
                            customClass: {
                            confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });

                        $('#card-reporting').unblock();
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: error,
                            icon: 'error',
                            customClass: {
                            confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        $('#card-reporting').unblock();
                    }
                });
            }
        })

        $("#download-waybill-history").click(function() {
            var date = $("#waybill-history-date").val();
            var hub = $("#waybill-history-hub").val();
            var status = $("#waybill-history-status").val();
            var payment = $("#waybill-history-payment").val();

            if (date.length == 0) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please select date!',
                    icon: 'error',
                    customClass: {
                    confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            } else {
                $('#card-reporting').block({
                    message: '<div class="spinner-border text-white" role="status"></div>',
                    css: {
                        backgroundColor: 'transparent',
                        border: '0'
                    },
                        overlayCSS: {
                        opacity: 0.5
                    }
                });

                var url = "{{ route('report.waybill-history') }}";
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        date:date,
                        hub:hub,
                        status:status,
                        payment:payment
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        var blob = new Blob([data]);
                        var link = document.createElement('a');
                        var user_id = "{{ Auth::user()->users_id }}"; 
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "reporting_waybill_history_"+Date.now()+"_"+user_id+".xlsx";
                        link.click();

                        Swal.fire({
                            title: 'Success!',
                            text: 'Success download reporting waybill history',
                            icon: 'success',
                            customClass: {
                            confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });

                        $('#card-reporting').unblock();
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: error,
                            icon: 'error',
                            customClass: {
                            confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        $('#card-reporting').unblock();
                    }
                });
            }
        })
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

    const datePickerGroup2 = document.querySelector('.datePickerGroup2');
    if (datePickerGroup2) {
        datePickerGroup2.flatpickr({
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