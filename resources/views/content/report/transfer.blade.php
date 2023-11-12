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
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Transfer</h5>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="popover"
            data-bs-placement="right"
            data-bs-content="This is a very beautiful popover, show some love.">
                <path d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z" stroke="#E5E5E5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="card-body p-0">
            <div class="tab-content p-0">
                <div class="tab-pane fade show active" id="navs-pills-inbound-summary" role="tabpanel">
                    <div class="mb-3">
                        <label class="form-label" for="filter-date">Filter Date</label>
                        <div class="input-group input-group-merge datePickerGroup">
                            <input
                            type="text"
                            class="form-control" name="filter-date" id="filter-date" placeholder="DD/MM/YYYY" value="" data-input/>
                            <span class="input-group-text" data-toggle>
                            <i class="ti ti-calendar-event cursor-pointer"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="from-hub">From Hub</label>
                        <select name="from-hub" id="from-hub" class="form-select">
                            <option value="" selected disabled>Select Hub</option>
                            @foreach ($hubs as $hub)
                            <option value="{{ $hub->hub_id }}">{{ $hub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="to-hub">To Hub</label>
                        <select name="to-hub" id="to-hub" class="form-select">
                            <option value="" selected disabled>Select Hub</option>
                            @foreach ($hubs as $hub)
                            <option value="{{ $hub->hub_id }}">{{ $hub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary float-end" id="download">Download</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $("#download").click(function() {
            var date    = $("#filter-date").val();
            var fromhub = $("#from-hub").val();
            var tohub   = $("#to-hub").val();

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
            } else if(fromhub === null) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please select from hub!',
                    icon: 'error',
                    customClass: {
                    confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            } else if(tohub === null) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please select to hub!',
                    icon: 'error',
                    customClass: {
                    confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            } else {
                var url = "{{ route('report.report_transfer') }}";
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        date:date,
                        tohub:tohub,
                        fromhub:fromhub
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        var blob = new Blob([data]);
                        var link = document.createElement('a');
                        var user_id = "{{ Auth::user()->users_id }}"; 
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "reporting_transfer_"+Date.now()+"_"+user_id+"_.xlsx";
                        link.click();

                        Swal.fire({
                            title: 'Success!',
                            text: 'Success download reporting transfer',
                            icon: 'success',
                            customClass: {
                            confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
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