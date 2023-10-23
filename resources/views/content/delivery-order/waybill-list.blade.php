@extends('layouts.main')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Waybill List</h5>
        </div>
        <div class="d-flex">
            <div class="col-md-4 xs-3">
                <span class="d-flex align-items-center me-2">
                    <span class="me-1"><b>Created Waybill:</b></span>
                    <div class="mb-1">
                        <input type="text" class="form-control dt-date flatpickr-date dt-input" name="dt_date" placeholder="YYYY-MM-DD" id="flatpickr-date" />
                    </div>
                </span>
            </div>
            <div class="col-md-4 xs-3">
                <span class="d-flex align-items-center me-2">
                    <span class="me-1"><b>Filter Status:</b></span>
                    <select class="form-select">
                        <option selected="">All</option>
                        <option>Option 2</option>
                        <option>Option 3</option>
                    </select>
                </span>
            </div>
            <div class="col-md-4 xs-3">
                <span class="d-flex align-items-center me-2">
                    <span class="me-1"><b>Origin Hub:</b></span>
                    <select class="form-select">
                        <option selected="">Option 1</option>
                        <option>Option 2</option>
                        <option>Option 3</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="card-datatable text-nowrap table-responsive">
            <table class="table table-custom-default" id="serverside">
                <thead>
                    <tr>
                    <th>Waybill</th>
                    <th>Location</th>
                    <th>Origin Hub</th>
                    <th>Destination Hub</th>
                    <th>Status</th>
                    <th>Created Via</th>
                    <th>Actions</th>
                    </tr>
                </thead>
            </table>
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
                ajax: { url :"{{ route('list-package') }}"},
                columns: [
                    { data: 'waybill', name: 'waybill' },
                    { data: 'location', name: 'location' },
                    { data: 'origin_hub', name: 'origin_hub' },
                    { data: 'destination_hub', name: 'destination_hub' },
                    { data: 'status', name: 'status' },
                    { data: 'created_via', name: 'created_via' },
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