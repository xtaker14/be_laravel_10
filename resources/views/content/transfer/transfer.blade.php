@extends('layouts.main')
@section('styles')
<style>
    table.scroll tbody,
    table.scroll thead { display: block; }

    table.scroll tbody {
        height: 100px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .print-container, .print-container * {
            visibility: visible;
        }
    }
</style>    
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Transfer</h5>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="popover"
                data-bs-placement="right"
                data-bs-content="This is a very beautiful popover, show some love.">
                <path d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z" stroke="#E5E5E5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <form class="filter-card ms-auto d-flex align-items-center flex-column flex-md-row flex-lg-row">
                <label class="label-filter-card" for="date-filter" style="margin: 0px 8px;">Created&nbsp;Waybill:</label>
                <div class="input-group input-group-merge datePickerGroup">
                    <input type="text" class="form-control date" name="date" placeholder="YYYY-MM-DD" id="search-date" value="{{ $date }}" />
                    <span class="input-group-text" data-toggle>
                    <i class="ti ti-calendar-event cursor-pointer"></i>
                    </span>    
                </div>
                <label class="label-filter-card" for="origin-filter" style="margin: 0px 8px;">Origin&nbsp;Hub:</label>
                <select class="form-select" id="hub">
                    @foreach($hubuser as $hubs)
                        <option value="{{ $hubs->hub_id }}">{{ $hubs->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="d-flex flex-wrap gap-2 pt-3 mb-0 mb-md-4">
            <div class="row">
                <div class="col-md-6">
                    <form id="addNewCCForm" class="row g-3">
                        <div class="mb-6">
                            <label for="hub_dest" class="form-label">Destination Hub</label>
                            <select class="form-select" name="hub_dest" id="hub_dest" aria-label="Default select example">
                            @foreach($hub as $h)
                                @if($h->hub_id != $usershub->hub_id)
                                <option value="{{$h->hub_id}}">{{ $h->name }}</option>
                                @endif;
                            @endforeach;
                            </select>
                        </div>
                        <div class="mb-6">
                            <label for="waybill" class="form-label">Waybill ID</label>
                            <input
                            type="text"
                            class="form-control"
                            id="waybill"
                            name="waybill"
                            placeholder="DTX00000" />
                            <input type="hidden" id="transfer_id">
                        </div>
                        <div class="mb-6">
                            <input
                            type="hidden"
                            class="form-control"
                            id="hub_origin"
                            name="hub_origin"
                            value="{{ $usershub->hub_id }}" />
                        </div>
                        <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                            <button type="button" class="btn btn-primary me-sm-3 me-1 finish">Finish</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header"></div>
                        <div class="card-body">
                        <table class="table table-borderless table-responsive scroll" id="counter">
                            <h5>Counter : <span id="counter_num"></span></h5>                            
                            <tbody>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-datatable text-nowrap table-responsive">
            <table class="table table-custom-default" id="serverside">
                <thead>
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

<div class="modal fade" id="qrcode" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="card print-container">
                    <div class="card-header d-flex justify-content-between">
                        <img src="{{ asset('template/assets/img/website/dethix-logo.svg') }}" />
                        <span style="font-weight: bold; color: #203864; text-align: right">M-BAG ID <p style="color: #444;" id="mbag_val"></p></span>
                    </div>
                    <div class="card-body text-center">
                        <div class="text-center mb-4" id="qrValue"></div>
                        <p style="font-size: 12px; margin: 0px" id="from_hub">From Hub : </span><span id="from_hub_val"></span>
                        <p style="font-size: 12px; margin: 0px" id="to_hub">To Hub : </span><span id="to_hub_val"></span>
                        <p style="font-size: 12px; margin: 0px" id="total_waybill">Total Waybill : </span><span id="total_waybill_val"></span>
                        <p style="font-size: 12px" id="transfer_date">Transfer Date : </span><span id="transfer_date_val"></span>
                    </div>
                </div>
            </div>
            <button class="btn btn-default" onclick="window.print();">Print</button>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    
    document.getElementById('hub').value = "{{ $usershub->hub_id }}";
    var counter = 0;

    $(document).ready(function () {
        load();
    })

    function load(){
        var date = $('#search-date').val();
            if(date != "")
                var url = "{{ route('transfer') }}?date="+date
            else
                var url = "{{ route('transfer') }}"

        $('#serverside').DataTable({
            processing: true,
            ajax: { url : url},
            columns: [
                { data: 'code', name: 'code' },
                { data: 'total_waybill', name: 'total_waybill' },
                { data: 'total_koli', name: 'total_koli' },
                { data: 'total_weight', name: 'total_weight' },
                { data: 'hub_origin', name: 'hub_origin' },
                { data: 'hub_dest', name: 'hub_dest' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' }
            ],
        });
    }

    $('#waybill').change(function()
    {
        var transfer_id = $('#transfer_id').val();
        var hub_origin = $('#hub_origin').val();
        var hub_dest   = $('#hub_dest').val();
        var waybill    = $('#waybill').val();
        if(hub_dest == "" || hub_dest == null)
			alert("Destination cannot be null");
        else if(hub_origin == "" || hub_origin == null)
			alert("Origin cannot be null");
        else if(waybill == "" || waybill == null)
			alert("Waybill cannot be null");
		else
		{
            var uri    = "{{ route('create-transfer') }}";
            jQuery.ajax(
            {
                type: 'POST',
                async: false,
                dataType: "json",
                url: uri,
                data: {
                    "_token": "{{ csrf_token() }}",
                    transfer_id:transfer_id,
                    hub_origin:hub_origin,
                    hub_dest:hub_dest,
                    waybill:waybill
                },
                beforeSend: function(jqXHR, settings)
                {
                },
                success: function(result)
                {
                    var msgs = result.split("*");
                    if(msgs[0] == "OK")
                    {
                        counter++;

                        document.getElementById('counter_num').innerHTML = counter;

                        var tablePreview = $("#counter tbody");
                        var strContent = "<tr>";
                        
                        strContent = strContent + "<td>" + msgs[1] + "<input type='hidden' name='nama[]' value="+ msgs[1] +"></td>";
                        strContent = strContent + "</tr>";
                        
                        document.getElementById('transfer_id').value = msgs[2];
                        tablePreview.prepend(strContent);
                        $("#waybill").val('');
                        setTimeout(function() { $("#waybill").focus() }, 500);
                    }
                    else
                    {
                        Swal.fire({
                            title: 'Failed',
                            text: msgs[1],
                            icon: 'error',
                            type: "error",
                            showCancelButton: false,
                            showDenyButton: false,
                            customClass: {
                                confirmButton: 'btn btn-primary me-3'
                            },
                            buttonsStyling: false
                        });

                        $("#waybill").val('');
                        setTimeout(function() { $("#waybill").focus() }, 500);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    alert(textStatus); 
                }
            });
        }
    });

    $('.finish').on('click', function()
    {
        Swal.fire({
            title: 'Success',
            text: 'Transfer Finish !',
            icon: 'success',
            type: "success",
            showCancelButton: false,
            showDenyButton: false,
            customClass: {
                confirmButton: 'btn btn-primary me-3'
            },
            buttonsStyling: false
        }).then((result) => {
            location.reload();
        });
    });

    $('#search-date').change(function()
    {
        var date = $('#search-date').val();
        window.location.href = "{{ route('transfer') }}?date="+date
    });

    $("#addNewCCForm").submit(function() {
        search($("#search").get(0));
        return false;
    });

    $('#hub').change(function()
    {
        var hub = $('#hub').val();
        window.location.href = "{{ route('transfer') }}?hub="+hub
    });

    function qrcode(id) {
        var uri    = "{{ route('get-qr-transfer') }}";
        jQuery.ajax(
        {
            type: 'POST',
            async: false,
            dataType: "json",
            url: uri,
            data: {
                "_token": "{{ csrf_token() }}",
                id:id,
            },
            beforeSend: function(jqXHR, settings)
            {
            },
            success: function(result)
            {
                var msgs = result.split("*");
                if(msgs[0] == "OK")
                {
                    document.getElementById('mbag_val').innerHTML = msgs[1];
                    document.getElementById('from_hub_val').innerHTML = msgs[2];
                    document.getElementById('to_hub_val').innerHTML = msgs[3];
                    document.getElementById('total_waybill_val').innerHTML = msgs[4];
                    document.getElementById('transfer_date_val').innerHTML = msgs[5];
                    document.getElementById('qrValue').innerHTML = msgs[6];
                }
                else
                {
                    Swal.fire({
                        title: 'Failed',
                        text: msgs[1],
                        icon: 'error',
                        type: "error",
                        showCancelButton: false,
                        showDenyButton: false,
                        customClass: {
                            confirmButton: 'btn btn-primary me-3'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        location.reload();
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                alert(textStatus); 
            }
        });
    }
    </script>
@endsection