@extends('layouts.main')

@section('styles')
<link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<style>
    .filter-card .label-filter-card{
        font-size: 14px;
        font-style: normal;
        font-weight: 700; 
        margin: 0px 16px;
    }
    .filter-card #created-filter{
        min-width: 300px;
    }
    .summary-dashboard .card-status{
        padding: 16px 24px;
        background: #FFF;
        box-shadow: 0px 4px 24px 0px rgba(0, 0, 0, 0.06) !important;
        margin-bottom: 40px;
    }
    .summary-dashboard .card-status .icon-bg{
        padding: 12px;
    }
    .summary-dashboard .card-status .content-right h4{
        color: #4C4F54;
        font-size: 24px;
        font-style: normal;
        font-weight: 700;
    }
    .summary-dashboard .card-status .content-right p{
        color: #4C4F54;
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
    }
    .summary-dashboard .card-total-waybill{
        height: 200px;
    }
    .filter-tracking{
        margin-bottom: 30px;
    }
    .order-tracking .card-order-information{
        border-radius: 6px;
        background: #FFF;
        box-shadow: 0px 4px 24px 0px rgba(0, 0, 0, 0.06);
    }
    .order-tracking .card-order-information .card-header{
        padding: 24px;
    }
    .order-tracking .card-order-information h5{
        color: #4C4F54;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        margin-bottom: 0px;
    }
    .order-tracking .card-order-information dl dt{
        color: #4C4F54;
        font-size: 14px;
        font-style: normal;
        font-weight: 700;
    }
    .order-tracking .card-order-information dl dd{
        color: #4C4F54;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        margin-left: 5px;
    }
    .order-tracking .card-delivery-history{
        border-radius: 6px;
        background: #FFF;
        box-shadow: 0px 4px 24px 0px rgba(0, 0, 0, 0.06);
    }
    .order-tracking .card-delivery-history .card-header{
        padding: 24px;
    }
    .order-tracking .card-delivery-history h5{
        color: #4C4F54;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        margin-bottom: 0px;
    }
    .order-tracking .card-delivery-history .table-responsive{
        max-height: 220px;
        overflow: auto;
    }
    .order-tracking .card-delivery-history table{
        border: 1px solid#EBE9F1;
    }
    .order-tracking .card-delivery-history table thead{
        background:#E2EAF4;
    }
    .order-tracking .card-delivery-history table thead tr th{
        color: #4C4F54;
        font-size: 12px;
        font-style: normal;
        font-weight: 700;
        line-height: 16px; 
        padding: 6px 16px;
    }
    .order-tracking .card-delivery-history table tbody tr td{
        color:#4C4F54;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        padding: 11px 16px;
    }
    .record-tracking .card-record-information{
        border-radius: 6px;
        background: #FFF;
        box-shadow: 0px 4px 24px 0px rgba(0, 0, 0, 0.06);
    }
    .record-tracking .card-record-information .card-header{
        padding: 24px;
    }
    .record-tracking .card-record-information h5{
        color: #4C4F54;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        margin-bottom: 0px;
    }
    .record-tracking .card-record-information dl dt{
        color: #4C4F54;
        font-size: 14px;
        font-style: normal;
        font-weight: 700;
    }
    .record-tracking .card-record-information dl dd{
        color: #4C4F54;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        margin-left: 5px;
    }
    .record-tracking .card-record-history{
        border-radius: 6px;
        background: #FFF;
        box-shadow: 0px 4px 24px 0px rgba(0, 0, 0, 0.06);
    }
    .record-tracking .card-record-history .card-header{
        padding: 24px;
    }
    .record-tracking .card-record-history h5{
        color: #4C4F54;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        margin-bottom: 0px;
    }
    .record-tracking .card-record-history .table-responsive{
        max-height: 220px;
        overflow: auto;
    }
    .record-tracking .card-record-history table{
        border: 1px solid#EBE9F1;
    }
    .record-tracking .card-record-history table thead{
        background:#E2EAF4;
    }
    .record-tracking .card-record-history table thead tr th{
        color: #4C4F54;
        font-size: 12px;
        font-style: normal;
        font-weight: 700;
        line-height: 16px; 
        padding: 6px 16px;
    }
    .record-tracking .card-record-history table tbody tr td{
        color:#4C4F54;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        padding: 11px 16px;
    }
