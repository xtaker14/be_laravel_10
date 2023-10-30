@extends('layouts.main')

@section('styles')
<style>
    .courier-select
    {
        margin-bottom: 30px;
    }
    .courier-select label{
        font-size: 12px;
        font-style: normal;
        font-weight: 700; 
    }
    .courier-select #deliveryRecord{
        background: #E5E5E5; 
    }
    @media (min-width: 767px) {
        .courier-select .btn-submit{
            position: relative;
        }
        .courier-select button{
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
        }
    }
    .filter-card .label-filter-card{
        font-size: 14px;
        font-style: normal;
        font-weight: 700; 
        margin: 0px 16px;
    }
    html:not([dir="rtl"]) .datePickerGroup .form-control:not(:first-child){
        border-left: 1px solid #dbdade;
        border-top-left-radius: 0.375rem !important;
        border-bottom-left-radius: 0.375rem !important;
        padding: 0px 0.75rem !important;
        height: 38px;
    }
    .date-header{
        margin-bottom: 30px;
    }
    .date-header span{
        font-size: 14px;
        font-style: normal;
        font-weight: 400; 
    }
    .cod-dashboard .card-icon{
        margin-bottom: 30px;
        text-align: center;
    }
    .cod-dashboard .card-icon .card-body{
        padding: 16px 16px;
    }
    .cod-dashboard .avatar{
        width: 48px;
        height: 48px; 
    }
    .cod-dashboard .avatar .avatar-initial{
        border-radius: 26px;
    }
    .cod-dashboard h3{
        font-size: 20px;
        font-style: normal;
        font-weight: 700; 
    }
    .cod-dashboard p{
        font-size: 12px;
        font-style: normal;
        font-weight: 400;  
    }
    .cod-dashboard .bg-primary-icon{
        background: rgba(115, 103, 240, 0.12);
    }
    .cod-dashboard .bg-warning-icon{
        background: rgba(255, 176, 0, 0.12); 
    }
    .cod-dashboard .bg-info-icon{
        background: rgba(77, 167, 205, 0.12); 
    }
    .cod-dashboard .bg-success-icon{
        background: rgba(68, 205, 144, 0.12); 
    }
    .cod-dashboard .bg-danger-icon{
        background: rgba(255, 110, 93, 0.12); 
    }
    .cod-table .tab-content{
        padding: 30px 0px;
        box-shadow: none !important;
    }
    .cod-table .tab-content h5{
        font-size: 18px;
        font-style: normal;
        font-weight: 700; 
        margin-bottom: 30px;
    }
    .cod-table .datatable-cod{
        margin-bottom: 30px !important;
    }
    .cod-table .datatable-cod thead{
        background: #E2EAF4;
        height: 40px;
    }
    .cod-table .table:not(.table-dark) thead:not(.table-dark) th{
        color: #4C4F54;
        font-size: 12px;
        font-style: normal;
        font-weight: 700; 
    }
    .cod-table .datatable-cod tbody td{
        color: #4C4F54; 
        font-size: 14px;
        font-style: normal;
        font-weight: 400; 
        padding: 20px 16px 17px 16px;
    }
    .cod-table .datatable-cod tbody td .badge{
        font-size: 12px;
        font-style: normal;
        font-weight: 700; 
    }
    .cod-table .datatable-cod tbody td .btn{
        border-radius: 6px;
        padding: 9px 24px;
        align-items: center; 
        font-size: 16px;
        font-weight: 400; 
    }
    .cod-table .datatable-cod tbody td .btn-warning{
        background: #FFB000; 
    }
    .cod-table .datatable-cod tbody td .btn i{
        margin-right: 8px;
    }
