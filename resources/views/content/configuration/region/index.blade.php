@extends('layouts.main')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Master Region</h5>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="popover"
            data-bs-placement="right"
            data-bs-content="This is a very beautiful popover, show some love.">
                <path d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z" stroke="#E5E5E5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="d-flex">
            <div class="input-search-datatable me-auto">
                <div class="input-group input-group-merge">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Search"
                      id="customSearchInput"
                      aria-describedby="text-to-speech-addon" />
                    <span class="input-group-text" id="text-to-speech-addon">
                      <i class="ti ti-search cursor-pointer"></i>
                    </span>
                </div>
            </div>
            <div class="button-area-datatable">
                <button type="button" class="btn btn-primary waves-effect waves-light">Create</button>
                <button type="button" class="btn btn-primary waves-effect waves-light">Import</button>
                <button type="button" class="btn btn-outline-secondary waves-effect waves-light">
                    <i class="ti ti-cloud-down cursor-pointer"></i>
                    Template
                </button>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table id="DataTableServeSide" class="table table-custom-default">
                <thead>
                    <tr>
                        <th>REGION ID</th>
                        <th>PROVINCE</th>
                        <th>CITY</th>
                        <th>DISTRICT</th>
                        <th>SUBDISTRICT</th>
                        <th>POSTAL CODE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

var table = $('#DataTableServeSide').DataTable({
    processing: true,
    serverSide: true,
    lengthChange: false,
    ajax: "{{ route('configuration.region.index') }}",
    columns: [
        {data: 'row_index', name: 'sub.row_index'},
        {data: 'province', name: 'province.name'},
        {data: 'city', name: 'city.name',},
        {data: 'district', name: 'district.name'},
        {data: 'subdistrict', name: 'subdistrict.name'},
        {data: 'postal_code', name: 'postal_code'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
    ]
});

// Add an event listener to your custom search input
$('#customSearchInput').on('keyup', function() {
    // Get the value of the input field
    var searchValue = $(this).val();

    // Use DataTables API to search and redraw the table
    table.search(searchValue).draw();
});

</script>
@endsection