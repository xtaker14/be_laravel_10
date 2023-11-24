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
</style>    
@endsection
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Inbound</h5>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="popover"
                data-bs-placement="right"
                data-bs-content="This is a very beautiful popover, show some love.">
                <path d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z" stroke="#E5E5E5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <form class="filter-card ms-auto d-flex align-items-center flex-column flex-md-row flex-lg-row">
                <label class="label-filter-card" for="date-filter" style="margin: 0px 8px;">Date:</label>
                <div class="input-group input-group-merge datePickerGroup">
                    <input type="text" class="form-control date" name="date" placeholder="YYYY-MM-DD" id="search-date" value="{{ $date }}" />
                    <span class="input-group-text" data-toggle>
                    <i class="ti ti-calendar-event cursor-pointer"></i>
                    </span>    
                </div>
                <label class="label-filter-card" for="origin-filter" style="margin: 0px 8px;">Origin&nbsp;Hub:</label>
                <select class="form-select" id="hub">
                    @foreach($hub as $hubs)
                        <option value="{{ $hubs->hub_id }}">{{ $hubs->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="d-flex flex-wrap gap-2 pt-3 mb-0 mb-md-4">
            <div class="row">
                <div class="col-md-6">
                    <form id="addNewCCForm" class="row g-3">
                        <div class="mb-3">
                            <label for="courier" class="form-label">Inbound Type</label>
                            <select class="form-select" name="type" id="type" aria-label="Default select example">
                                @foreach($inboundtype as $type)
                                    <option value="{{ $type->name }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3" id="dlrecord-div" style="display:none;">
                            <label for="dlrecord" class="form-label">Delivery Record</label>
                            <input
                            type="text"
                            class="form-control"
                            id="dlrecord"
                            name="dlrecord"
                            placeholder="Delivery Record"/>
                            <input type="hidden" id="dr_id">
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input
                            class="form-control"
                            type="text"
                            name="location"
                            id="location"
                            placeholder="Location Grid"/>
                        </div>
                        <div class="mb-3" id="waybill-div">
                            <label for="waybill" class="form-label">Waybill</label>
                            <input
                            type="text"
                            class="form-control"
                            id="waybill"
                            name="waybill"
                            placeholder="DTX00000"/>
                            <input type="hidden" id="inbound_id">
                        </div>
                        <div class="mb-3" id="waybill-undlv-div" style="display:none;">
                            <label for="waybill-undlv" class="form-label">Waybill</label>
                            <input
                            type="text"
                            class="form-control"
                            id="waybill-undlv"
                            name="waybill-undlv"
                            placeholder="DTX00000"/>
                            <input type="hidden" id="inbound_id">
                        </div>
                        <div class="mb-3" id="mbag-div" style="display:none;">
                            <label for="mbag" class="form-label">M-BAG</label>
                            <input
                            type="text"
                            class="form-control"
                            id="mbag"
                            name="mbag"
                            placeholder="MBAG-00000"/>
                            <input type="hidden" id="inbound_id">
                        </div>
                        <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                            <button type="button" class="btn btn-primary me-sm-3 me-1 received">Received</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <table class="table table-borderless table-responsive" id="summary">                                                     
                                <tbody>
                                </tbody>
                            </table>
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
                    <th>Inbound Id</th>
                    <th>Total Waybill</th>
                    <th>Type</th>
                    <th>Inbound Date</th>
                    <th>Created By</th>
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
    var counter = 0;

    $(document).ready(function () {
        load();
    })

    function load(){
        var date = $('#search-date').val();
        if(date != "")
            var url = "{{ route('inbound') }}?date="+date
        else
            var url = "{{ route('inbound') }}"

        $('#serverside').DataTable({
            processing: true,
            order: [[3, 'desc']],
            ajax: { url : url },
            columns: [
                { data: 'inbound_id', name: 'inbound_id' },
                { data: 'total_waybill', name: 'total_waybill' },
                { data: 'type', name: 'type' },
                { data: 'inbound_date', name: 'inbound_date' },
                { data: 'created_by', name: 'created_by' },
                { data: 'action', name: 'action' }
            ],
        });
    }

    $("#type").change(function()
    {
        if(document.getElementById("type").value == "TRANSFER")
        {
            $("#waybill-div").attr("style", "display:none");
            $("#waybill-undlv-div").attr("style", "display:none");
            $("#mbag-div").removeAttr("style");
            $("#dlrecord-div").attr("style", "display:none");
            $('#location').prop('disabled', false);
            $('#waybill').prop('disabled', false);
        }
        else if(document.getElementById("type").value == "UNDELIVERED WAYBILL")
        {
            $("#mbag-div").attr("style", "display:none");
            $("#waybill-div").attr("style", "display:none");
            $("#waybill-undlv-div").removeAttr("style");
            $("#dlrecord-div").removeAttr("style");
            $('#location').prop('disabled', true);
            $('#waybill-undlv').prop('disabled', true);
        }
        else
        {
            $("#waybill-undlv-div").attr("style", "display:none");
            $("#mbag-div").attr("style", "display:none");
            $("#waybill-div").removeAttr("style");   
            $("#dlrecord-div").attr("style", "display:none");
            $('#location').prop('disabled', false);
            $('#waybill').prop('disabled', false);
        }

        $('#summary tbody').empty();
        $('#counter_num').empty();
        $('#counter tbody').empty();
        $("#dlrecord").val('');
        $('#location').val('');
        $('#mbag').val('');
        $('#waybill').val('');
        $('#waybill-undlv').val('');

        counter = 0;
    });
    
    $("#location").change(function(){
        var type       = $('#type').val();
        if(type == "TRANSFER")
            setTimeout(function() { $("#mbag").focus() }, 500);
        else
            setTimeout(function() { $("#waybill").focus() }, 500);
    });

    $("#dlrecord").change(function()
    {
        var dlrecord = $('#dlrecord').val();
        if(dlrecord == "" || dlrecord == null)
        {
			alert("Delivery Record cannot be null");
            $("#dlrecord").val('');
        }
		else
		{
            var uri    = "{{ route('check-delivery-record') }}";
            jQuery.ajax(
            {
                type: 'POST',
                async: false,
                dataType: "json",
                url: uri,
                data: {
                    "_token": "{{ csrf_token() }}",
                    dlrecord:dlrecord,
                },
                beforeSend: function(jqXHR, settings)
                {
                },
                success: function(result)
                {
                    var msgs = result.split("*");
                    if(msgs[0] == "OK")
                    {
                        var tablePreview = $("#summary tbody");

                        var summary = JSON.parse(msgs[1]);
                        for (const [key, value] of Object.entries(summary)){
                            var strContent = "<tr>";
                            strContent = strContent + "<td>" + key + " : " + value + "<input type='hidden' name='nama[]' value="+ value +"></td>";
                            strContent = strContent + "</tr>";
                            tablePreview.prepend(strContent);
                        }

                        document.getElementById('dr_id').value = msgs[2];

                        $('#dlrecord').prop('disabled', true);
                        $('#location').prop('disabled', false);
                        $('#waybill-undlv').prop('disabled', false);
                        
                        $("#waybill-undlv").val('');
                        setTimeout(function() { $("#location").focus() }, 500);
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

                        $("#dlrecord").val('');
                        setTimeout(function() { $("#dlrecord").focus() }, 500);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    alert(textStatus); 
                }
            });
        }
    });

    $('#waybill').change(function()
    {
        var hub        = $('#hub').val();
        var type       = $('#type').val();
        var inbound_id = $('#inbound_id').val();
        var location   = $('#location').val();
        var waybill    = $('#waybill').val();
        if(location == "" || location == null)
        {
			alert("Location cannot be null");
            $("#waybill").val('');
        }
        else if(hub == "" || hub == null)
        {
            alert("Hub cannot be null");
            $("#waybill").val('');
        }
        else if(type == "" || type == null)
        {
            alert("Type cannot be null");
            $("#waybill").val('');
        }
        else if(waybill == "" || waybill == null)
        {
            alert("Waybill cannot be null");
            $("#waybill").val('');
        }
		else
		{            
            var uri = "{{ route('create-inbound') }}";

            jQuery.ajax(
            {
                type: 'POST',
                async: false,
                dataType: "json",
                url: uri,
                data: {
                    "_token": "{{ csrf_token() }}",
                    type:type,
                    hub:hub,
                    location:location,
                    waybill:waybill,
                    inbound_id:inbound_id
                },
                beforeSend: function(jqXHR, settings)
                {
                },
                success: function(result)
                {
                    var msgs = result.split("*");
                    if(msgs[0] == "OK")
                    {            
                        var tablePreview = $("#summary tbody");
                        
                        counter++;

                        document.getElementById('counter_num').innerHTML = counter;

                        var summary = JSON.parse(msgs[3]);
                        if ($('#summary td:contains("From")').length < 1) {
                            for (const [key, value] of Object.entries(summary)){
                                var strContent = "<tr>";
                                strContent = strContent + "<td>" + key + " : " + value + "<input type='hidden' name='summary[]' value="+ value +"></td>";
                                strContent = strContent + "</tr>";
                                tablePreview.prepend(strContent);
                            }
                        }

                        var tablePreview = $("#counter tbody");
                        var strContent = "<tr>";
                        
                        strContent = strContent + "<td>" + msgs[1] + "<input type='hidden' name='nama[]' value="+ msgs[1] +"></td>";
                        strContent = strContent + "</tr>";
                        
                        document.getElementById('inbound_id').value = msgs[2];
                        tablePreview.prepend(strContent);
                        $('#location').prop('disabled', true);
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

    $('#waybill-undlv').change(function()
    {
        var hub        = $('#hub').val();
        var type       = $('#type').val();
        var inbound_id = $('#inbound_id').val();
        var location   = $('#location').val();
        var waybill    = $('#waybill-undlv').val();
        var dlrecord   = $('#dlrecord').val();
        var dr_id      = $('#dr_id').val();
        if(location == "" || location == null)
        {
			alert("Location cannot be null");
            $("#waybill-undlv").val('');
        }
        else if(hub == "" || hub == null)
        {
            alert("Hub cannot be null");
            $("#waybill-undlv").val('');
        }
        else if(type == "" || type == null)
        {
            alert("Type cannot be null");
            $("#waybill-undlv").val('');
        }
        else if(waybill == "" || waybill == null)
        {
            alert("Waybill cannot be null");
            $("#waybill-undlv").val('');
        }
        else if(dlrecord == "" || dlrecord == null)
        {
            alert("Delivery Record cannot be null")
            $("#waybill-undlv").val('');
        }
		else
		{
            var uri = "{{ route('create-inbound-undelivered') }}";

            jQuery.ajax(
            {
                type: 'POST',
                async: false,
                dataType: "json",
                url: uri,
                data: {
                    "_token": "{{ csrf_token() }}",
                    type:type,
                    hub:hub,
                    location:location,
                    waybill:waybill,
                    inbound_id:inbound_id,
                    dlrecord:dlrecord,
                    dr_id:dr_id
                },
                beforeSend: function(jqXHR, settings)
                {
                },
                success: function(result)
                {
                    var msgs = result.split("*");
                    if(msgs[0] == "OK")
                    {
                        $('#counter_num').empty();
                        
                        counter++;

                        document.getElementById('counter_num').innerHTML = counter;

                        var tablePreview = $("#counter tbody");
                        var strContent = "<tr>";
                        
                        strContent = strContent + "<td>" + msgs[1] + "<input type='hidden' name='nama[]' value="+ msgs[1] +"></td>";
                        strContent = strContent + "</tr>";
                        
                        document.getElementById('inbound_id').value = msgs[2];
                        tablePreview.prepend(strContent);
                        $('#location').prop('disabled', true);
                        $("#waybill-undlv").val('');
                        setTimeout(function() { $("#waybill-undlv").focus() }, 500);
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

                        $("#waybill-undlv").val('');
                        setTimeout(function() { $("#waybill-undlv").focus() }, 500);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    alert(textStatus); 
                }
            });
        }
    });

    $('#mbag').change(function()
    {
        var hub        = $('#hub').val();
        var type       = $('#type').val();
        var inbound_id = $('#inbound_id').val();
        var location   = $('#location').val();
        var mbag       = $('#mbag').val();
        if(location == "" || location == null)
        {
			alert("Location cannot be null");
            $("#mbag").val('');
        }
        else if(mbag == "" || mbag == null)
        {
            alert("Waybill cannot be null");
            $("#mbag").val('');
        }
        else if(hub == "" || hub == null)
        {
            alert("Hub cannot be null");
            $("#mbag").val('');
        }
        else if(type == "" || type == null)
        {
            alert("Type cannot be null");
            $("#mbag").val('');
        }
		else
		{
            var uri    = "{{ route('create-inbound-transfer') }}";
            jQuery.ajax(
            {
                type: 'POST',
                async: false,
                dataType: "json",
                url: uri,
                data: {
                    "_token": "{{ csrf_token() }}",
                    hub:hub,
                    type:type,
                    location:location,
                    mbag:mbag,
                    inbound_id:inbound_id
                },
                beforeSend: function(jqXHR, settings)
                {
                },
                success: function(result)
                {
                    var msgs = result.split("*");
                    if(msgs[0] == "OK")
                    {
                        $('#summary tbody').empty();
                        
                        counter++;

                        document.getElementById('counter_num').innerHTML = counter;

                        var tablePreview = $("#summary tbody");

                        var summary = JSON.parse(msgs[3]);
                        for (const [key, value] of Object.entries(summary)){
                            var strContent = "<tr>";
                            strContent = strContent + "<td>" + key + " : " + value + "<input type='hidden' name='nama[]' value="+ value +"></td>";
                            strContent = strContent + "</tr>";
                            tablePreview.prepend(strContent);
                        }

                        var tablePreview = $("#counter tbody");
                        
                        var package = JSON.parse(msgs[1]);
                        for (var i = 0; i < package.length; i++){
                            var strContent = "<tr>";
                            strContent = strContent + "<td>" + package[i] + "<input type='hidden' name='nama[]' value="+ package[i] +"></td>";
                            strContent = strContent + "</tr>";
                            tablePreview.prepend(strContent);
                        }
                        
                        document.getElementById('inbound_id').value = msgs[2];
                        $("#mbag").val('');
                        setTimeout(function() { $("#mbag").focus() }, 500);
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

                        $("#mbag").val('');
                        setTimeout(function() { $("#mbag").focus() }, 500);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    alert(textStatus); 
                }
            });
        }
    });

    $('.received').on('click', function()
    {
        var inbound_id     = $('#inbound_id').val();
        if(inbound_id == "" || inbound_id == null)
        {
            Swal.fire({
                title: 'Failed',
                text: 'No data to inbound',
                icon: 'error',
                type: "failed",
                showCancelButton: false,
                showDenyButton: false,
                customClass: {
                    confirmButton: 'btn btn-primary me-3'
                },
                buttonsStyling: false
            });
        }
        else
        {
            Swal.fire({
                title: 'Success',
                text: 'Success Received Inbound',
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
        }
    });

    $('#search-date').change(function()
    {
        var date = $('#search-date').val();
        window.location.href = "{{ route('inbound') }}?date="+date
    });
</script>
@endsection