@extends('layouts.main')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Waybill Detail</h5>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="popover"
                data-bs-placement="right"
                data-bs-content="This is a very beautiful popover, show some love.">
                <path d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z" stroke="#E5E5E5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="card-body">
            <div class="row p-sm-3 p-0">
                <div class="col-xl-6 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
                    <h6 class="mb-3">DETAILS</h6>
                    <hr/>
                    <p class="mb-1"><b>Waybill :</b> {{ $package->tracking_number }}</p>
                    <p class="mb-1"><b>Origin Hub :</b> {{ $package->hub->name }}</p>
                    <p class="mb-1"><b>Destination Hub :</b> {{ $package->hub->name }}</p>
                    <p class="mb-1"><b>Status :</b> {{ $package->status->name }}</p>
                    <p class="mb-1"><b>Created Via :</b> {{ $package->created_via }}</p>
                </div>
                <div class="col-xl-6 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
                    <h6 class="mb-3">PACKAGE DETAILS</h6>
                    <hr/>
                    <p class="mb-1"><b>Customer Name :</b> {{ $package->recipient_name }} </p>
                    <p class="mb-1"><b>Address :</b> {{ $package->recipient_address }} </p>
                    <p class="mb-1"><b>Province :</b> {{ $package->recipient_province }} </p>
                    <p class="mb-1"><b>City :</b> {{ $package->recipient_city }}</p>
                    <p class="mb-1"><b>District :</b> {{ $package->recipient_district }}</p>
                    <p class="mb-1"><b>Weight :</b> {{ $package->total_weight }}</p>
                    <p class="mb-1"><b>Koli :</b> {{ $package->total_koli }}</p>
                    <p class="mb-1"><b>COD? :</b> {{ $package->cod_price > 0 ? "YES":"NO"}}</p>
                    <p class="mb-1"><b>COD Amount :</b> {{ number_format($package->cod_price) }}</p>
                </div>
                <div class="col-xl-6 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
                    <h6 class="mb-3">COURIER DETAILS</h6>
                    <hr/>
                    <p class="mb-1"><b>Courier Name :</b> {{ $routing->courier_name ?? "" }} </p>
                    <p class="mb-1"><b>Courier Id :</b> {{ $routing->courier_id ?? "" }} </p>
                    <p class="mb-1"><b>Delivery Record :</b> {{ $routing->code ?? "" }} </p>
                </div>
            </div>
            <div class="row p-sm-3 p-0">
                <div class="col-xl-6">
                    <p class="mb-1"><b>E-Signature</b> </p>
                    <div class="border rounded p-4 pb-3"></div>
                </div>
                <div class="col-xl-6">
                    <p class="mb-1"><b>Photo</b></p>
                    <div class="border rounded p-4 pb-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection