@extends('layouts.main')

@section('styles')
<style>
    .progress { position: relative; width: 100%; margin-top: 10px;}
    .bar { background-color: #03fc30; width:0%; height: 40px; }
    .percent { position: absolute; display: inline-block; left: 50%; color: #040608;}
</style>    
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Request Waybill</h5>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="popover"
                data-bs-placement="right"
                data-bs-content="This is a very beautiful popover, show some love.">
                <path d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z" stroke="#E5E5E5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <form class="filter-card ms-auto d-flex align-items-center flex-column flex-md-row flex-lg-row">
                <label class="label-filter-card" for="date-filter" style="margin: 0px 8px;">Date:</label>
                <div class="input-group input-group-merge datePickerGroup">
                    <input type="text" class="form-control date" name="date" placeholder="YYYY-MM-DD" id="search-date" value="{{ $date }}" />
                    <span class="input-group-text" data-toggle>
                    <i class="ti ti-calendar-event cursor-pointer"></i>
                    </span>    
                </div>
                <label class="label-filter-card" for="origin-filter" style="margin: 0px 8px;">Origin&nbsp;Hub:</label>
                <select class="form-select" id="hub">
                    @foreach($hub as $hubs)
                        <option value="{{ $hubs->hub_id }}">{{ $hubs->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="d-flex justify-content-end">
            <div class="button-area-datatable">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ImportModal">Import Order</button>
                <a class="btn btn-outline-secondary waves-effect waves-light" href="{{ asset('web-resource/files-upload/template-req-waybill.xlsx') }}" download>
                    <i class="ti ti-cloud-down ti-xs me-1"></i>
                    Template
                </a>
            </div>
        </div>
        <div class="card-datatable text-nowrap table-responsive">
            <table class="table table-custom-default" id="serverside">
                <thead>
                    <tr>
                    <th>Master Waybill</th>
                    <th>File Name</th>
                    <th>Total Waybill</th>
                    <th>Upload Time</th>
                    <th>Upload By</th>
                    <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="ImportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                <h3 class="mb-2">Import Delivery Order</h3>
                </div>
                <form action="{{ route('upload-reqwaybill') }}" id="addNewCCForm" class="row g-3 form-upload" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="col-12 text-center">
                        <div class="fallback">
                            <input name="file" type="file" accept=".xlsx, .xls, .csv" required/>
                            <div class="progress">
                                <div class="bar"></div>
                                <div class="percent"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        load();
    })

    function load(){
        var date = $('#search-date').val();
        if(date != "")
            var url = "{{ route('request-waybill') }}?date="+date
        else
            var url = "{{ route('request-waybill') }}"

        $('#serverside').DataTable({
            processing: true,
            ajax: { url : url },
            columns: [
                { data: 'master_waybill', name: 'master_waybill' },
                { data: 'filename', name: 'filename' },
                { data: 'total_waybill', name: 'total_waybill' },
                { data: 'upload_time', name: 'upload_time' },
                { data: 'upload_by', name: 'upload_by' },
                { data: 'action', name: 'action' }
            ],
        });
    }
    
    $('#search-date').change(function()
    {
        var date = $('#search-date').val();
        window.location.href = "{{ route('request-waybill') }}?date="+date
    });

    $(function() {
        $(document).ready(function() {
            var bar = $('.bar');
            var percent = $('.percent');

            $('form').ajaxForm({
                responseType : 'blob',
                beforeSend: function() {
                    var percentVal = '0%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                },
                complete: function(response) {
                    Swal.fire({
                        title: 'Success',
                        text: 'Success Upload Request Waybill',
                        icon: 'success',
                        type: "success",
                        showCancelButton: false,
                        showDenyButton: false,
                        customClass: {
                            confirmButton: 'btn btn-primary me-3'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        var res = response.responseText;
                        var msgs = res.split("*");
                        if(msgs[0] == "OK")
                        {                    
                            var params = {
                                filename: msgs[1],
                                data: msgs[2]
                            }
                            
                            var url = "{{ route('upload-result') }}?" + $.param(params);

                            window.location = url;
                            location.reload();
                        }
                    });
                }
            });
        });
    });
</script>
@endsection