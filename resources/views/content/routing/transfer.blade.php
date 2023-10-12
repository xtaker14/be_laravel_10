@extends('layouts.main')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-sm-12 col-lg-12 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <span class="me-6">Transfer</span>
                </div>
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="col-md-6">
                                <form action="{{ route('upload-reqwaybill') }}" id="addNewCCForm" class="row g-3" method="post" enctype="multipart/form-data">
                                @csrf
                                    <div class="mb-3">
                                        <label for="exampleFormControlSelect1" class="form-label">Destination Hub</label>
                                        <select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example">
                                        @foreach($hub as $h)
                                        <option value="{{$h->hub_id}}">{{ $h->name }}</option>
                                        @endforeach;
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Waybill</label>
                                        <input
                                        type="text"
                                        class="form-control"
                                        id="exampleFormControlInput1"
                                        placeholder="DTX00000" />
                                    </div>
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Finish</button>
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
                                    <th>M-Bag Id</th>
                                    <th>Total Waybill</th>
                                    <th>Total Koli</th>
                                    <th>Total Weight</th>
                                    <th>Hub Origin</th>
                                    <th>Hub Destination</th>
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