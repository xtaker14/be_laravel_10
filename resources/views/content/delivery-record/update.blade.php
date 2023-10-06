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
                                    <a class="nav-link" href="{{ route('create-record') }}"> Create</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="javascript:void(0);"> Update</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <form class="form-repeater">
                            <div data-repeater-list="group-a">
                                <div data-repeater-item>
                                <div class="row">
                                    <div class="mb-3 col-lg-6 col-xl-5 col-12 mb-0">
                                    <label class="form-label" for="form-repeater-1-1">Delivery Record</label>
                                    <input type="text" id="form-repeater-1-1" class="form-control" placeholder="john.doe" />
                                    </div>
                                    <div class="mb-3 col-lg-6 col-xl-5 col-12 mb-0">
                                    <label class="form-label" for="form-repeater-1-4">Change Courier</label>
                                    <select id="form-repeater-1-4" class="form-select">
                                        <option value="Designer">Designer</option>
                                        <option value="Developer">Developer</option>
                                        <option value="Tester">Tester</option>
                                        <option value="Manager">Manager</option>
                                    </select>
                                    </div>
                                    <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                    <button class="btn btn-primary mt-4" data-repeater-delete>
                                        <span class="align-middle">Update</span>
                                    </button>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="card-datatable text-nowrap table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waybill</th>
                                        <th>Brand</th>
                                        <th>District</th>
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