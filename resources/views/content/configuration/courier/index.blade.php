@extends('layouts.main')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Courier</h5>
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
                <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#importModal">Import</button>
                <a href="{{ route('configuration.courier.template-import') }}" class="btn btn-outline-secondary waves-effect waves-light">
                    <i class="ti ti-cloud-down cursor-pointer"></i>
                    Template
                </a>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table id="DataTableServeSide" class="table table-custom-default">
                <thead>
                    <tr>
                        <th>COURIER ID</th>
                        <th>COURIER NAME</th>
                        <th>ORIGIN HUB</th>
                        <th>VENDOR NAME</th>
                        <th>TRANSPORT TYPE</th>
                        <th>PHONE NUMBER</th>
                        <th>STATUS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="importModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalTitle">Import Courier</h5>
                <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBackdrop" class="form-label">Upload File Import :</label>
                        <div class="input-group">
                            <input
                              type="file"
                              class="form-control"
                              id="fileUpload"
                              aria-describedby="fileUpload"
                              aria-label="Upload" 
                              accept=".xlsx"/>
                            <button class="btn btn-outline-primary" type="button" id="import-btn">Import</button>
                        </div>
                        <div class="text-danger d-none" id="invalid-upload">Please select file to upload.</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                Close
                </button>
            </div>
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
    ajax: "{{ route('configuration.courier.index') }}",
    columns: [
        {data: 'code', name: 'courier.code'},
        {data: 'full_name', name: 'users.full_name'},
        {data: 'hub_name', name: 'hub.name'},
        {data: 'vendor_name', name: 'partner.name'},
        {data: 'vehicle_type', name: 'courier.vehicle_type'},
        {data: 'phone', name: 'courier.phone'},
        {data: 'status', name: 'sub2.status'},
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

$(document).ready(function () {
    $('#import-btn').on('click', function () {
        $("#invalid-upload").addClass("d-none");
        $("#fileUpload").removeClass("is-invalid");
        // Get the file input element
        var fileInput = $('#fileUpload')[0];

        // Check if a file is selected
        if (fileInput.files.length > 0) {
            // Create a FormData object to store the file data
            var formData = new FormData();

            // Append the file to the FormData object
            formData.append('file', fileInput.files[0]);
            formData.append('_token', "{{ csrf_token() }}");

            // Make the AJAX request
            var url = "{{ route('configuration.courier.upload') }}";
            $.ajax({
                url: url, // Your Laravel route for handling file upload
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data) {
                    var blob = new Blob([data]);
                    var link = document.createElement('a');
                    var user_id = "{{ Auth::user()->users_id }}"; 
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "import_courier_"+Date.now()+"_"+user_id+"_result.xlsx";
                    link.click();

                    Swal.fire({
                        title: 'Success!',
                        text: "Import courier success to process, please open file download to check result",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonText: 'Ok!',
                        customClass: {
                            confirmButton: 'btn btn-danger me-3',
                        },
                        buttonsStyling: false
                    }).then(function (result) {
                        if (result.value) {
                            location.reload();
                        }
                    });
                },
                error: function(error) {
                    Swal.fire({
                        title: 'Error!',
                        text: error,
                        icon: 'error',
                        customClass: {
                        confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });

                    $('#fileUpload').val('');
                }
            });
        } else {
            // No file selected, provide user feedback if needed
            $("#invalid-upload").removeClass("d-none");
            $("#fileUpload").addClass("is-invalid");
        }
    });
});

</script>
@endsection