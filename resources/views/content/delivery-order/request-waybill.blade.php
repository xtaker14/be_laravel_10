@extends('layouts/layoutMaster')

@section('title', 'Request Waybill')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
@endsection

@section('vendor-script')
<!-- Flat Picker -->
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>

@endsection

@section('page-script')
<script src="{{asset('assets/js/datatable.js')}}"></script>
@endsection

@section('content')
<!-- Column Search -->
<div class="card">
  <div class="card-header header-elements">
    <span class="me-5">Request Waybill</span>
    <div class="card-header-elements ms-auto">
      <span class="text d-flex">
      <div class="mb-0">
      <input type="text" class="form-control dt-date flatpickr-date dt-input" name="dt_date" placeholder="YYYY-MM-DD" id="flatpickr-date" />
        <input type="text" class="form-control dt-date flatpickr-range dt-input" data-column="5" placeholder="StartDate to EndDate" data-column-index="4" name="dt_date" />
        <input type="hidden" class="form-control dt-date start_date dt-input" data-column="5" data-column-index="4" name="value_from_start_date" />
        <input type="hidden" class="form-control dt-date end_date dt-input" name="value_from_end_date" data-column="5" data-column-index="4" />
      </div>
      </span>
      <span class="text d-flex">
        <small>Origin Hub</small>
        <select class="form-select">
          <option selected="">Option 1</option>
          <option>Option 2</option>
          <option>Option 3</option>
        </select>
      </span>
    </div>
  </div>
  <div class="card-body d-flex justify-content-right align-right">
    <button type="button" class="btn btn-primary">Primary</button>
    <button type="button" class="btn btn-primary">Primary</button>
  </div>
  <div class="card-datatable text-nowrap">
    <table class="dt-column-search table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Post</th>
          <th>City</th>
          <th>Date</th>
          <th>Salary</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Post</th>
          <th>City</th>
          <th>Date</th>
          <th>Salary</th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<!--/ Column Search -->

@endsection
