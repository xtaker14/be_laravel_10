@extends('layouts.main')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-sm-12 col-lg-12 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <span class="me-6">Waybill List</span>
                </div>
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
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
                </div>
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="card-datatable text-nowrap table-responsive">
                            <table class="dt-column-search table">
                                <thead class="table-light">
                                    <tr>
                                    <th>Waybill</th>
                                    <th>Location</th>
                                    <th>Brand</th>
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
            </div>
        </div>
    </div>
</div>
@endsection