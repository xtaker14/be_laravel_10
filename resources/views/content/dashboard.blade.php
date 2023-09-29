@extends('layouts.main')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4">Dashboard</h4>

    <!-- Card Border Shadow -->
    <div class="row">
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-primary">
                <div class="card-body">
                <div class="d-flex align-items-center mb-2 pb-1">
                    <div class="avatar me-2">
                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-truck ti-md"></i></span>
                    </div>
                    <h4 class="ms-1 mb-0">42</h4>
                </div>
                <p class="mb-1">On route vehicles</p>
                <p class="mb-0">
                    <span class="fw-medium me-1">+18.2%</span>
                    <small class="text-muted">than last week</small>
                </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-warning">
                <div class="card-body">
                <div class="d-flex align-items-center mb-2 pb-1">
                    <div class="avatar me-2">
                    <span class="avatar-initial rounded bg-label-warning"
                        ><i class="ti ti-alert-triangle ti-md"></i
                    ></span>
                    </div>
                    <h4 class="ms-1 mb-0">8</h4>
                </div>
                <p class="mb-1">Vehicles with errors</p>
                <p class="mb-0">
                    <span class="fw-medium me-1">-8.7%</span>
                    <small class="text-muted">than last week</small>
                </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-danger">
                <div class="card-body">
                <div class="d-flex align-items-center mb-2 pb-1">
                    <div class="avatar me-2">
                    <span class="avatar-initial rounded bg-label-danger"
                        ><i class="ti ti-git-fork ti-md"></i
                    ></span>
                    </div>
                    <h4 class="ms-1 mb-0">27</h4>
                </div>
                <p class="mb-1">Deviated from route</p>
                <p class="mb-0">
                    <span class="fw-medium me-1">+4.3%</span>
                    <small class="text-muted">than last week</small>
                </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-info">
                <div class="card-body">
                <div class="d-flex align-items-center mb-2 pb-1">
                    <div class="avatar me-2">
                    <span class="avatar-initial rounded bg-label-info"><i class="ti ti-clock ti-md"></i></span>
                    </div>
                    <h4 class="ms-1 mb-0">13</h4>
                </div>
                <p class="mb-1">Late vehicles</p>
                <p class="mb-0">
                    <span class="fw-medium me-1">-2.5%</span>
                    <small class="text-muted">than last week</small>
                </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection