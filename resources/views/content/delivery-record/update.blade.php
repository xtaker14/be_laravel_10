@extends('layouts.main')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Delivery Record</h5>
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
                    <form id="addNewCCForm" class="row g-3" action="{{ route('update-record') }}" method="get">
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
                            <button class="btn btn-primary mt-4" data-repeater-delete>
                                <span class="align-middle">Update</span>
                            </button>
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
        <div class="card-datatable text-nowrap table-responsive">
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
        Swal.fire({
            title: `Drop`,
            text: "Are you sure wants to drop ?",
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
                var uri = "{{ route('update-dr') }}";
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
</script>
@endsection