@extends('layouts.main')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Delivery Record</h5>
        </div>
        <div class="d-flex">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-sm-row mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"> Create</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('update-record') }}"> Update</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="d-flex">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('create-dr') }}" id="addNewCCForm" class="row g-3" method="post" enctype="multipart/form-data">
                    @csrf
                        <div class="mb-3">
                            <label for="courier" class="form-label">Select Courier</label>
                            <select class="form-select" name="courier" id="courier" aria-label="Default select example">
                                <option selected disbaled hidden>Select Courier</option>
                                @foreach($courier as $cc)
                                <option value="{{ $cc->courier_id }}" data-tp="{{ $cc->vehicle_type}}">{{ $cc->full_name }}</option>
                                @endforeach;
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="transport" class="form-label">Transport Type</label>
                            <input
                            class="form-control"
                            type="text"
                            name="transport"
                            id="transport"
                            placeholder="Transport Type"
                            readonly />
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Delivery Date</label>
                            <input type="text" class="form-control dt-date flatpickr-date dt-input" name="date" placeholder="YYYY-MM-DD" id="flatpickr-date" />
                        </div>
                        <div class="mb-3">
                            <label for="waybill" class="form-label">Waybill ID</label>
                            <input
                            type="text"
                            class="form-control"
                            id="waybill"
                            name="waybill"
                            placeholder="DTX00000" />
                        </div>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Assign</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <h5 class="card-header">Counter : </h5>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-datatable text-nowrap table-responsive">
            <table class="table table-custom-default" id="serverside">
                <thead>
                    <tr>
                    <th>Delivery Record Id</th>
                    <th>Courier</th>
                    <th>Total Waybill</th>
                    <th>Total Koli</th>
                    <th>Total Weight</th>
                    <th>Total cod</th>
                    <th>Status</th>
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
$("#courier").change(function()
{
    document.getElementById('transport').value = $(this).find(':selected').data('tp');
});
</script>
@endsection