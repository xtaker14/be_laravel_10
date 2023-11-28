@extends('layouts.main')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Delivery Record</h5>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="popover"
                data-bs-placement="right"
                data-bs-content="This is a very beautiful popover, show some love.">
                <path d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z" stroke="#E5E5E5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
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
        <div class="d-flex">
            <div class="row">
                <div class="col-md-12">
                    <form id="addNewCCForm" class="row g-10" style="margin-left: 10px">
                        <div class="mb-3 col-lg-6 col-xl-5 col-12 mb-0">
                            <label class="form-label" for="form-repeater-1-1">Delivery Record</label>
                            <input name="waybill" type="text" id="code" class="form-control" value="{{ $header->code ?? ""}}" placeholder="DR-DTX001" />
                        </div>
                        <div class="mb-3 col-lg-6 col-xl-5 col-12 mb-0">
                            <label class="form-label" for="form-repeater-1-4">Change Courier</label>
                            <select class="form-select" name="courier" id="courier" aria-label="Default select example">
                                <option @if ($selected == "") selected="selected" @endif disbaled hidden>Select Courier</option>
                                @foreach($courier as $cc)
                                <option @if ($cc->courier_id == $selected) selected="selected" @endif
                                    value="{{ $cc->courier_id }}" data-tp="{{ $cc->vehicle_type}}">{{ $cc->full_name }}</option>
                                @endforeach;
                            </select>
                        </div>
                        <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                            <button type="button" class="btn btn-primary mt-4 update">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if($header != "")
        <div class="card-h100">
            <div class="card-body">
                <h5>Information</h5>
                <div class="row">
                    <div class="col-6">
                        <div class="d-flex">
                            <h6>Delivery Record : {{ $header->code ?? ""}}</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex">
                            <h6>Total Weight : {{ $header->total_weight ?? ""}}</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex">
                            <h6>Courier : {{ $header->courier ?? ""}}</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex">
                            <h6>Total COD : {{ number_format($header->total_cod,0,',','.') ?? ""}}</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex">
                            <h6>Total Waybill : {{ $header->total_waybill ?? ""}}</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex">
                            <h6>Status : {{ $header->status ?? ""}}</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex">
                            <h6>Total Koli : {{ $header->total_koli ?? ""}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="card-datatable text-nowrap table-responsive" style="margin-left: 25px">
            <table class="table table-hover text-nowrap">
                <h5>Record</h5>
                <thead class="table-light">
                    <tr>
                        <th>Waybill</th>
                        <th>District</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $datas)
                        <tr>
                            <td>{{ $datas->waybill }}</td>
                            <td>{{ $datas->district }}</td>
                            <td>
                                <button type="button" onclick="remove(this)" value="{{ $datas->detail_id }}" class="btn btn-danger waves-effect waves-light">
                                <i class="ti ti-trash cursor-pointer"></i>Drop</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Alert -->
        @if($message = Session::get('failed'))
        <div class="alert alert-danger alert-dismissible d-flex align-items-baseline" role="alert">
            <span class="alert-icon alert-icon-lg text-danger me-2">
                <i class="ti ti-user ti-sm"></i>
            </span>
            <div class="d-flex flex-column ps-1">
                <h5 class="alert-heading mb-2">Failed!</h5>
                <p class="mb-0">{{ $message }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        </div>
        @endif
        <!-- Alert -->
    </div>
</div>
@endsection

@section('scripts')
<script>   
    $('#code').change(function()
    {
        var code = $('#code').val();
        window.location.href = "{{ route('update-record') }}?code="+code
    });

    function remove(data)
	{
        var code = $('#code').val();
        Swal.fire({
            title: `Drop Waybill`,
            text: "Are you sure want to drop this waybill from "+code+" ?",
            icon: 'warning',
            type: "warning",
            showCancelButton: false,
            showDenyButton: false,
            confirmButtonText: "Yes, drop!",
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
        }).then((result) => {
            if(result.value === true) {
                var id = $(data).val();
                if(id == "" || id == null)
                    alert("Something went wrong");
                else
                {
                    var uri = "{{ route('drop-waybill') }}";
                    jQuery.ajax(
                    {
                        type: 'POST',
                        async: false,
                        dataType: "json",
                        url: uri,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id:id
                        },
                        beforeSend: function(jqXHR, settings)
                        {
                        },
                        success: function(result)
                        {
                            var msgs = result.split("*");
                            if(msgs[0] == "OK")
                            {
                                var row = data.parentNode.parentNode;
                                row.parentNode.removeChild(row);

                                Swal.fire({
                                    title: 'Success',
                                    text: 'Success Drop Waybill',
                                    icon: 'success',
                                    type: "success",
                                    showCancelButton: false,
                                    showDenyButton: false,
                                    customClass: {
                                        confirmButton: 'btn btn-primary me-3'
                                    },
                                    buttonsStyling: false
                                });  
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            alert(textStatus); 
                        }
                    });
                }
            }
        });
	}

    $('.update').on('click', function()
    {
        var code = $('#code').val();
        var courier = $('#courier').val();
        if(code == "" || code == null)
            alert("Code cannot be null");
        else if(courier == "" || courier == null)
            alert("Courier cannot be null");
        else
        {
            var uri = "{{ route('update-dr') }}";
            jQuery.ajax(
            {
                type: 'POST',
                async: false,
                dataType: "json",
                url: uri,
                data: {
                    "_token": "{{ csrf_token() }}",
                    code:code,
                    courier:courier
                },
                beforeSend: function(jqXHR, settings)
                {
                },
                success: function(result)
                {
                    var msgs = result.split("*");
                    if(msgs[0] == "OK")
                    {
                        Swal.fire({
                            title: 'Success',
                            text: 'Success Update Courier',
                            icon: 'success',
                            type: "success",
                            showCancelButton: false,
                            showDenyButton: false,
                            customClass: {
                                confirmButton: 'btn btn-primary me-3'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if(msgs[0] == "OK")
                            {
                                window.location.href = "{{ route('update-record') }}"
                            }
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    alert(textStatus); 
                }
            });
        }
    });
</script>
@endsection