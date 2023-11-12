@extends('layouts.main')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Waybill List</h5>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="popover"
                data-bs-placement="right"
                data-bs-content="This is a very beautiful popover, show some love.">
                <path d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z" stroke="#E5E5E5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <form class="filter-card d-flex align-items-center flex-column flex-md-row flex-lg-row">
            <label class="label-filter-card" for="date-filter" style="margin: 0px 8px;">Created&nbsp;Waybill:</label>
            <div class="input-group input-group-merge datePickerGroup">
                <input type="text" class="form-control date" name="date" placeholder="YYYY-MM-DD" id="search-date" value="{{ $date }}" />
                <span class="input-group-text" data-toggle>
                <i class="ti ti-calendar-event cursor-pointer"></i>
                </span>    
            </div>
            <label class="label-filter-card" for="origin-filter" style="margin: 0px 8px;">Filter&nbsp;Status:</label>
            <select class="form-select" id="status">
                @foreach($status as $stats)
                    <option value="{{ $stats->status_id }}">{{ $stats->name }}</option>
                @endforeach
            </select>
            <label class="label-filter-card" for="origin-filter" style="margin: 0px 8px;">Origin&nbsp;Hub:</label>
            <select class="form-select" id="hub">
                @foreach($hub as $hubs)
                    <option value="{{ $hubs->hub_id }}">{{ $hubs->name }}</option>
                @endforeach
            </select>
        </form>
        <div class="card-datatable text-nowrap table-responsive">
            <table class="table table-custom-default" id="serverside">
                <thead>
                    <tr>
                    <th>Waybill</th>
                    <th>Location</th>
                    <th>Origin Hub</th>
                    <th>Destination Hub</th>
                    <th>Status</th>
                    <th>Created Via</th>
                    <th>Created Date</th>
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
        $(document).ready(function () {
            load();
        })

        function load(){
            var date = $('#search-date').val();
            if(date != "")
                var url = "{{ route('waybill-list') }}?date="+date
            else
                var url = "{{ route('waybill-list') }}"

            $('#serverside').DataTable({
                processing: true,
                order: [[6, 'desc']],
                ajax: { url : url },
                columns: [
                    { data: 'waybill', name: 'waybill' },
                    { data: 'location', name: 'location' },
                    { data: 'origin_hub', name: 'origin_hub' },
                    { data: 'destination_hub', name: 'destination_hub' },
                    { data: 'status', name: 'status' },
                    { data: 'created_via', name: 'created_via' },
                    { data: 'created_date', name: 'created_date' },
                    { data: 'action', name: 'action' }
                ],
            });
        }

        $('#search-date').change(function()
        {
            var date = $('#search-date').val();
            // var url = "{{ request()->getRequestUri() }}";

            // if(url.includes("?"))
            //     window.location.href = "{{ route('waybill-list') }}?date="+date
            // else
                window.location.href = "{{ route('waybill-list') }}?date="+date
        });
    </script>
@endsection