</style>    
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex flex-column flex-md-row flex-lg-row">
            <div class="title-card-page d-flex">
                <h5>COD Collection</h5>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="popover"
                data-bs-placement="right"
                data-bs-content="This is a very beautiful popover, show some love.">
                    <path d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z" stroke="#E5E5E5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <form action="" class="filter-card ms-auto d-flex align-items-center flex-column flex-md-row flex-lg-row">
                <label class="label-filter-card" for="date-filter">Date:</label>
                <div class="input-group input-group-merge datePickerGroup">
                    <input
                    type="text"
                    class="form-control" name="date-filter" id="search-date" placeholder="DD/MM/YYYY" value="{{$date}}" data-input/>
                    <span class="input-group-text" data-toggle>
                    <i class="ti ti-calendar-event cursor-pointer"></i>
                    </span>
                </div>
                <label class="label-filter-card" for="origin-filter">Origin&nbsp;Hub:</label>
                <select id="origin-filter" class="form-select" name="origin-filter">
                    <option value="">All Hub</option>
                    <option value="1">Hub 1</option>
                    <option value="2">Hub 2</option>
                    <option value="3">Hub 3</option>
                </select>
            </form>
        </div>
        <div class="date-header">
            <div class="row">
                <div class="col-md-12">
                    <span>Date: {{ date("D, d F Y") }}</span>
                </div>
            </div>
        </div>
        <div class="courier-select">
            <form action="{{ route('cod-collection.index') }}" method="get" id="formCollection">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <label for="courierName" class="form-label">Courier Name</label>
                        <select id="courierName" name="courier" class="select2Courier form-select form-select">
                            <option value="" {{ request()->get('courier') == "" ? 'selected' : '' }} disabled> Courier Name</option>
                            @foreach ($couriers as $courier)
                            <option value="{{ $courier->courier_id }}" {{ request()->get('courier') == $courier->courier_id ? 'selected' : '' }}> {{ $courier->code.' - '.$courier->userpartner->user->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-5">
                        <label for="deliveryRecord" class="form-label">Delivery Record</label>
                        <input
                              class="form-control"
                              type="text"
                              id="deliveryRecord"
                              name="delivery_record"
                              placeholder="Delivery Record"
                              value="{{ request()->get('delivery_record') }}"
                              readonly />
                    </div>
                    <div class="col-sm-12 col-md-2 btn-submit">
                        <button type="submit" class="btn btn-primary" id="submitDeliveryRecord1">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="cod-dashboard">
            <div class="row">
                <div class="col">
                    <div class="card card-icon">
                      <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-column">
                            <div class="avatar">
                                <span class="avatar-initial bg-warning-icon">
                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.625 4.5C2.58947 4.5 1.75 5.33947 1.75 6.375V13.5H13.75V6.375C13.75 5.33947 12.9105 4.5 11.875 4.5H3.625Z" fill="#FFB000"/>
                                        <path d="M13.75 15H1.75V17.625C1.75 18.6605 2.58947 19.5 3.625 19.5H4C4 17.8431 5.34315 16.5 7 16.5C8.65685 16.5 10 17.8431 10 19.5H13C13.4142 19.5 13.75 19.1642 13.75 18.75V15Z" fill="#FFB000"/>
                                        <path d="M8.5 19.5C8.5 18.6716 7.82843 18 7 18C6.17157 18 5.5 18.6716 5.5 19.5C5.5 20.3284 6.17157 21 7 21C7.82843 21 8.5 20.3284 8.5 19.5Z" fill="#FFB000"/>
                                        <path d="M16 6.75C15.5858 6.75 15.25 7.08579 15.25 7.5V18.75C15.25 18.8368 15.2647 18.9202 15.2919 18.9977C15.5309 17.58 16.7643 16.5 18.25 16.5C19.8942 16.5 21.2294 17.8226 21.2498 19.462C22.1031 19.2869 22.7724 18.5266 22.714 17.5794C22.481 13.799 21.1275 10.321 18.9824 7.4749C18.628 7.00463 18.0765 6.75 17.5121 6.75H16Z" fill="#FFB000"/>
                                        <path d="M19.75 19.5C19.75 18.6716 19.0784 18 18.25 18C17.4216 18 16.75 18.6716 16.75 19.5C16.75 20.3284 17.4216 21 18.25 21C19.0784 21 19.75 20.3284 19.75 19.5Z" fill="#FFB000"/>
                                    </svg> 
                                </span>
                            </div>
                            <div class="d-flex align-items-center my-2">
                              <h3 class="mb-0 waybil-text">{{ isset($routing['waybill']) ? $routing['waybill'] : '-' }}</h3>
                            </div>
                            <p class="mb-0">Waybill</p>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-icon">
                      <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-column">
                            <div class="avatar">
                                <span class="avatar-initial bg-info-icon">
                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.75 7.5C11.5074 7.5 10.5 8.50736 10.5 9.75C10.5 10.9926 11.5074 12 12.75 12C13.9926 12 15 10.9926 15 9.75C15 8.50736 13.9926 7.5 12.75 7.5Z" fill="#4DA7CD"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.25 4.875C2.25 3.83947 3.08947 3 4.125 3H21.375C22.4105 3 23.25 3.83947 23.25 4.875V14.625C23.25 15.6605 22.4105 16.5 21.375 16.5H4.125C3.08947 16.5 2.25 15.6605 2.25 14.625V4.875ZM9 9.75C9 7.67893 10.6789 6 12.75 6C14.8211 6 16.5 7.67893 16.5 9.75C16.5 11.8211 14.8211 13.5 12.75 13.5C10.6789 13.5 9 11.8211 9 9.75ZM19.5 9C19.0858 9 18.75 9.33579 18.75 9.75V9.7575C18.75 10.1717 19.0858 10.5075 19.5 10.5075H19.5075C19.9217 10.5075 20.2575 10.1717 20.2575 9.7575V9.75C20.2575 9.33579 19.9217 9 19.5075 9H19.5ZM5.25 9.75C5.25 9.33579 5.58579 9 6 9H6.0075C6.42171 9 6.7575 9.33579 6.7575 9.75V9.7575C6.7575 10.1717 6.42171 10.5075 6.0075 10.5075H6C5.58579 10.5075 5.25 10.1717 5.25 9.7575V9.75Z" fill="#4DA7CD"/>
                                        <path d="M3 18C2.58579 18 2.25 18.3358 2.25 18.75C2.25 19.1642 2.58579 19.5 3 19.5C8.40005 19.5 13.6302 20.2222 18.5998 21.5749C19.7904 21.899 21 21.0168 21 19.7551V18.75C21 18.3358 20.6642 18 20.25 18H3Z" fill="#4DA7CD"/>
                                      </svg>
                                </span>
                            </div>
                            <div class="d-flex align-items-center my-2">
                              <h3 class="mb-0 waybill-cod-text">{{ isset($routing['waybill_cod']) ? $routing['waybill_cod'] : '-' }}</h3>
                            </div>
                            <p class="mb-0">Waybill COD</p>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-icon">
                      <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-column">
                            <div class="avatar">
                                <span class="avatar-initial bg-success-icon">
                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21.1556 5.99064L17.5004 7.45344L8.50039 3.85344L10.6904 2.97624C11.6918 2.57563 12.809 2.57563 13.8104 2.97624L20.7188 5.73984C20.8754 5.80219 21.0224 5.88741 21.1556 5.99064ZM12.2516 9.55344L15.8852 8.09904L6.88519 4.49904L3.78199 5.74104C3.62542 5.80337 3.47838 5.88739 3.34519 5.99064L12.2504 9.55344H12.2516ZM2.65039 7.41144C2.65039 7.27944 2.66479 7.14864 2.69239 7.02264L11.6504 10.6058V14.6462C11.2539 15.5097 11.0492 16.4489 11.0504 17.399C11.0504 18.383 11.2664 19.3154 11.6504 20.153V21.2786C11.3213 21.2315 10.999 21.1453 10.6904 21.0218L3.78199 18.2582C3.4479 18.1246 3.16152 17.894 2.95982 17.596C2.75811 17.298 2.65033 16.9465 2.65039 16.5866V7.41144ZM21.8504 7.41144V12.3074C20.669 11.3306 19.1833 10.7975 17.6504 10.8002C16.7514 10.799 15.8618 10.9819 15.0362 11.3378C14.2107 11.6936 13.4668 12.2147 12.8504 12.869V10.6058L21.8084 7.02264C21.836 7.14864 21.8504 7.27944 21.8504 7.41144ZM17.6504 22.8002C19.0826 22.8002 20.4561 22.2313 21.4688 21.2186C22.4815 20.2059 23.0504 18.8324 23.0504 17.4002C23.0504 15.9681 22.4815 14.5946 21.4688 13.5819C20.4561 12.5692 19.0826 12.0002 17.6504 12.0002C16.2182 12.0002 14.8447 12.5692 13.832 13.5819C12.8193 14.5946 12.2504 15.9681 12.2504 17.4002C12.2504 18.8324 12.8193 20.2059 13.832 21.2186C14.8447 22.2313 16.2182 22.8002 17.6504 22.8002ZM16.4504 18.3518L19.6256 15.1766C19.7381 15.064 19.8907 15.0006 20.05 15.0005C20.1288 15.0005 20.2069 15.0159 20.2797 15.046C20.3526 15.0762 20.4188 15.1203 20.4746 15.176C20.5304 15.2317 20.5746 15.2979 20.6049 15.3707C20.6351 15.4435 20.6507 15.5216 20.6507 15.6004C20.6508 15.6793 20.6353 15.7573 20.6052 15.8302C20.5751 15.903 20.5309 15.9693 20.4752 16.025L16.8752 19.625C16.8195 19.6809 16.7532 19.7253 16.6803 19.7555C16.6075 19.7857 16.5293 19.8013 16.4504 19.8013C16.3715 19.8013 16.2933 19.7857 16.2204 19.7555C16.1475 19.7253 16.0813 19.6809 16.0256 19.625L14.8256 18.425C14.7131 18.3124 14.6499 18.1596 14.6501 18.0004C14.6502 17.8412 14.7135 17.6885 14.8262 17.576C14.9389 17.4635 15.0916 17.4004 15.2508 17.4005C15.41 17.4006 15.5627 17.464 15.6752 17.5766L16.4504 18.3518Z" fill="#44CD90"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="d-flex align-items-center my-2">
                              <h3 class="mb-0 cod-delivered-text">{{ isset($routing['cod_delivered']) ? $routing['cod_delivered'] : '-' }}</h3>
                            </div>
                            <p class="mb-0">COD Delivered</p>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-icon">
                      <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-column">
                            <div class="avatar">
                                <span class="avatar-initial bg-danger-icon">
                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21.6556 5.99064L18.0004 7.45344L9.00041 3.85344L11.1904 2.97624C12.1918 2.57563 13.309 2.57563 14.3104 2.97624L21.2188 5.73984C21.3754 5.80219 21.5224 5.88741 21.6556 5.99064ZM12.7516 9.55344L16.3852 8.09904L7.38521 4.49904L4.28201 5.74104C4.12543 5.80337 3.9784 5.88739 3.84521 5.99064L12.7504 9.55344H12.7516ZM3.19121 7.02264C3.16353 7.15038 3.14985 7.28075 3.15041 7.41144V16.5866C3.15035 16.9465 3.25813 17.298 3.45983 17.596C3.66154 17.894 3.94792 18.1246 4.28201 18.2582L11.1904 21.0218C11.5024 21.1466 11.824 21.2318 12.1504 21.2786V20.1542C11.7537 19.2904 11.549 18.3508 11.5504 17.4002C11.5504 16.4162 11.7664 15.485 12.1504 14.6462V10.6058L3.19241 7.02264H3.19121ZM22.3504 7.41144V12.3086C21.1692 11.3314 19.6835 10.7978 18.1504 10.8002C17.2514 10.7992 16.3616 10.9823 15.5361 11.3383C14.7106 11.6943 13.9667 12.2157 13.3504 12.8702V10.6058L22.3084 7.02264C22.336 7.14864 22.3504 7.27944 22.3504 7.41144ZM23.5504 17.4002C23.5504 18.8324 22.9815 20.2059 21.9688 21.2186C20.9561 22.2313 19.5826 22.8002 18.1504 22.8002C16.7182 22.8002 15.3447 22.2313 14.332 21.2186C13.3193 20.2059 12.7504 18.8324 12.7504 17.4002C12.7504 15.9681 13.3193 14.5946 14.332 13.5819C15.3447 12.5692 16.7182 12.0002 18.1504 12.0002C19.5826 12.0002 20.9561 12.5692 21.9688 13.5819C22.9815 14.5946 23.5504 15.9681 23.5504 17.4002ZM20.3752 16.025C20.4879 15.9124 20.5512 15.7596 20.5512 15.6002C20.5512 15.4409 20.4879 15.2881 20.3752 15.1754C20.2625 15.0628 20.1097 14.9995 19.9504 14.9995C19.7911 14.9995 19.6383 15.0628 19.5256 15.1754L18.1504 16.5518L16.7752 15.1754C16.6625 15.0628 16.5097 14.9995 16.3504 14.9995C16.1911 14.9995 16.0383 15.0628 15.9256 15.1754C15.8129 15.2881 15.7497 15.4409 15.7497 15.6002C15.7497 15.7596 15.8129 15.9124 15.9256 16.025L17.302 17.4002L15.9256 18.7754C15.8698 18.8312 15.8256 18.8975 15.7954 18.9703C15.7652 19.0432 15.7497 19.1213 15.7497 19.2002C15.7497 19.2791 15.7652 19.3573 15.7954 19.4301C15.8256 19.503 15.8698 19.5693 15.9256 19.625C15.9814 19.6808 16.0476 19.7251 16.1205 19.7553C16.1934 19.7855 16.2715 19.801 16.3504 19.801C16.4293 19.801 16.5074 19.7855 16.5803 19.7553C16.6532 19.7251 16.7194 19.6808 16.7752 19.625L18.1504 18.2486L19.5256 19.625C19.5814 19.6808 19.6476 19.7251 19.7205 19.7553C19.7934 19.7855 19.8715 19.801 19.9504 19.801C20.0293 19.801 20.1074 19.7855 20.1803 19.7553C20.2532 19.7251 20.3194 19.6808 20.3752 19.625C20.431 19.5693 20.4752 19.503 20.5054 19.4301C20.5356 19.3573 20.5512 19.2791 20.5512 19.2002C20.5512 19.1213 20.5356 19.0432 20.5054 18.9703C20.4752 18.8975 20.431 18.8312 20.3752 18.7754L18.9988 17.4002L20.3752 16.025Z" fill="#FF6E5D"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="d-flex align-items-center my-2">
                              <h3 class="mb-0 cod-undelivered-text">{{ isset($routing['cod_undelivered']) ? $routing['cod_undelivered'] : '-' }}</h3>
                            </div>
                            <p class="mb-0">COD Undelivered</p>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-icon">
                      <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-column">
                            <div class="avatar">
                                <span class="avatar-initial bg-info-icon">
                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.1997 13C10.8736 13 9.60186 12.4732 8.66417 11.5355C7.72649 10.5979 7.19971 9.32608 7.19971 8H9.19971C9.19971 8.79565 9.51578 9.55871 10.0784 10.1213C10.641 10.6839 11.4041 11 12.1997 11C12.9954 11 13.7584 10.6839 14.321 10.1213C14.8836 9.55871 15.1997 8.79565 15.1997 8H17.1997C17.1997 9.32608 16.6729 10.5979 15.7352 11.5355C14.7976 12.4732 13.5258 13 12.1997 13ZM12.1997 3C12.9954 3 13.7584 3.31607 14.321 3.87868C14.8836 4.44129 15.1997 5.20435 15.1997 6H9.19971C9.19971 5.20435 9.51578 4.44129 10.0784 3.87868C10.641 3.31607 11.4041 3 12.1997 3ZM19.1997 6H17.1997C17.1997 5.34339 17.0704 4.69321 16.8191 4.08658C16.5678 3.47995 16.1995 2.92876 15.7352 2.46447C15.2709 2.00017 14.7198 1.63188 14.1131 1.3806C13.5065 1.12933 12.8563 1 12.1997 1C10.8736 1 9.60186 1.52678 8.66417 2.46447C7.72649 3.40215 7.19971 4.67392 7.19971 6H5.19971C4.08971 6 3.19971 6.89 3.19971 8V20C3.19971 20.5304 3.41042 21.0391 3.78549 21.4142C4.16057 21.7893 4.66927 22 5.19971 22H19.1997C19.7301 22 20.2388 21.7893 20.6139 21.4142C20.989 21.0391 21.1997 20.5304 21.1997 20V8C21.1997 7.46957 20.989 6.96086 20.6139 6.58579C20.2388 6.21071 19.7301 6 19.1997 6Z" fill="#7367F0"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="d-flex align-items-center my-2">
                              <h3 class="mb-0 value-cod-delivered-text">{{ isset($routing['value_cod_delivered']) ? number_format($routing['value_cod_delivered']) : '-' }}</h3>
                            </div>
                            <p class="mb-0">Value COD Delivered</p>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom">
        <div class="cod-table">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link active"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-pills-top-waybill"
                                aria-controls="navs-pills-top-waybill"
                                aria-selected="true">
                                Waybill
                            </button>
                            </li>
                            <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-pills-top-collection-record"
                                aria-controls="navs-pills-top-collection-record"
                                aria-selected="false">
                                Collection Record
                            </button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="navs-pills-top-waybill" role="tabpanel">
                                <h5 id="waybillTableTitle">{{ request()->get('delivery_record','-') }}</h5>
                                <div class="table-responsive text-nowrap">
                                    <table class="table datatable-cod" id="dataTableUncollected">
                                        <thead>
                                        <tr class="text-nowrap">
                                            <th style="width: 25%">waybill no</th>
                                            <th style="width: 25%">STATUS DELIVERY</th>
                                            <th style="width: 25%">COD</th>
                                            <th style="width: 25%">STATUS COLLECTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if (isset($routing['list_waybill']))
                                            @foreach ($routing['list_waybill'] as $waybill)
                                                <tr>
                                                    <td>{{ $waybill->tracking_number }}</td>
                                                    <td>
                                                        <span class="badge bg-label-{{ $waybill->status->color }}">{{ ucwords($waybill->status->name) }}</span>
                                                    </td>
                                                    <td>{{ number_format($waybill->cod_price) }}</td>
                                                    <td>
                                                        @if ($waybill->routingdetails()->orderBy('routing_id','desc')->first()->routing->status->code == 'COLLECTED')
                                                            <span class="badge bg-label-success">Collected</span>
                                                        @else
                                                            <span class="badge bg-label-warning">Uncollected</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                @php
                                    $status = "";
                                    if (isset($routing['data'])) {
                                        $status = isset($routing['data']->status->code) ? $routing['data']->status->code : "";
                                    }
                                @endphp
                                @if ($status == "ASSIGNED" || $status == "INPROGRESS")
                                <div class="row">
                                    <div class="col-md-5">
                                        <span>Total COD: <strong id="total-cod">{{ isset($routing['value_cod_uncollected']) ? number_format($routing['value_cod_uncollected']) : '0' }}</strong></span>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="d-flex align-items-center">
                                            <label class="label-filter-card me-3" for="depositAmount">Enter&nbsp;Deposited&nbsp;Amount:</label>
                                            <input
                                            type="text"
                                            class="form-control me-3" name="deposit_amount" placeholder="Amount" id="depositAmount" data-type="currency"/>
                                            <button type="submit" class="btn btn-primary" id="submitDeposited">Submit</button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="navs-pills-top-collection-record" role="tabpanel">
                                <h5>Collection Record</h5>
                                <div class="table-responsive text-nowrap">
                                    <table class="table datatable-cod" id="dataTableCollected">
                                        <thead>
                                        <tr class="text-nowrap">
                                            <th>Delivery record id</th>
                                            <th>courier</th>
                                            <th>COD deposited</th>
                                            <th>STATUS COLLECTION</th>
                                            <th>modified date</th>
                                            <th>modified by</th>
                                            <th>PRINT</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($record as $rec)
                                            <tr>
                                                <td>{{ $rec->dr_code }}</td>
                                                <td>{{ $rec->full_name }}</td>
                                                <td>{{ $rec->total_deposit }}</td>
                                                <td>
                                                    <span class="badge bg-label-{{ $rec->status_label }}">{{ $rec->status }}</span>
                                                </td>
                                                <td>{{ $rec->modified_date }}</td>
                                                <td>{{ $rec->modified_by }}</td>
                                                <td>
                                                    <a href="{{ route('cod-collection.pdf', ['id' => $rec->reconcile_id, 'type' => 'print']) }}" target="_blank" class="btn btn-warning waves-effect waves-light">
                                                        <i class="ti ti-book cursor-pointer"></i>
                                                        PDF
                                                    </a>
                                                    <a href="{{ route('cod-collection.pdf', ['id' => $rec->reconcile_id, 'type' => 'struct']) }}" target="_blank" class="btn btn-warning waves-effect waves-light">
                                                        <i class="ti ti-book cursor-pointer"></i>
                                                        Struct
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if (session()->has('error'))
<script>
    Swal.fire({
        title: 'Error!',
        text: "{{ session()->get('error') }}",
        icon: 'error',
        customClass: {
        confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
    });
</script>
@endif
<script>
    $(document).ready(function() {
        $("#courierName").on("change", function() {
            var selectedValue = $(this).val();
            if (selectedValue.length) {

                clearDashboard();

                var url = "{{ route('courier.routing', ['id' => ':id']) }}";
                url = url.replace(':id', selectedValue);
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.success) {
                            $('#deliveryRecord').val(data.data.code);
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
                        }
                        console.log(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        Swal.fire({
                            title: 'Error!',
                            text: error,
                            icon: 'error',
                            customClass: {
                            confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                    }
                });
            }
        });

        $("#formCollection").submit(function(event) {
            // Prevent the default form submission
            event.preventDefault();

            // Check the input field value
            var deliveryRecord = $('#deliveryRecord').val();

            if (deliveryRecord === "") {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please choose courier has delivery record!',
                    icon: 'error',
                    customClass: {
                    confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });

                $("#courierName").val('');
                clearDashboard();
            } else {
                // If the input is valid, you can submit the form
                this.submit();
            }
        });

        $("#submitDeposited").click(function(){
            var depositAmount = $('#depositAmount').val();
            var deliveryRecord = $('#deliveryRecord').val();
            if (!depositAmount.length) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please insert deposit amount!',
                    icon: 'error',
                    customClass: {
                    confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            } else if (!deliveryRecord.length) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please select courier has delivery record!',
                    icon: 'error',
                    customClass: {
                    confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            } else {
                Swal.fire({
                    title: 'Confirm Submit COD',
                    text: 'Are you sure to submit COD Collection with correct data?',
                    icon: 'warning',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        denyButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitCod(deliveryRecord, depositAmount);
                    }
                });
            }
        })
    });

    function submitCod(deliveryRecord, depositAmount) {
        var url = "{{ route('cod-collection.store') }}";
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                deliveryRecord:deliveryRecord,
                depositAmount:depositAmount,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Success submit COD Collection',
                        icon: 'success',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var urlWindow = "{{ route('cod-collection.pdf', ['id' => ':id', 'type' => 'print']) }}";
                            urlWindow = urlWindow.replace(':id', data.data.reconcile_id);

                            // Use window.open to open a new window
                            var newWindow = window.open(urlWindow, "_blank");

                            // Check if the new window was successfully opened
                            if (newWindow) {
                                location.reload();
                            } else {
                                // New window was blocked by the browser's pop-up blocker or some other issue
                                alert("The new window was blocked or failed to open. Please check your browser's pop-up settings.");
                            }
                        }
                    });
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
                }
                console.log(data);
            },
            error: function(xhr, status, error) {
                console.error(error);
                Swal.fire({
                    title: 'Error!',
                    text: error,
                    icon: 'error',
                    customClass: {
                    confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            }
        });
    }

    const datePickerGroup = document.querySelector('.datePickerGroup');
    if (datePickerGroup) {
        datePickerGroup.flatpickr({
            monthSelectorType: 'static',
            maxDate: 'today',
            wrap: true,
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            disableMobile: "true"
        });
    }

    const select2Courier = $('.select2Courier');
    if (select2Courier.length) {
        select2Courier.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Courier Name',
                dropdownParent: $this.parent()
            });
        });
    }

    $('#dataTableUncollected').DataTable({
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "info": false
    });

    $('#dataTableCollected').DataTable({
        "lengthChange": false,
        "searching": false,
        "paging": false,
        "info": false
    });

    function clearDashboard() {
        $('#deliveryRecord').val('');

        $('.waybil-text').html('-');
        $('.waybill-cod-text').html('-');
        $('.cod-delivered-text').html('-');
        $('.cod-undelivered-text').html('-');
        $('.value-cod-delivered-text').html('-');

        $('#waybillTableTitle').html('-');

        var dataTableUncollected = $('#dataTableUncollected').DataTable();

        // Clear all data from the DataTable
        dataTableUncollected.clear().draw();
    }

    $("input[data-type='currency']").on({
        keyup: function () {
            formatCurrency($(this));
        },
        blur: function () {
            formatCurrency($(this), "blur");
        }
    });

    function formatNumber(n) {
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function formatCurrency(input, blur) {
        var inputVal = input.val();

        if (inputVal === "") {
            return;
        }

        var originalLen = inputVal.length;
        var caretPos = input.prop("selectionStart");

        if (inputVal.indexOf(".") >= 0) {
            var decimalPos = inputVal.indexOf(".");
            var leftSide = inputVal.substring(0, decimalPos);
            var rightSide = inputVal.substring(decimalPos);
            leftSide = formatNumber(leftSide);
            rightSide = formatNumber(rightSide);

            if (blur === "blur") {
                rightSide += "00";
            }

            rightSide = rightSide.substring(0, 2);
            inputVal = leftSide + "." + rightSide;
        } else {
            inputVal = formatNumber(inputVal);
            inputVal = inputVal;
        }

        input.val(inputVal);
        var updatedLen = inputVal.length;
        caretPos = updatedLen - originalLen + caretPos;
        input[0].setSelectionRange(caretPos, caretPos);
    }

    $('#search-date').change(function()
    {
        var date = $('#search-date').val();
        window.location.href = "{{ route('cod-collection.index') }}?date="+date
    });
    
</script>
@endsection