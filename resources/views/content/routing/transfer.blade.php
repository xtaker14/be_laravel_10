@extends('layouts.main')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Transfer</h5>
        </div>
        <div class="d-flex flex-wrap gap-2 pt-3 mb-0 mb-md-4">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('create-dr') }}" id="addNewCCForm" class="row g-3" method="post" enctype="multipart/form-data">
                    @csrf
                        <div class="mb-6">
                            <label for="hub_dest" class="form-label">Destination Hub</label>
                            <select class="form-select" id="hub_dest" aria-label="Default select example">
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
                        </div>
                        <div class="mb-6">
                            <input
                            type="hidden"
                            class="form-control"
                            id="hub_origin"
                            name="hub_origin"
                            value="{{ $usershub->hub_id }}" />
                            <input type="hidden" id="transfer_id">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 finish">Finish</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header"></div>
                        <div class="card-body">
                        <table class="table table-borderless table-responsive" id="counter">
                            <h5>Counter : </h5>                            
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
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        load();
    })

    function load(){
        $('#serverside').DataTable({
            processing: true,
            ajax: { url :"{{ route('transfer') }}"},
            columns: [
                { data: 'master_waybill', name: 'master_waybill' },
                { data: 'filename', name: 'filename' },
                { data: 'total_waybill', name: 'total_waybill' },
                { data: 'upload_time', name: 'upload_time' },
                { data: 'upload_by', name: 'upload_by' },
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
            text: 'Success Transfer',
            icon: 'success',
            type: "success",
            showCancelButton: false,
            showDenyButton: false,
            customClass: {
                confirmButton: 'btn btn-primary me-3'
            },
            buttonsStyling: false
        });

        location.reload();
    });
    </script>
@endsection