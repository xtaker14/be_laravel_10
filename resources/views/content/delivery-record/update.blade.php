@extends('layouts.main')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Delivery Record</h5>
        </div>
        <div class="d-flex">
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
                <form class="form-repeater" action="{{ route('update-record') }}" method="get">
                    <div data-repeater-list="group-a">
                        <div data-repeater-item>
                        <div class="row">
                            <div class="mb-3 col-lg-6 col-xl-5 col-12 mb-0">
                                <label class="form-label" for="form-repeater-1-1">Delivery Record</label>
                                <input name="waybill" type="text" id="form-repeater-1-1" class="form-control" placeholder="DTX001" />
                            </div>
                            <div class="mb-3 col-lg-6 col-xl-5 col-12 mb-0">
                                <label class="form-label" for="form-repeater-1-4">Change Courier</label>
                                <select class="form-select" name="courier" id="courier" aria-label="Default select example">
                                    <option selected disbaled hidden>Select Courier</option>
                                    @foreach($courier as $cc)
                                    <option value="{{ $cc->courier_id }}" data-tp="{{ $cc->vehicle_type}}">{{ $cc->full_name }}</option>
                                    @endforeach;
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
                <tbody>
                    @foreach ($data as $datas)
                        <tr>
                            <td>{{ $datas->waybill }}</td>
                            <td>{{ $datas->waybill }}</td>
                            <td>{{ $datas->district }}</td>
                            <td><button type="button" class="btn btn-danger waves-effect waves-light">
                                <i class="ti ti-trash cursor-pointer"></i>Drop</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection