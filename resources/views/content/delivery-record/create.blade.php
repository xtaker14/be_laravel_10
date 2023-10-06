@extends('layouts.main')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-sm-12 col-lg-12 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <span class="me-6">Delivery Record</span>
                </div>
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
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
                </div>
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="col-md-6">
                                <form action="{{ route('upload-reqwaybill') }}" id="addNewCCForm" class="row g-3" method="post" enctype="multipart/form-data">
                                @csrf
                                    <div class="mb-3">
                                        <label for="exampleFormControlSelect1" class="form-label">Select Courier</label>
                                        <select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example">
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlReadOnlyInput1" class="form-label">Transport Type</label>
                                        <input
                                        class="form-control"
                                        type="text"
                                        id="exampleFormControlReadOnlyInput1"
                                        placeholder="Readonly input here..."
                                        readonly />
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Delivery Date</label>
                                        <input type="text" class="form-control dt-date flatpickr-date dt-input" name="dt_date" placeholder="YYYY-MM-DD" id="flatpickr-date" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Waybill ID</label>
                                        <input
                                        type="email"
                                        class="form-control"
                                        id="exampleFormControlInput1"
                                        placeholder="name@example.com" />
                                    </div>
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Assign</button>
                                </form>
                        </div>
                        <div class="col-md-6">
                        <div class="card mb-4">
                                <h5 class="card-header">Counter</h5>
                                <div class="card-body">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="card-datatable text-nowrap table-responsive">
                            <table class="table table-hover text-nowrap" id="serverside">
                                <thead class="table-light">
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
            </div>
        </div>
    </div>
</div>
@endsection