</style> 
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom pb-0" id="card-summary">
        <div class="card-header d-flex flex-column flex-md-row flex-lg-row">
            <div class="title-card-page d-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M2.25 18L9 11.25L13.3064 15.5564C14.5101 13.1881 16.5042 11.2023 19.1203 10.0375L21.8609 8.81732M21.8609 8.81732L15.9196 6.53668M21.8609 8.81732L19.5802 14.7586" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h5 class="ms-2">Summary</h5>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="popover"
                data-bs-placement="right"
                data-bs-content="This is a very beautiful popover, show some love.">
                    <path d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z" stroke="#E5E5E5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <form action="{{ route('dashboard') }}" class="filter-card ms-auto d-flex align-items-center flex-column flex-md-row flex-lg-row" id="dashboard-form">
                <label class="label-filter-card" for="created-filter">Filter&nbsp;by&nbsp;Created&nbsp;Waybill:</label>
                <input type="text" id="created-filter" name="createdFilter" class="form-control" value="{{ request()->input('createdFilter') }}" />
                <label class="label-filter-card" for="origin-filter">Origin&nbsp;Hub:</label>
                <select id="origin-filter" class="form-select" name="originFilter">
                    <option value="" {{ request()->input('originFilter') == "" ? 'selected' : '' }}>All Hub</option>
                    @foreach ($hubs as $hub)
                        <option value="{{ $hub->hub_id }}" {{ request()->input('originFilter') == $hub->hub_id ? 'selected' : '' }}>{{ $hub->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="summary-dashboard">
            <div class="row" data-masonry='{"percentPosition": true }'>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-status card-total-waybill d-flex align-items-center justify-content-center">
                        <div class="d-flex flex-column align-items-center text-center gap-3 me-4 me-sm-0">
                            <span class="icon-bg bg-label-warning rounded-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                    <path d="M3.875 4.5C2.83947 4.5 2 5.33947 2 6.375V13.5H14V6.375C14 5.33947 13.1605 4.5 12.125 4.5H3.875Z" fill="#FFB000"/>
                                    <path d="M14 15H2V17.625C2 18.6605 2.83947 19.5 3.875 19.5H4.25C4.25 17.8431 5.59315 16.5 7.25 16.5C8.90685 16.5 10.25 17.8431 10.25 19.5H13.25C13.6642 19.5 14 19.1642 14 18.75V15Z" fill="#FFB000"/>
                                    <path d="M8.75 19.5C8.75 18.6716 8.07843 18 7.25 18C6.42157 18 5.75 18.6716 5.75 19.5C5.75 20.3284 6.42157 21 7.25 21C8.07843 21 8.75 20.3284 8.75 19.5Z" fill="#FFB000"/>
                                    <path d="M16.25 6.75C15.8358 6.75 15.5 7.08579 15.5 7.5V18.75C15.5 18.8368 15.5147 18.9202 15.5419 18.9977C15.7809 17.58 17.0143 16.5 18.5 16.5C20.1442 16.5 21.4794 17.8226 21.4998 19.462C22.3531 19.2869 23.0224 18.5266 22.964 17.5794C22.731 13.799 21.3775 10.321 19.2324 7.4749C18.878 7.00463 18.3265 6.75 17.7621 6.75H16.25Z" fill="#FFB000"/>
                                    <path d="M20 19.5C20 18.6716 19.3284 18 18.5 18C17.6716 18 17 18.6716 17 19.5C17 20.3284 17.6716 21 18.5 21C19.3284 21 20 20.3284 20 19.5Z" fill="#FFB000"/>
                                </svg>
                            </span>
                            <div class="content-right">
                                <h4 class="mb-0" id="sum-waybill">0</h4>
                                <p class="mb-0">Waybill</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-status">
                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="icon-bg bg-label-primary rounded-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M3.375 3C2.33947 3 1.5 3.83947 1.5 4.875V5.625C1.5 6.66053 2.33947 7.5 3.375 7.5H20.625C21.6605 7.5 22.5 6.66053 22.5 5.625V4.875C22.5 3.83947 21.6605 3 20.625 3H3.375Z" fill="#7367F0"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.08679 9L3.62657 18.1762C3.71984 19.7619 5.03296 21 6.62139 21H17.3783C18.9667 21 20.2799 19.7619 20.3731 18.1762L20.9129 9H3.08679ZM12 10.5C12.4142 10.5 12.75 10.8358 12.75 11.25V16.1893L14.4697 14.4697C14.7626 14.1768 15.2374 14.1768 15.5303 14.4697C15.8232 14.7626 15.8232 15.2374 15.5303 15.5303L12.5303 18.5303C12.2374 18.8232 11.7626 18.8232 11.4697 18.5303L8.46967 15.5303C8.17678 15.2374 8.17678 14.7626 8.46967 14.4697C8.76256 14.1768 9.23744 14.1768 9.53033 14.4697L11.25 16.1893V11.25C11.25 10.8358 11.5858 10.5 12 10.5Z" fill="#7367F0"/>
                                </svg>
                            </span>
                            <div class="content-right">
                                <h4 class="mb-0" id="sum-entry">0</h4>
                                <p class="mb-0">Entry</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-status">
                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="icon-bg bg-label-danger rounded-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.333 2.25C6.94823 2.25 2.58301 6.61522 2.58301 12C2.58301 17.3848 6.94823 21.75 12.333 21.75C17.7178 21.75 22.083 17.3848 22.083 12C22.083 6.61522 17.7178 2.25 12.333 2.25ZM10.6133 9.21967C10.3204 8.92678 9.84557 8.92678 9.55268 9.21967C9.25978 9.51256 9.25978 9.98744 9.55268 10.2803L11.2723 12L9.55268 13.7197C9.25978 14.0126 9.25978 14.4874 9.55268 14.7803C9.84557 15.0732 10.3204 15.0732 10.6133 14.7803L12.333 13.0607L14.0527 14.7803C14.3456 15.0732 14.8204 15.0732 15.1133 14.7803C15.4062 14.4874 15.4062 14.0126 15.1133 13.7197L13.3937 12L15.1133 10.2803C15.4062 9.98744 15.4062 9.51256 15.1133 9.21967C14.8204 8.92678 14.3456 8.92678 14.0527 9.21967L12.333 10.9393L10.6133 9.21967Z" fill="#FF6E5D"/>
                                </svg>
                            </span>
                            <div class="content-right">
                                <h4 class="mb-0" id="sum-rejected">0</h4>
                                <p class="mb-0">Rejected</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-status">
                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="icon-bg bg-label-success rounded-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                    <path d="M21.8409 9.63937C21.4874 9.27 21.1218 8.88938 20.984 8.55469C20.8565 8.24813 20.849 7.74 20.8415 7.24781C20.8274 6.33281 20.8124 5.29594 20.0915 4.575C19.3706 3.85406 18.3337 3.83906 17.4187 3.825C16.9265 3.8175 16.4184 3.81 16.1118 3.6825C15.7781 3.54469 15.3965 3.17906 15.0271 2.82562C14.3803 2.20406 13.6453 1.5 12.6665 1.5C11.6878 1.5 10.9537 2.20406 10.3059 2.82562C9.9365 3.17906 9.55588 3.54469 9.22119 3.6825C8.9165 3.81 8.4065 3.8175 7.91432 3.825C6.99932 3.83906 5.96244 3.85406 5.2415 4.575C4.52057 5.29594 4.51025 6.33281 4.4915 7.24781C4.484 7.74 4.4765 8.24813 4.349 8.55469C4.21119 8.88844 3.84557 9.27 3.49213 9.63937C2.87057 10.2863 2.1665 11.0212 2.1665 12C2.1665 12.9788 2.87057 13.7128 3.49213 14.3606C3.84557 14.73 4.21119 15.1106 4.349 15.4453C4.4765 15.7519 4.484 16.26 4.4915 16.7522C4.50557 17.6672 4.52057 18.7041 5.2415 19.425C5.96244 20.1459 6.99932 20.1609 7.91432 20.175C8.4065 20.1825 8.91463 20.19 9.22119 20.3175C9.55494 20.4553 9.9365 20.8209 10.3059 21.1744C10.9528 21.7959 11.6878 22.5 12.6665 22.5C13.6453 22.5 14.3793 21.7959 15.0271 21.1744C15.3965 20.8209 15.7771 20.4553 16.1118 20.3175C16.4184 20.19 16.9265 20.1825 17.4187 20.175C18.3337 20.1609 19.3706 20.1459 20.0915 19.425C20.8124 18.7041 20.8274 17.6672 20.8415 16.7522C20.849 16.26 20.8565 15.7519 20.984 15.4453C21.1218 15.1116 21.4874 14.73 21.8409 14.3606C22.4624 13.7137 23.1665 12.9788 23.1665 12C23.1665 11.0212 22.4624 10.2872 21.8409 9.63937ZM16.9471 10.2806L11.6971 15.5306C11.6275 15.6004 11.5448 15.6557 11.4537 15.6934C11.3627 15.7312 11.2651 15.7506 11.1665 15.7506C11.0679 15.7506 10.9703 15.7312 10.8793 15.6934C10.7882 15.6557 10.7055 15.6004 10.6359 15.5306L8.38588 13.2806C8.24515 13.1399 8.16609 12.949 8.16609 12.75C8.16609 12.551 8.24515 12.3601 8.38588 12.2194C8.52661 12.0786 8.71748 11.9996 8.9165 11.9996C9.11553 11.9996 9.3064 12.0786 9.44713 12.2194L11.1665 13.9397L15.8859 9.21937C15.9556 9.14969 16.0383 9.09442 16.1293 9.0567C16.2204 9.01899 16.318 8.99958 16.4165 8.99958C16.515 8.99958 16.6126 9.01899 16.7037 9.0567C16.7947 9.09442 16.8774 9.14969 16.9471 9.21937C17.0168 9.28906 17.0721 9.37178 17.1098 9.46283C17.1475 9.55387 17.1669 9.65145 17.1669 9.75C17.1669 9.84855 17.1475 9.94613 17.1098 10.0372C17.0721 10.1282 17.0168 10.2109 16.9471 10.2806Z" fill="#44CD90"/>
                                </svg>
                            </span>
                            <div class="content-right">
                                <h4 class="mb-0" id="sum-received">0</h4>
                                <p class="mb-0">Received</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-status">
                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="icon-bg bg-label-warning rounded-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                    <path d="M11.8029 3.84099C12.0958 3.5481 12.5707 3.5481 12.8636 3.84099L21.5529 12.5303C21.8458 12.8232 22.3207 12.8232 22.6136 12.5303C22.9065 12.2374 22.9065 11.7626 22.6136 11.4697L13.9242 2.78033C13.0456 1.90165 11.6209 1.90165 10.7423 2.78033L2.05292 11.4697C1.76003 11.7626 1.76003 12.2374 2.05292 12.5303C2.34582 12.8232 2.82069 12.8232 3.11358 12.5303L11.8029 3.84099Z" fill="#FFB000"/>
                                    <path d="M12.3333 5.43198L20.4923 13.591C20.522 13.6207 20.5523 13.6494 20.5833 13.6771V19.875C20.5833 20.9105 19.7438 21.75 18.7083 21.75H15.3333C14.919 21.75 14.5833 21.4142 14.5833 21V16.5C14.5833 16.0858 14.2475 15.75 13.8333 15.75H10.8333C10.419 15.75 10.0833 16.0858 10.0833 16.5V21C10.0833 21.4142 9.74747 21.75 9.33325 21.75H5.95825C4.92272 21.75 4.08325 20.9105 4.08325 19.875V13.6771C4.11418 13.6494 4.14452 13.6207 4.17424 13.591L12.3333 5.43198Z" fill="#FFB000"/>
                                </svg>
                            </span>
                            <div class="content-right">
                                <h4 class="mb-0" id="sum-transfer">0</h4>
                                <p class="mb-0">Transfer</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-status">
                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="icon-bg bg-label-primary rounded-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M15.7493 8.25C16.1635 8.25 16.4993 8.58579 16.4993 9C16.4993 10.1201 16.0072 11.1263 15.23 11.8123C14.9194 12.0864 14.4454 12.0569 14.1713 11.7463C13.8972 11.4357 13.9268 10.9618 14.2374 10.6877C14.7057 10.2743 14.9993 9.67191 14.9993 9C14.9993 8.58579 15.3351 8.25 15.7493 8.25Z" fill="#7367F0"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9995 2.25C6.61474 2.25 2.24951 6.61522 2.24951 12C2.24951 17.3848 6.61474 21.75 11.9995 21.75C17.3843 21.75 21.7495 17.3848 21.7495 12C21.7495 6.61522 17.3843 2.25 11.9995 2.25ZM4.57441 15.6002C5.91147 18.3527 8.73391 20.25 11.9995 20.25C12.6618 20.25 13.3058 20.172 13.9229 20.0246C13.6359 19.2603 12.9039 18.75 12.0823 18.75C11.6018 18.75 11.1916 18.4026 11.1126 17.9285L11.0396 17.4907C10.9456 16.9263 11.2901 16.3813 11.8403 16.2241L12.829 15.9416C13.2563 15.8195 13.6127 15.5237 13.8114 15.1263L13.8479 15.0533C14.094 14.561 14.5972 14.25 15.1476 14.25C15.533 14.25 15.9026 14.4031 16.1751 14.6756L16.4995 15H17.1278C17.9664 15 18.7235 15.4646 19.1103 16.1857C19.8342 14.9586 20.2495 13.5278 20.2495 12C20.2495 7.7018 16.9626 4.17132 12.7652 3.78506C12.8095 4.04802 12.9463 4.289 13.1543 4.46233L14.2229 5.35284C14.6646 5.7209 14.7577 6.36275 14.4388 6.84112L13.9277 7.60766C13.6502 8.02398 13.2418 8.3359 12.7671 8.49413L12.6249 8.54154C11.9322 8.77243 11.6487 9.59877 12.0537 10.2063C12.4232 10.7605 12.2233 11.5131 11.6276 11.811L8.99951 13.125L9.42291 14.1835C9.60751 14.645 9.40754 15.171 8.96294 15.3933C8.54981 15.5999 8.04807 15.4814 7.77093 15.1119L7.09168 14.2062C6.5899 13.5372 5.55946 13.6301 5.18545 14.3781L4.57441 15.6002Z" fill="#7367F0"/>
                                </svg>
                            </span>
                            <div class="content-right">
                                <h4 class="mb-0" id="sum-in-transit">0</h4>
                                <p class="mb-0">In-Transit</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-status">
                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="icon-bg bg-label-success rounded-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                    <path d="M5.88985 2.25C5.39257 2.25 4.91566 2.44754 4.56403 2.79917L3.26485 4.09835C1.80039 5.56282 1.80039 7.93718 3.26485 9.40165C4.60201 10.7388 6.69774 10.8551 8.16679 9.75038C8.79319 10.2206 9.57249 10.5 10.4165 10.5C11.2606 10.5 12.0401 10.2205 12.6665 9.75016C13.293 10.2205 14.0724 10.5 14.9165 10.5C15.7605 10.5 16.5398 10.2206 17.1662 9.75038C18.6353 10.8551 20.731 10.7388 22.0682 9.40165C23.5326 7.93718 23.5326 5.56282 22.0682 4.09835L20.769 2.79918C20.4174 2.44755 19.9404 2.25 19.4432 2.25L5.88985 2.25Z" fill="#4DA7CD"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.6665 20.25V11.4951C5.08671 12.1686 6.7464 12.1681 8.16694 11.4944C8.84915 11.8183 9.61262 12 10.4165 12C11.2206 12 11.9842 11.8182 12.6665 11.4942C13.3488 11.8182 14.1124 12 14.9165 12C15.7204 12 16.4839 11.8183 17.1661 11.4944C18.5866 12.1681 20.2463 12.1686 21.6665 11.4951V20.25H22.4165C22.8307 20.25 23.1665 20.5858 23.1665 21C23.1665 21.4142 22.8307 21.75 22.4165 21.75H2.9165C2.50229 21.75 2.1665 21.4142 2.1665 21C2.1665 20.5858 2.50229 20.25 2.9165 20.25H3.6665ZM6.6665 14.25C6.6665 13.8358 7.00229 13.5 7.4165 13.5H10.4165C10.8307 13.5 11.1665 13.8358 11.1665 14.25V17.25C11.1665 17.6642 10.8307 18 10.4165 18H7.4165C7.00229 18 6.6665 17.6642 6.6665 17.25V14.25ZM14.9165 13.5C14.5023 13.5 14.1665 13.8358 14.1665 14.25V19.5C14.1665 19.9142 14.5023 20.25 14.9165 20.25H17.9165C18.3307 20.25 18.6665 19.9142 18.6665 19.5V14.25C18.6665 13.8358 18.3307 13.5 17.9165 13.5H14.9165Z" fill="#4DA7CD"/>
                                </svg>
                            </span>
                            <div class="content-right">
                                <h4 class="mb-0" id="sum-routing">0</h4>
                                <p class="mb-0">Routing</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-status">
                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="icon-bg bg-label-info rounded-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                                    <path d="M10.3614 9.45749L10.9193 11.4757C11.4447 13.377 11.7069 14.3282 12.4793 14.7604C13.2517 15.1937 14.2321 14.9381 16.193 14.4289L18.273 13.8872C20.2338 13.3781 21.2142 13.1235 21.6605 12.3749C22.1069 11.6252 21.8447 10.6741 21.3182 8.77283L20.7614 6.75566C20.236 4.85333 19.9727 3.90216 19.2014 3.46991C18.4279 3.03658 17.4475 3.29224 15.4866 3.80249L13.4066 4.34199C11.4458 4.85116 10.4654 5.10683 10.0201 5.85649C9.57379 6.60508 9.83596 7.55624 10.3614 9.45749Z" fill="#4DA7CD"/>
                                    <path d="M2.46671 5.68424C2.49525 5.58135 2.54379 5.48509 2.60956 5.40098C2.67533 5.31686 2.75703 5.24653 2.85 5.19401C2.94296 5.14149 3.04537 5.10781 3.15136 5.09489C3.25735 5.08198 3.36485 5.09008 3.46771 5.11874L5.31263 5.63007C5.80158 5.76286 6.24778 6.02007 6.60775 6.37662C6.96772 6.73318 7.22918 7.17691 7.36663 7.66457L9.69688 16.0994L9.86804 16.6909C10.5538 16.9477 11.1431 17.4276 11.5169 18.0646L11.8527 17.9606L21.4619 15.4635C21.5652 15.4366 21.6727 15.4303 21.7784 15.445C21.8842 15.4597 21.9859 15.495 22.078 15.5491C22.17 15.6031 22.2505 15.6747 22.3149 15.7598C22.3793 15.845 22.4262 15.942 22.4531 16.0452C22.48 16.1485 22.4863 16.2561 22.4716 16.3618C22.4569 16.4675 22.4216 16.5693 22.3675 16.6613C22.3135 16.7534 22.2419 16.8339 22.1568 16.8983C22.0716 16.9626 21.9747 17.0096 21.8714 17.0365L12.2969 19.5238L11.9415 19.6343C11.934 21.0102 10.9839 22.2679 9.54738 22.6417C7.82379 23.0902 6.05254 22.0978 5.59104 20.4273C5.12954 18.7557 6.15221 17.0376 7.87471 16.5902C7.95958 16.5683 8.04522 16.5495 8.13146 16.5338L5.80013 8.09682C5.73737 7.88006 5.61958 7.68323 5.45822 7.52548C5.29686 7.36772 5.09742 7.25441 4.87929 7.19657L3.03329 6.68415C2.93042 6.65572 2.83416 6.6073 2.75001 6.54165C2.66586 6.47601 2.59547 6.39443 2.54285 6.30157C2.49024 6.20872 2.45643 6.10641 2.44337 6.00048C2.4303 5.89456 2.43823 5.7871 2.46671 5.68424Z" fill="#4DA7CD"/>
                                </svg>
                            </span>
                            <div class="content-right">
                                <h4 class="mb-0" id="sum-on-delivery">0</h4>
                                <p class="mb-0">On-Delivery</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-status">
                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="icon-bg bg-label-danger rounded-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M20.9051 5.9904L17.2499 7.4532L8.24992 3.8532L10.4399 2.976C11.4413 2.57538 12.5585 2.57538 13.5599 2.976L20.4683 5.7396C20.6249 5.80194 20.7719 5.88716 20.9051 5.9904ZM12.0011 9.5532L15.6347 8.0988L6.63472 4.4988L3.53152 5.7408C3.37495 5.80313 3.22791 5.88715 3.09472 5.9904L11.9999 9.5532H12.0011ZM2.44072 7.0224C2.41304 7.15013 2.39936 7.2805 2.39992 7.4112V16.5864C2.39986 16.9462 2.50764 17.2978 2.70934 17.5958C2.91105 17.8937 3.19743 18.1244 3.53152 18.258L10.4399 21.0216C10.7519 21.1464 11.0735 21.2316 11.3999 21.2784V20.154C11.0032 19.2902 10.7985 18.3506 10.7999 17.4C10.7999 16.416 11.0159 15.4848 11.3999 14.646V10.6056L2.44192 7.0224H2.44072ZM21.5999 7.4112V12.3084C20.4187 11.3311 18.933 10.7976 17.3999 10.8C16.5009 10.7989 15.6112 10.982 14.7856 11.3381C13.9601 11.6941 13.2162 12.2154 12.5999 12.87V10.6056L21.5579 7.0224C21.5855 7.1484 21.5999 7.2792 21.5999 7.4112ZM22.7999 17.4C22.7999 18.8322 22.231 20.2057 21.2183 21.2184C20.2056 22.2311 18.8321 22.8 17.3999 22.8C15.9677 22.8 14.5942 22.2311 13.5815 21.2184C12.5688 20.2057 11.9999 18.8322 11.9999 17.4C11.9999 15.9678 12.5688 14.5943 13.5815 13.5816C14.5942 12.5689 15.9677 12 17.3999 12C18.8321 12 20.2056 12.5689 21.2183 13.5816C22.231 14.5943 22.7999 15.9678 22.7999 17.4ZM19.6247 16.0248C19.7374 15.9121 19.8007 15.7593 19.8007 15.6C19.8007 15.4407 19.7374 15.2879 19.6247 15.1752C19.5121 15.0625 19.3592 14.9992 19.1999 14.9992C19.0406 14.9992 18.8878 15.0625 18.7751 15.1752L17.3999 16.5516L16.0247 15.1752C15.9121 15.0625 15.7592 14.9992 15.5999 14.9992C15.4406 14.9992 15.2878 15.0625 15.1751 15.1752C15.0625 15.2879 14.9992 15.4407 14.9992 15.6C14.9992 15.7593 15.0625 15.9121 15.1751 16.0248L16.5515 17.4L15.1751 18.7752C15.1193 18.831 15.0751 18.8972 15.0449 18.9701C15.0147 19.043 14.9992 19.1211 14.9992 19.2C14.9992 19.2789 15.0147 19.357 15.0449 19.4299C15.0751 19.5028 15.1193 19.569 15.1751 19.6248C15.2309 19.6806 15.2971 19.7248 15.37 19.755C15.4429 19.7852 15.521 19.8008 15.5999 19.8008C15.6788 19.8008 15.7569 19.7852 15.8298 19.755C15.9027 19.7248 15.9689 19.6806 16.0247 19.6248L17.3999 18.2484L18.7751 19.6248C18.8309 19.6806 18.8971 19.7248 18.97 19.755C19.0429 19.7852 19.121 19.8008 19.1999 19.8008C19.2788 19.8008 19.3569 19.7852 19.4298 19.755C19.5027 19.7248 19.5689 19.6806 19.6247 19.6248C19.6805 19.569 19.7248 19.5028 19.7549 19.4299C19.7851 19.357 19.8007 19.2789 19.8007 19.2C19.8007 19.1211 19.7851 19.043 19.7549 18.9701C19.7248 18.8972 19.6805 18.831 19.6247 18.7752L18.2483 17.4L19.6247 16.0248Z" fill="#FF6E5D"/>
                                </svg>
                            </span>
                            <div class="content-right">
                                <h4 class="mb-0" id="sum-undelivered">0</h4>
                                <p class="mb-0">Undelivered</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-status">
                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="icon-bg bg-label-success rounded-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M20.9051 5.9904L17.2499 7.4532L8.2499 3.8532L10.4399 2.976C11.4413 2.57538 12.5585 2.57538 13.5599 2.976L20.4683 5.7396C20.6249 5.80194 20.7719 5.88716 20.9051 5.9904ZM12.0011 9.5532L15.6347 8.0988L6.6347 4.4988L3.5315 5.7408C3.37493 5.80313 3.22789 5.88715 3.0947 5.9904L11.9999 9.5532H12.0011ZM2.3999 7.4112C2.3999 7.2792 2.4143 7.1484 2.4419 7.0224L11.3999 10.6056V14.646C11.0034 15.5095 10.7987 16.4486 10.7999 17.3988C10.7999 18.3828 11.0159 19.3152 11.3999 20.1528V21.2784C11.0708 21.2313 10.7485 21.1451 10.4399 21.0216L3.5315 18.258C3.19741 18.1244 2.91104 17.8937 2.70933 17.5958C2.50762 17.2978 2.39984 16.9462 2.3999 16.5864V7.4112ZM21.5999 7.4112V12.3072C20.4185 11.3304 18.9328 10.7972 17.3999 10.8C16.501 10.7988 15.6113 10.9817 14.7857 11.3375C13.9602 11.6933 13.2163 12.2145 12.5999 12.8688V10.6056L21.5579 7.0224C21.5855 7.1484 21.5999 7.2792 21.5999 7.4112ZM17.3999 22.8C18.8321 22.8 20.2056 22.2311 21.2183 21.2184C22.231 20.2057 22.7999 18.8322 22.7999 17.4C22.7999 15.9678 22.231 14.5943 21.2183 13.5816C20.2056 12.5689 18.8321 12 17.3999 12C15.9677 12 14.5942 12.5689 13.5815 13.5816C12.5688 14.5943 11.9999 15.9678 11.9999 17.4C11.9999 18.8322 12.5688 20.2057 13.5815 21.2184C14.5942 22.2311 15.9677 22.8 17.3999 22.8ZM16.1999 18.3516L19.3751 15.1764C19.4876 15.0637 19.6403 15.0004 19.7995 15.0003C19.8783 15.0002 19.9564 15.0157 20.0292 15.0458C20.1021 15.0759 20.1683 15.1201 20.2241 15.1758C20.2799 15.2315 20.3242 15.2977 20.3544 15.3705C20.3846 15.4433 20.4002 15.5213 20.4002 15.6002C20.4003 15.679 20.3848 15.7571 20.3547 15.8299C20.3246 15.9028 20.2804 15.969 20.2247 16.0248L16.6247 19.6248C16.569 19.6807 16.5028 19.725 16.4299 19.7553C16.357 19.7855 16.2788 19.8011 16.1999 19.8011C16.121 19.8011 16.0428 19.7855 15.9699 19.7553C15.897 19.725 15.8308 19.6807 15.7751 19.6248L14.5751 18.4248C14.4626 18.3121 14.3995 18.1594 14.3996 18.0002C14.3997 17.841 14.463 17.6883 14.5757 17.5758C14.6884 17.4633 14.8411 17.4002 15.0003 17.4003C15.1595 17.4004 15.3122 17.4637 15.4247 17.5764L16.1999 18.3516Z" fill="#44CD90"/>
                                </svg>
                            </span>
                            <div class="content-right">
                                <h4 class="mb-0" id="sum-delivered">0</h4>
                                <p class="mb-0">Delivered</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-status">
                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="icon-bg bg-label-warning rounded-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M20.9051 5.9904L17.2499 7.4532L8.2499 3.8532L10.4399 2.976C11.4413 2.57538 12.5585 2.57538 13.5599 2.976L20.4683 5.7396C20.6249 5.80194 20.7719 5.88716 20.9051 5.9904ZM12.0011 9.5532L15.6347 8.0988L6.6347 4.4988L3.5315 5.7408C3.37493 5.80313 3.22789 5.88715 3.0947 5.9904L11.9999 9.5532H12.0011ZM2.3999 7.4112C2.3999 7.2792 2.4143 7.1484 2.4419 7.0224L11.3999 10.6056V14.646C11.0034 15.5095 10.7987 16.4486 10.7999 17.3988C10.7999 18.3828 11.0159 19.3152 11.3999 20.1528V21.2784C11.0708 21.2313 10.7485 21.1451 10.4399 21.0216L3.5315 18.258C3.19741 18.1244 2.91104 17.8937 2.70933 17.5958C2.50762 17.2978 2.39984 16.9462 2.3999 16.5864V7.4112ZM21.5999 7.4112V12.3072C20.4185 11.3304 18.9328 10.7972 17.3999 10.8C16.501 10.7988 15.6113 10.9817 14.7857 11.3375C13.9602 11.6933 13.2163 12.2145 12.5999 12.8688V10.6056L21.5579 7.0224C21.5855 7.1484 21.5999 7.2792 21.5999 7.4112ZM17.3999 22.8C18.8321 22.8 20.2056 22.2311 21.2183 21.2184C22.231 20.2057 22.7999 18.8322 22.7999 17.4C22.7999 15.9678 22.231 14.5943 21.2183 13.5816C20.2056 12.5689 18.8321 12 17.3999 12C15.9677 12 14.5942 12.5689 13.5815 13.5816C12.5688 14.5943 11.9999 15.9678 11.9999 17.4C11.9999 18.8322 12.5688 20.2057 13.5815 21.2184C14.5942 22.2311 15.9677 22.8 17.3999 22.8ZM17.8211 14.574L17.8247 14.5764L20.2247 16.9764C20.2804 17.0322 20.3246 17.0984 20.3547 17.1713C20.3848 17.2441 20.4003 17.3222 20.4002 17.401C20.4002 17.4799 20.3846 17.5579 20.3544 17.6307C20.3242 17.7035 20.2799 17.7697 20.2241 17.8254C20.1683 17.8811 20.1021 17.9253 20.0292 17.9554C19.9564 17.9855 19.8783 18.001 19.7995 18.0009C19.7206 18.0009 19.6426 17.9853 19.5698 17.9551C19.497 17.9249 19.4308 17.8806 19.3751 17.8248L17.9999 16.4484V19.8C17.9999 19.9591 17.9367 20.1117 17.8242 20.2243C17.7116 20.3368 17.559 20.4 17.3999 20.4C17.2408 20.4 17.0882 20.3368 16.9756 20.2243C16.8631 20.1117 16.7999 19.9591 16.7999 19.8V16.4484L15.4247 17.8236C15.369 17.8794 15.3028 17.9237 15.23 17.9539C15.1572 17.9841 15.0792 17.9997 15.0003 17.9997C14.9215 17.9998 14.8434 17.9843 14.7706 17.9542C14.6977 17.9241 14.6315 17.8799 14.5757 17.8242C14.5199 17.7685 14.4756 17.7023 14.4454 17.6295C14.4152 17.5567 14.3996 17.4787 14.3996 17.3998C14.3995 17.321 14.415 17.2429 14.4451 17.1701C14.4752 17.0972 14.5194 17.031 14.5751 16.9752L16.9751 14.5752C17.087 14.4636 17.2383 14.4007 17.3963 14.4H17.4035C17.56 14.4007 17.7099 14.4628 17.8211 14.5728V14.574Z" fill="#FFB000"/>
                                </svg>
                            </span>
                            <div class="content-right">
                                <h4 class="mb-0" id="sum-return">0</h4>
                                <p class="mb-0">Return</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row filter-tracking" id="filter-tracking">
        <div class="col-md-8">
            <div class="filter-card d-flex align-items-center flex-column flex-md-row flex-lg-row" id="dashboard-form">
                <label class="label-filter-card" for="tracking-type">Tracking:</label>
                <div class="input-group">
                    <select id="tracking-type" class="form-select" name="trackingType">
                        <option value="waybill">Waybill</option>
                        <option value="delivery_record">Delivery Record</option>
                    </select>
                    <input type="text" name="tracking" id="tracking" class="form-control" placeholder="Input Number">
                </div>
                <button type="button" class="btn btn-primary ms-2" id="submit-tracking">Submit</button>
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-center flex-column flex-md-row flex-lg-row justify-content-end">
            <strong>Delivery Record:</strong>
            <span class="ms-1" id="sum-dr">{{ number_format($total_dr) }}</span>
        </div>
    </div>
    <div class="row order-tracking d-none" id="order-tracking">
        <div class="col-md-6">
            <div class="card card-order-information">
                <div class="card-header d-flex flex-column flex-md-row flex-lg-row">
                    <div class="title-card-page d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M15.75 8.4375V14.625C15.75 15.2463 15.2463 15.75 14.625 15.75H3.9375C3.31618 15.75 2.8125 15.2463 2.8125 14.625V8.4375M9 3.65625C9 2.56894 8.11856 1.6875 7.03125 1.6875C5.94394 1.6875 5.0625 2.56894 5.0625 3.65625C5.0625 4.74356 5.94394 5.625 7.03125 5.625C7.58213 5.625 9 5.625 9 5.625M9 3.65625C9 4.19268 9 5.625 9 5.625M9 3.65625C9 2.56894 9.88144 1.6875 10.9688 1.6875C12.0561 1.6875 12.9375 2.56894 12.9375 3.65625C12.9375 4.74356 12.0561 5.625 10.9688 5.625C10.4179 5.625 9 5.625 9 5.625M9 5.625V15.75M2.53125 8.4375H16.0312C16.4972 8.4375 16.875 8.05974 16.875 7.59375V6.46875C16.875 6.00276 16.4972 5.625 16.0312 5.625H2.53125C2.06526 5.625 1.6875 6.00276 1.6875 6.46875V7.59375C1.6875 8.05974 2.06526 8.4375 2.53125 8.4375Z" stroke="#4C4F54" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h5 class="ms-2">Order Information</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Waybill:</dt>
                                <dd class="mb-0" id="order-waybill"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Order&nbsp;Code:</dt>
                                <dd class="mb-0" id="order-code"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Order&nbsp;Date:</dt>
                                <dd class="mb-0" id="order-date"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Channel:</dt>
                                <dd class="mb-0" id="order-channel"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Brand:</dt>
                                <dd class="mb-0" id="order-brand"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Delivery&nbsp;Record:</dt>
                                <dd class="mb-0" id="order-dr"></dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Courier:</dt>
                                <dd class="mb-0" id="order-courier"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">COD:</dt>
                                <dd class="mb-0" id="order-cod"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Status:</dt>
                                <dd class="mb-0" id="order-status"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Origin&nbsp;Hub:</dt>
                                <dd class="mb-0" id="order-origin-hub"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Destination&nbsp;Hub:</dt>
                                <dd class="mb-0" id="order-destination-hub"></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-delivery-history">
                <div class="card-header d-flex flex-column flex-md-row flex-lg-row">
                    <div class="title-card-page d-flex">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.01157 2.25H15.0116C16.2866 2.25 17.2616 3.225 17.2616 4.5V13.5C17.2616 14.775 16.2866 15.75 15.0116 15.75H10.5116C10.0616 15.75 9.76157 15.45 9.76157 15C9.76157 14.55 10.0616 14.25 10.5116 14.25H15.0116C15.4616 14.25 15.7616 13.95 15.7616 13.5V4.5C15.7616 4.05 15.4616 3.75 15.0116 3.75H3.01157C2.56157 3.75 2.26157 4.05 2.26157 4.5V6C2.26157 6.45 1.96157 6.75 1.51157 6.75C1.06157 6.75 0.76157 6.45 0.76157 6V4.5C0.76157 3.225 1.73657 2.25 3.01157 2.25ZM4.58657 15.75C4.96157 15.675 5.26157 15.225 5.18657 14.85C4.81157 13.05 3.46157 11.7 1.66157 11.325C1.21157 11.25 0.83657 11.55 0.76157 11.925C0.68657 12.3 0.98657 12.75 1.36157 12.825C2.56157 13.05 3.46157 13.95 3.68657 15.15C3.76157 15.525 4.06157 15.75 4.43657 15.75H4.58657ZM1.58657 8.325C1.21157 8.25 0.83657 8.55 0.76157 9C0.68657 9.375 0.98657 9.75 1.43657 9.825C4.21157 10.125 6.46157 12.3 6.76157 15.15C6.76157 15.45 7.06157 15.75 7.43657 15.75H7.51157C7.88657 15.675 8.18657 15.3 8.18657 14.925C7.81157 11.4 5.11157 8.7 1.58657 8.325ZM0.98657 14.475C1.06157 14.4 1.13657 14.325 1.21157 14.325C1.51157 14.175 1.81157 14.25 2.03657 14.475C2.18657 14.625 2.26157 14.775 2.26157 15C2.26157 15.225 2.18657 15.375 2.03657 15.525C1.88657 15.675 1.73657 15.75 1.51157 15.75C1.28657 15.75 1.13657 15.675 0.98657 15.525C0.83657 15.375 0.76157 15.225 0.76157 15C0.76157 14.775 0.83657 14.625 0.98657 14.475Z" fill="#4C4F54"/>
                            <mask id="mask0_1837_4853" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="2" width="18" height="14">
                               <path fill-rule="evenodd" clip-rule="evenodd" d="M3.01157 2.25H15.0116C16.2866 2.25 17.2616 3.225 17.2616 4.5V13.5C17.2616 14.775 16.2866 15.75 15.0116 15.75H10.5116C10.0616 15.75 9.76157 15.45 9.76157 15C9.76157 14.55 10.0616 14.25 10.5116 14.25H15.0116C15.4616 14.25 15.7616 13.95 15.7616 13.5V4.5C15.7616 4.05 15.4616 3.75 15.0116 3.75H3.01157C2.56157 3.75 2.26157 4.05 2.26157 4.5V6C2.26157 6.45 1.96157 6.75 1.51157 6.75C1.06157 6.75 0.76157 6.45 0.76157 6V4.5C0.76157 3.225 1.73657 2.25 3.01157 2.25ZM4.58657 15.75C4.96157 15.675 5.26157 15.225 5.18657 14.85C4.81157 13.05 3.46157 11.7 1.66157 11.325C1.21157 11.25 0.83657 11.55 0.76157 11.925C0.68657 12.3 0.98657 12.75 1.36157 12.825C2.56157 13.05 3.46157 13.95 3.68657 15.15C3.76157 15.525 4.06157 15.75 4.43657 15.75H4.58657ZM1.58657 8.325C1.21157 8.25 0.83657 8.55 0.76157 9C0.68657 9.375 0.98657 9.75 1.43657 9.825C4.21157 10.125 6.46157 12.3 6.76157 15.15C6.76157 15.45 7.06157 15.75 7.43657 15.75H7.51157C7.88657 15.675 8.18657 15.3 8.18657 14.925C7.81157 11.4 5.11157 8.7 1.58657 8.325ZM0.98657 14.475C1.06157 14.4 1.13657 14.325 1.21157 14.325C1.51157 14.175 1.81157 14.25 2.03657 14.475C2.18657 14.625 2.26157 14.775 2.26157 15C2.26157 15.225 2.18657 15.375 2.03657 15.525C1.88657 15.675 1.73657 15.75 1.51157 15.75C1.28657 15.75 1.13657 15.675 0.98657 15.525C0.83657 15.375 0.76157 15.225 0.76157 15C0.76157 14.775 0.83657 14.625 0.98657 14.475Z" fill="white"/>
                            </mask>
                            <g mask="url(#mask0_1837_4853)">
                               <rect width="18" height="18" fill="#4C4F54"/>
                            </g>
                         </svg>
                        <h5 class="ms-2">Delivery History</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>STATUS</th>
                                    <th>TIMESTAMP</th>
                                    <th>MODIFIED BY</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="tbody-delivery-history">
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-primary me-1" data-bs-toggle="modal" data-bs-target="#modalSignature">POD Signature</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPhoto">POD Photo</button>

                        <!-- Modal POD Signature-->
                        <div class="modal fade" id="modalSignature" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <img src="{{ asset('storage/fa6-solid_signature.png') }}" class="img-responsive w-100" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal POD Photo-->
                        <div class="modal fade" id="modalPhoto" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <img src="{{ asset('storage/1166d7d13c1020cc12a84d80efeb21bf.jpeg') }}" class="img-responsive w-100" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row record-tracking d-none" id="record-tracking">
        <div class="col-md-6">
            <div class="card card-record-information">
                <div class="card-header d-flex flex-column flex-md-row flex-lg-row">
                    <div class="title-card-page d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M15.75 8.4375V14.625C15.75 15.2463 15.2463 15.75 14.625 15.75H3.9375C3.31618 15.75 2.8125 15.2463 2.8125 14.625V8.4375M9 3.65625C9 2.56894 8.11856 1.6875 7.03125 1.6875C5.94394 1.6875 5.0625 2.56894 5.0625 3.65625C5.0625 4.74356 5.94394 5.625 7.03125 5.625C7.58213 5.625 9 5.625 9 5.625M9 3.65625C9 4.19268 9 5.625 9 5.625M9 3.65625C9 2.56894 9.88144 1.6875 10.9688 1.6875C12.0561 1.6875 12.9375 2.56894 12.9375 3.65625C12.9375 4.74356 12.0561 5.625 10.9688 5.625C10.4179 5.625 9 5.625 9 5.625M9 5.625V15.75M2.53125 8.4375H16.0312C16.4972 8.4375 16.875 8.05974 16.875 7.59375V6.46875C16.875 6.00276 16.4972 5.625 16.0312 5.625H2.53125C2.06526 5.625 1.6875 6.00276 1.6875 6.46875V7.59375C1.6875 8.05974 2.06526 8.4375 2.53125 8.4375Z" stroke="#4C4F54" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h5 class="ms-2">Delivery Record Information</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Courier&nbsp;Name:</dt>
                                <dd class="mb-0" id="record-courier"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Timestamp&nbsp;Created:</dt>
                                <dd class="mb-0" id="record-timestamp-created"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Total&nbsp;Waybill:</dt>
                                <dd class="mb-0" id="record-total-waybill"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Total&nbsp;Delivered:</dt>
                                <dd class="mb-0" id="record-total-delivered"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Total&nbsp;Undelivered:</dt>
                                <dd class="mb-0" id="record-total-undelivered"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Total&nbsp;Return:</dt>
                                <dd class="mb-0" id="record-total-return"></dd>
                            </dl>
                            <dl class="d-flex flex-row mb-2">
                                <dt class="mb-0">Destination&nbsp;Hub:</dt>
                                <dd class="mb-0" id="record-destination-hub"></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-record-history">
                <div class="card-header d-flex flex-column flex-md-row flex-lg-row">
                    <div class="title-card-page d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M14.625 10.6875V8.71875C14.625 7.32078 13.4917 6.1875 12.0938 6.1875H10.9688C10.5028 6.1875 10.125 5.80974 10.125 5.34375V4.21875C10.125 2.82078 8.99172 1.6875 7.59375 1.6875H6.1875M6.75 12.375V12.9375M9 10.6875V12.9375M11.25 9V12.9375M7.875 1.6875H4.21875C3.75276 1.6875 3.375 2.06526 3.375 2.53125V15.4688C3.375 15.9347 3.75276 16.3125 4.21875 16.3125H13.7812C14.2472 16.3125 14.625 15.9347 14.625 15.4688V8.4375C14.625 4.70958 11.6029 1.6875 7.875 1.6875Z" stroke="#4C4F54" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h5 class="ms-2">List Waybill</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>WAYBILL</th>
                                    <th>ORDER CODE</th>
                                    <th>LAST STATUS</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="tbody-record">
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <a href="javascript:void(0);" class="btn btn-warning waves-effect waves-light">
                            <i class="ti ti-book cursor-pointer me-1"></i>
                            Print
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('template/assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/libs/masonry/masonry.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/libs/block-ui/block-ui.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#tracking-type").change(function() {
                $("#record-tracking").addClass("d-none");
                $("#order-tracking").addClass("d-none");
                $("#tracking").val('');
            })

            $("#submit-tracking").click(function() {
                var trackingType = $("#tracking-type").val();
                var tracking = $("#tracking").val();

                if (trackingType.length == 0) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please select tracking type!',
                        icon: 'error',
                        customClass: {
                        confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                } else if(tracking.length == 0) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please insert tracking value!',
                        icon: 'error',
                        customClass: {
                        confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                } else {
                    if (trackingType === 'waybill') {
                        orderTracking(tracking);
                    } else {
                        deliveryRecordTracking(tracking);
                    }
                }
            })

            var bsRangePickerRange = $('#created-filter');
            if (bsRangePickerRange.length) {
                bsRangePickerRange.daterangepicker({
                    ranges: {
                        Today: [moment(), moment()],
                        Yesterday: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'All': [moment(), moment()],
                    }
                });

                bsRangePickerRange.on('apply.daterangepicker', function(ev, picker) {
                    if (picker.chosenLabel === 'All') {
                        $('#created-filter').val("All"); 
                    }
                    summaryCount();
                });
            }

            $('#origin-filter').change(function() {
                summaryCount();
            });

            summaryCount();
        });

        function summaryCount()
        {
            var createdFilter = $('#created-filter').val();
            var originFilter = $('#origin-filter').val();

            $('#card-summary').block({
                message: '<div class="spinner-border text-white" role="status"></div>',
                css: {
                    backgroundColor: 'transparent',
                    border: '0'
                },
                    overlayCSS: {
                    opacity: 0.5
                }
            });

            var url = "{{ route('dashboard-summary') }}";
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    createdFilter:createdFilter,
                    originFilter:originFilter
                },
                success: function(data) {
                    if (data.success) {
                        var sum = data.data;
                        $("#sum-waybill").html(sum.waybill);
                        $("#sum-delivered").html(sum.delivered);
                        $("#sum-entry").html(sum.entry);
                        $("#sum-in-transit").html(sum.in_transit);
                        $("#sum-on-delivery").html(sum.on_delivery);
                        $("#sum-received").html(sum.received);
                        $("#sum-rejected").html(sum.rejected);
                        $("#sum-return").html(sum.return);
                        $("#sum-routing").html(sum.routing);
                        $("#sum-transfer").html(sum.transfer);
                        $("#sum-undelivered").html(sum.undelivered);
                        $('#card-summary').unblock();
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.error,
                            icon: 'error',
                            customClass: {
                            confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        $('#card-summary').unblock();
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: error,
                        icon: 'error',
                        customClass: {
                        confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                    $('#card-summary').unblock();
                }
            });
        }

        function orderTracking(waybill)
        {
            $('#filter-tracking').block({
                message: '<div class="spinner-border text-white" role="status"></div>',
                css: {
                    backgroundColor: 'transparent',
                    border: '0'
                },
                    overlayCSS: {
                    opacity: 0.5
                }
            });

            $("#record-tracking").addClass("d-none");
            $("#order-tracking").addClass("d-none");
            var url = "{{ route('dashboard-order-tracking') }}";
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    waybill:waybill
                },
                success: function(data) {
                    if (data.success) {
                        var waybill = data.data;
                        $("#order-waybill").html(waybill.waybill);
                        $("#order-code").html(waybill.order_code);
                        $("#order-date").html(waybill.order_date);
                        $("#order-channel").html(waybill.channel);
                        $("#order-brand").html(waybill.brand);
                        $("#order-dr").html(waybill.delivery_record);
                        $("#order-courier").html(waybill.courier);
                        $("#order-cod").html(waybill.cod);
                        $("#order-status").html("<span class='badge bg-label-"+waybill.waybill_label+"'>"+waybill.status_name+"</span>");
                        $("#order-origin-hub").html(waybill.origin_hub);
                        $("#order-destination-hub").html(waybill.destination_hub);

                        var delivery_history = waybill.delivery_history;
                        var tbody = $("#tbody-delivery-history");
                        tbody.html('');
                        $.each(delivery_history, function(index, item) {
                            var row = $('<tr>');
                            row.append($('<td>').text(item.status));
                            row.append($('<td>').text(item.timestamp));
                            row.append($('<td>').text(item.modified_by));
                            tbody.append(row);
                        });
                        if (delivery_history.length == 0) {
                            var row = $('<tr>');
                            var cell = $('<td>', { colspan: 3, text: 'history empty', class: 'text-center', });
                            row.append(cell);
                            tbody.append(row);
                        }
                        $("#order-tracking").removeClass("d-none");
                        $('#filter-tracking').unblock();
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.error,
                            icon: 'error',
                            customClass: {
                            confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        $('#filter-tracking').unblock();
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: error,
                        icon: 'error',
                        customClass: {
                        confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                    $('#filter-tracking').unblock();
                }
            });
        }

        function deliveryRecordTracking(routing)
        {
            $('#filter-tracking').block({
                message: '<div class="spinner-border text-white" role="status"></div>',
                css: {
                    backgroundColor: 'transparent',
                    border: '0'
                },
                    overlayCSS: {
                    opacity: 0.5
                }
            });

            $("#record-tracking").addClass("d-none");
            $("#order-tracking").addClass("d-none");
            var url = "{{ route('dashboard-routing-tracking') }}";
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    routing:routing
                },
                success: function(data) {
                    if (data.success) {
                        var record = data.data;
                        $("#record-courier").html(record.courier_name);
                        $("#record-timestamp-created").html(record.timestamp_created);
                        $("#record-total-waybill").html(record.total_waybill);
                        $("#record-total-delivered").html(record.total_delivered);
                        $("#record-total-undelivered").html(record.total_undelivered);
                        $("#record-total-return").html(record.total_return);
                        $("#record-destination-hub").html(record.destination_hub);

                        var list_waybill = record.list_waybill;
                        var tbody = $("#tbody-record");
                        tbody.html('');
                        $.each(list_waybill, function(index, item) {
                            var row = $('<tr>');
                            row.append($('<td>').text(item.waybill));
                            row.append($('<td>').text(item.order_code));
                            row.append($('<td>').text(item.last_status));
                            tbody.append(row);
                        });
                        if (list_waybill.length == 0) {
                            var row = $('<tr>');
                            var cell = $('<td>', { colspan: 3, text: 'list waybill empty', class: 'text-center', });
                            row.append(cell);
                            tbody.append(row);
                        }
                        $("#record-tracking").removeClass("d-none");
                        $('#filter-tracking').unblock();
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.error,
                            icon: 'error',
                            customClass: {
                            confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        $('#filter-tracking').unblock();
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: error,
                        icon: 'error',
                        customClass: {
                        confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                    $('#filter-tracking').unblock();
                }
            });
        }
    </script>
@endsection