@extends('layouts.main')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Request Waybill</h5>
            <div class="card-header-elements ms-auto">
                <span class="d-flex align-items-center me-2">
                    <span class="me-1"><b>Date:</b></span>
                    <div class="mb-1">
                        <input type="text" class="form-control dt-date flatpickr-date dt-input" name="dt_date" placeholder="YYYY-MM-DD" id="flatpickr-date" value=""/>
                    </div>
                </span>
                <span class="d-flex align-items-center me-2">
                    <span class="me-1"><b>Origin Hub:</b></span>
                    <select class="form-select">
                        @foreach($hub as $hubs)
                            <option value="{{ $hubs->hub_id }}">{{ $hubs->name }}</option>
                        @endforeach
                    </select>
                </span>
            </div>
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
                <form action="{{ route('upload-reqwaybill') }}" id="addNewCCForm" class="row g-3" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="col-12 text-center">
                        <div class="fallback">
                            <input name="file" type="file" accept=".xlsx, .xls, .csv"/>
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
    <script>
        $(document).ready(function () {
            load();
        })

        function load(){
            $('#serverside').DataTable({
                processing: true,
                ajax: { url :"{{ route('list-upload') }}"},
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

        // $(".flatpickr-date").change(function () {
        //     var date = $(this).val();
        //     $.ajax({
        //         type:'GET',
        //         url:"{{ route('request-waybill') }}",
        //         data:{'date':date},
        //         success:function(data){
        //             console.log(data);
        //         }
        //     });
        // });
    </script>
@endsection