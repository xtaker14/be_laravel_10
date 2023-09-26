@extends('layouts/layoutMaster')

@section('title', 'DataTables - Advanced Tables')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<!-- Flat Picker -->
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>

@endsection

@section('page-script')
<script src="{{asset('assets/js/tables-datatables-advanced.js')}}"></script>
@endsection

@section('content')
<!-- Column Search -->
<div class="card">
  <h5 class="card-header">Waybill List</h5>
  <div class="card-datatable text-nowrap table-responsive">
    <table class="dt-column-search table">
      <thead>
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
<!--/ Column Search -->

@endsection
