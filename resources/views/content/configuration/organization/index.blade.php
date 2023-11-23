@extends('layouts.main')
@section('styles')
<link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
<style>
    .summary-organization .card-count{
        padding: 16px 24px;
        background: #FFF;
        box-shadow: 0px 4px 24px 0px rgba(0, 0, 0, 0.06) !important;
        margin-bottom: 40px;
    }
    .summary-organization .card-count .icon-bg{
        padding: 12px;
    }
    .summary-organization .card-count .content-text h4{
        color: #4C4F54;
        font-size: 24px;
        font-style: normal;
        font-weight: 700;
    }
    .summary-organization .card-count .content-text p{
        color: #4C4F54;
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
    }
    .summary-organization .card-total-waybill{
        height: 100%;
    }
    .summary-organization .card-total-waybill .img-logo{
        max-width: 148px;
    }
    .summary-organization .card-total-waybill .organization-name{
        color:#4C4F54;
        text-align: center;
        font-size: 20px;
        font-style: normal;
        font-weight: 700;
        line-height: 16px;
    }
    .summary-organization .card-total-waybill .organization-app-name{
        color:#4C4F54;
        text-align: center;
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
        line-height: 16px;
        margin-bottom: 0px;
    }
    .summary-organization .card-total-waybill .organization-since{
        border-radius: 4px;
        background: rgba(77, 167, 205, 0.12);
        color: #4DA7CD;
        text-align: center;
        font-size: 12px;
        font-style: normal;
        font-weight: 700;
        line-height: 16px;
    }
    .summary-organization .left-card{
        padding-bottom: 40px;
    }
    .detail .form-label{
        color:#4C4F54;
        font-size: 12px;
        font-style: normal;
        font-weight: 700;
        line-height: 16px;
        margin-top: 16px;
    }
    .needs-validation #country-code{
        max-width: 74px;
        color: #4C4F54;
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
        line-height: 16px;
        padding: 0.422rem 0.45rem 0.422rem 0.875rem;
        background-position: right 0.375rem center;
    }
    .upload-input input[type="file"]{
        display: none;
    }
    .upload-input .preview{
        border-radius: 8px;
        border: 2px dashed#E5E5E5;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 8px;
        align-self: stretch;
        height: 180px;
        cursor: pointer;
        text-align: center;
        flex-direction: column;
    }
    .upload-input img{
        max-height: 100%;
        max-width: 100%;
    }
</style>
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card card-custom">
        <div class="card-header d-flex">
            <h5>Organization</h5>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="popover"
            data-bs-placement="right"
            data-bs-content="This is a very beautiful popover, show some love.">
                <path d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z" stroke="#E5E5E5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <button type="button" class="btn btn-primary ms-auto">Create</button>
        </div>
        <div class="summary-organization">
            <div class="row">
                <div class="col-md-5 left-card">
                    <div class="card card-count card-total-waybill d-flex align-items-center justify-content-center">
                        <div class="d-flex flex-column align-items-center text-center gap-3 me-4 me-sm-0">
                            <img src="{{ asset('assets/logo/logo-1.png') }}" class="img-responsive img-logo" alt="" srcset="">
                            <span class="organization-name">{{ $organization->name }}</span>
                            <p class="organization-app-name">{{ $organization->organizationdetail->company_name }}</p>
                            <span class="badge bg-label-info organization-since">Activated at {{ Carbon\Carbon::parse($organization->created_date)->format('F Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="card card-count">
                                <div class="d-flex flex-column align-items-center gap-3 me-4 me-sm-0 text-center">
                                    <span class="icon-bg bg-label-info rounded-pill">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.1519 2.75186C11.6205 2.28323 12.3803 2.28323 12.849 2.75186L21.249 11.1519C21.5922 11.4951 21.6948 12.0112 21.5091 12.4596C21.3233 12.908 20.8858 13.2004 20.4004 13.2004H19.2004V20.4004C19.2004 21.0631 18.6632 21.6004 18.0004 21.6004H15.6004C14.9377 21.6004 14.4004 21.0631 14.4004 20.4004V16.8004C14.4004 16.1377 13.8632 15.6004 13.2004 15.6004H10.8004C10.1377 15.6004 9.60043 16.1377 9.60043 16.8004V20.4004C9.60043 21.0631 9.06317 21.6004 8.40043 21.6004H6.00043C5.33768 21.6004 4.80043 21.0631 4.80043 20.4004V13.2004H3.60043C3.11507 13.2004 2.67751 12.908 2.49177 12.4596C2.30603 12.0112 2.4087 11.4951 2.7519 11.1519L11.1519 2.75186Z" fill="#4DA7CD"/>
                                        </svg>
                                    </span>
                                    <div class="content-text">
                                        <h4 class="mb-0">{{ $summary['hub'] }}</h4>
                                        <p class="mb-0">Hub</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="card card-count">
                                <div class="d-flex flex-column align-items-center gap-3 me-4 me-sm-0 text-center">
                                    <span class="icon-bg bg-label-success rounded-pill">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M5.22335 2.25C4.72607 2.25 4.24916 2.44754 3.89752 2.79917L2.59835 4.09835C1.13388 5.56282 1.13388 7.93718 2.59835 9.40165C3.93551 10.7388 6.03124 10.8551 7.50029 9.75038C8.12669 10.2206 8.90598 10.5 9.75 10.5C10.5941 10.5 11.3736 10.2205 12 9.75016C12.6264 10.2205 13.4059 10.5 14.25 10.5C15.094 10.5 15.8733 10.2206 16.4997 9.75038C17.9688 10.8551 20.0645 10.7388 21.4016 9.40165C22.8661 7.93718 22.8661 5.56282 21.4016 4.09835L20.1025 2.79918C19.7508 2.44755 19.2739 2.25 18.7767 2.25L5.22335 2.25Z" fill="#44CD90"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3 20.25V11.4951C4.42021 12.1686 6.0799 12.1681 7.50044 11.4944C8.18265 11.8183 8.94611 12 9.75 12C10.5541 12 11.3177 11.8182 12 11.4942C12.6823 11.8182 13.4459 12 14.25 12C15.0539 12 15.8173 11.8183 16.4996 11.4944C17.9201 12.1681 19.5798 12.1686 21 11.4951V20.25H21.75C22.1642 20.25 22.5 20.5858 22.5 21C22.5 21.4142 22.1642 21.75 21.75 21.75H2.25C1.83579 21.75 1.5 21.4142 1.5 21C1.5 20.5858 1.83579 20.25 2.25 20.25H3ZM6 14.25C6 13.8358 6.33579 13.5 6.75 13.5H9.75C10.1642 13.5 10.5 13.8358 10.5 14.25V17.25C10.5 17.6642 10.1642 18 9.75 18H6.75C6.33579 18 6 17.6642 6 17.25V14.25ZM14.25 13.5C13.8358 13.5 13.5 13.8358 13.5 14.25V19.5C13.5 19.9142 13.8358 20.25 14.25 20.25H17.25C17.6642 20.25 18 19.9142 18 19.5V14.25C18 13.8358 17.6642 13.5 17.25 13.5H14.25Z" fill="#44CD90"/>
                                        </svg>
                                    </span>
                                    <div class="content-text">
                                        <h4 class="mb-0">{{ $summary['vendor'] }}</h4>
                                        <p class="mb-0">Vendor</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="card card-count">
                                <div class="d-flex flex-column align-items-center gap-3 me-4 me-sm-0 text-center">
                                    <span class="icon-bg bg-label-danger rounded-pill">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.49984 6C7.49984 3.51472 9.51456 1.5 11.9998 1.5C14.4851 1.5 16.4998 3.51472 16.4998 6C16.4998 8.48528 14.4851 10.5 11.9998 10.5C9.51456 10.5 7.49984 8.48528 7.49984 6Z" fill="#FF6E5D"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.75109 20.1053C3.82843 15.6156 7.49183 12 11.9998 12C16.508 12 20.1714 15.6157 20.2486 20.1056C20.2537 20.4034 20.0822 20.676 19.8115 20.8002C17.4326 21.8918 14.7864 22.5 12.0002 22.5C9.2137 22.5 6.56728 21.8917 4.18816 20.7999C3.91749 20.6757 3.74596 20.4031 3.75109 20.1053Z" fill="#FF6E5D"/>
                                        </svg>
                                    </span>
                                    <div class="content-text">
                                        <h4 class="mb-0">{{ $summary['courier'] }}</h4>
                                        <p class="mb-0">Courier</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="card card-count">
                                <div class="d-flex flex-column align-items-center gap-3 me-4 me-sm-0 text-center">
                                    <span class="icon-bg bg-label-danger rounded-pill">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M3.375 3C2.33947 3 1.5 3.83947 1.5 4.875V5.625C1.5 6.66053 2.33947 7.5 3.375 7.5H20.625C21.6605 7.5 22.5 6.66053 22.5 5.625V4.875C22.5 3.83947 21.6605 3 20.625 3H3.375Z" fill="#FF6E5D"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.08679 9L3.62657 18.1762C3.71984 19.7619 5.03296 21 6.62139 21H17.3783C18.9667 21 20.2799 19.7619 20.3731 18.1762L20.9129 9H3.08679ZM9.24976 12.75C9.24976 12.3358 9.58554 12 9.99976 12H13.9998C14.414 12 14.7498 12.3358 14.7498 12.75C14.7498 13.1642 14.414 13.5 13.9998 13.5H9.99976C9.58554 13.5 9.24976 13.1642 9.24976 12.75Z" fill="#FF6E5D"/>
                                        </svg>
                                    </span>
                                    <div class="content-text">
                                        <h4 class="mb-0">{{ $summary['origin'] }}</h4>
                                        <p class="mb-0">Origin</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="card card-count">
                                <div class="d-flex flex-column align-items-center gap-3 me-4 me-sm-0 text-center">
                                    <span class="icon-bg bg-label-primary rounded-pill">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M3.375 3C2.33947 3 1.5 3.83947 1.5 4.875V5.625C1.5 6.66053 2.33947 7.5 3.375 7.5H20.625C21.6605 7.5 22.5 6.66053 22.5 5.625V4.875C22.5 3.83947 21.6605 3 20.625 3H3.375Z" fill="#7367F0"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.08679 9L3.62657 18.1762C3.71984 19.7619 5.03296 21 6.62139 21H17.3783C18.9667 21 20.2799 19.7619 20.3731 18.1762L20.9129 9H3.08679ZM12 10.5C12.4142 10.5 12.75 10.8358 12.75 11.25V16.1893L14.4697 14.4697C14.7626 14.1768 15.2374 14.1768 15.5303 14.4697C15.8232 14.7626 15.8232 15.2374 15.5303 15.5303L12.5303 18.5303C12.2374 18.8232 11.7626 18.8232 11.4697 18.5303L8.46967 15.5303C8.17678 15.2374 8.17678 14.7626 8.46967 14.4697C8.76256 14.1768 9.23744 14.1768 9.53033 14.4697L11.25 16.1893V11.25C11.25 10.8358 11.5858 10.5 12 10.5Z" fill="#7367F0"/>
                                        </svg>
                                    </span>
                                    <div class="content-text">
                                        <h4 class="mb-0">{{ $summary['destination'] }}</h4>
                                        <p class="mb-0">Destination</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="card card-count">
                                <div class="d-flex flex-column align-items-center gap-3 me-4 me-sm-0 text-center">
                                    <span class="icon-bg bg-label-warning rounded-pill">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.5 9.83233V11.625C1.5 12.6605 2.33947 13.5 3.375 13.5H20.625C21.6605 13.5 22.5 12.6605 22.5 11.625V9.83233C22.5 9.11619 22.2438 8.42368 21.7778 7.87995L18.4929 4.04763C17.923 3.38269 17.0909 3 16.2151 3H7.78485C6.90908 3 6.07703 3.38269 5.50708 4.04763L2.22223 7.87995C1.75618 8.42368 1.5 9.1162 1.5 9.83233ZM7.78485 4.5C7.34697 4.5 6.93094 4.69134 6.64597 5.02381L3.88067 8.25H7.04584C8.0489 8.25 8.98559 8.7513 9.54199 9.5859L9.70609 9.83205C9.98429 10.2493 10.4526 10.5 10.9542 10.5H13.0458C13.5474 10.5 14.0157 10.2493 14.2939 9.83205L14.458 9.5859C15.0144 8.7513 15.9511 8.25 16.9542 8.25H20.1193L17.354 5.02381C17.0691 4.69134 16.653 4.5 16.2151 4.5H7.78485Z" fill="#FFB000"/>
                                            <path d="M2.8125 15C2.08763 15 1.5 15.5876 1.5 16.3125V18C1.5 19.6569 2.84315 21 4.5 21H19.5C21.1569 21 22.5 19.6569 22.5 18V16.3125C22.5 15.5876 21.9124 15 21.1875 15H16.9542C15.9511 15 15.0144 15.5013 14.458 16.3359L14.2939 16.5821C14.0157 16.9993 13.5474 17.25 13.0458 17.25H10.9542C10.4526 17.25 9.98429 16.9993 9.70609 16.5821L9.54199 16.3359C8.98559 15.5013 8.0489 15 7.04584 15H2.8125Z" fill="#FFB000"/>
                                        </svg>
                                    </span>
                                    <div class="content-text">
                                        <h4 class="mb-0" id="sum-routing">{{ $summary['user'] }}</h4>
                                        <p class="mb-0">Users</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary float-end" id="btn-detail">Details</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom detail d-none" id="detail">
        <form action="{{ route('configuration.organization.update', $organization->organization_id) }}" class="needs-validation" enctype="multipart/form-data" method="post" novalidate>
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" for="company-name">Company Name <i class="text-danger">*</i></label>
                    <input type="text" id="company-name" name="company-name" class="form-control{{ $errors->has('company-name') ? ' is-invalid' : '' }}" placeholder="Company Name" value="{{ old('company-name', $organization->organizationdetail->company_name) }}" required/>
                    <div class="invalid-feedback">Please input company name.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="application-name">Application Name</label>
                    <input type="text" id="application-name" name="application-name" class="form-control{{ $errors->has('application-name') ? ' is-invalid' : '' }}" placeholder="Application Name" value="{{ old('application-name', $organization->organizationdetail->application_name) }}"/>
                    <div class="invalid-feedback">Please input application name.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-country">Company's Country</label>
                    <select name="company-country" id="company-country" class="form-select{{ $errors->has('company-country') ? ' is-invalid' : '' }}">
                        <option value="" {{ old('company-country', $organization->organizationdetail->country_id) == "" ? 'selected' : '' }} disabled>Select Country</option>
                        @foreach ($countries as $key => $country)
                            <option value="{{ $key }}" {{ old('company-country', $organization->organizationdetail->country_id) == $key ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Please select company's country.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-province">Company's Province</label>
                    <select name="company-province" id="company-province" class="form-select{{ $errors->has('company-province') ? ' is-invalid' : '' }}">
                        <option value="" {{ old('company-province', $organization->organizationdetail->province_id) == "" ? 'selected' : '' }} disabled>Select Province</option>
                        @foreach ($provinces as $key => $provincy)
                            <option value="{{ $key }}" {{ old('company-province', $organization->organizationdetail->province_id) == $key ? 'selected' : '' }}>{{ $provincy }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Please select company's province.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-address">Company's Address</label>
                    <input type="text" id="company-address" name="company-address" class="form-control{{ $errors->has('company-address') ? ' is-invalid' : '' }}" placeholder="Company's Address" value="{{ old('company-address', $organization->organizationdetail->address) }}"/>
                    <div class="invalid-feedback">Please input company's address.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="postal-code">Postal Code</label>
                    <input type="text" id="postal-code" name="postal-code" class="form-control{{ $errors->has('postal-code') ? ' is-invalid' : '' }}" placeholder="Postal Code" value="{{ old('postal-code', $organization->organizationdetail->postal_code) }}"/>
                    <div class="invalid-feedback">Please input postal code.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="postal-code">Phone Number</label>
                    <div class="input-group">
                        <select
                          class="form-select{{ $errors->has('country-code') ? ' is-invalid' : '' }}"
                          id="country-code"
                          aria-label="country code" name="country-code">
                          <option value="+62" {{ old('country-code', substr($organization->organizationdetail->phone_number, 0, 3)) == '+62' ? 'selected' : '' }}>+62</option>
                        </select>
                        <input type="text" class="form-control{{ $errors->has('phone-number') ? ' is-invalid' : '' }}" aria-label="Phone Number" placeholder="Phone Number" name="phone-number" id="phone-number" value="{{ old('phone-number', substr($organization->organizationdetail->phone_number, 3)) }}"/>
                    </div>
                    <div class="invalid-feedback">Please input phone number.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="fax">Fax</label>
                    <input type="text" id="fax" name="fax" class="form-control{{ $errors->has('fax') ? ' is-invalid' : '' }}" placeholder="Fax" value="{{ old('fax', $organization->organizationdetail->fax) }}" />
                    <div class="invalid-feedback">Please input fax.</div>
                </div>
                <div class="col-12">
                    <hr class="my-4" />
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-bank-name">Company Bank Name </label>
                    <input type="text" id="company-bank-name" name="company-bank-name" class="form-control{{ $errors->has('company-bank-name') ? ' is-invalid' : '' }}" placeholder="Company Bank Name" value="{{ old('company-bank-name', $organization->organizationdetail->bank_name) }}" />
                    <div class="invalid-feedback">Please input company bank name.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-bank-account">Company Bank Account </label>
                    <input type="text" id="company-bank-account" name="company-bank-account" class="form-control{{ $errors->has('company-bank-account') ? ' is-invalid' : '' }}" placeholder="Company Bank Account" value="{{ old('company-bank-account', $organization->organizationdetail->bank_account) }}" />
                    <div class="invalid-feedback">Please input company bank account.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-bank-accounts-address">Company Bank Account's Address</label>
                    <input type="text" id="company-bank-accounts-address" name="company-bank-accounts-address" class="form-control{{ $errors->has('company-bank-accounts-address') ? ' is-invalid' : '' }}" placeholder="Company Bank Account's Address" value="{{ old('company-bank-accounts-address', $organization->organizationdetail->bank_account_address) }}" />
                    <div class="invalid-feedback">Please input company bank account's address.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-bank-accounts-name">Company Bank Account's  Name</label>
                    <input type="text" id="company-bank-accounts-name" name="company-bank-accounts-name" class="form-control{{ $errors->has('company-bank-accounts-name') ? ' is-invalid' : '' }}" placeholder="Company Bank Account's  Name" value="{{ old('company-bank-accounts-name', $organization->organizationdetail->bank_account_name) }}" />
                    <div class="invalid-feedback">Please input company bank account's name.</div>
                </div>
                <div class="col-12">
                    <hr class="my-4" />
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="number-of-employees">Number of Employees</label>
                    <input type="text" id="number-of-employees" name="number-of-employees" class="form-control{{ $errors->has('number-of-employees') ? ' is-invalid' : '' }}" placeholder="Number of Employees" value="{{ old('number-of-employees', $organization->organizationdetail->number_of_employees) }}"/>
                    <div class="invalid-feedback">Please input number of employees.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="website-company">Website Company</label>
                    <input type="url" id="website-company" name="website-company" class="form-control{{ $errors->has('website-company') ? ' is-invalid' : '' }}" placeholder="Website Company" value="{{ old('website-company', $organization->organizationdetail->website_company) }}"/>
                    <div class="invalid-feedback">Please input valid website company.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="email-company">E-mail Company</label>
                    <input type="email" id="email-company" name="email-company" class="form-control{{ $errors->has('email-company') ? ' is-invalid' : '' }}" placeholder="E-mail Company" value="{{ old('email-company', $organization->organizationdetail->email_company) }}"/>
                    <div class="invalid-feedback">Please input valid e-mail company.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="instagram-account">Instagram Account</label>
                    <input type="text" id="instagram-account" name="instagram-account" class="form-control{{ $errors->has('instagram-account') ? ' is-invalid' : '' }}" placeholder="Instagram Account" value="{{ old('instagram-account', $organization->organizationdetail->instagram_account) }}" />
                    <div class="invalid-feedback">Please input instagram account.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="twitter-account">Twitter Account</label>
                    <input type="text" id="twitter-account" name="twitter-account" class="form-control{{ $errors->has('twitter-account') ? ' is-invalid' : '' }}" placeholder="Twitter Account" value="{{ old('twitter-account', $organization->organizationdetail->twitter_account) }}"/>
                    <div class="invalid-feedback">Please input twitter account.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="facebook-account">Facebook Account</label>
                    <input type="text" id="facebook-account" name="facebook-account" class="form-control{{ $errors->has('facebook-account') ? ' is-invalid' : '' }}" placeholder="Facebook Account" value="{{ old('facebook-account', $organization->organizationdetail->facebook_account) }}"/>
                    <div class="invalid-feedback">Please input facebook account.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="linkedin-account">LinkedIn Account</label>
                    <input type="text" id="linkedin-account" name="linkedin-account" class="form-control{{ $errors->has('linkedin-account') ? ' is-invalid' : '' }}" placeholder="LinkedIn Account" value="{{ old('linkedin-account', $organization->organizationdetail->linkedin_account) }}"/>
                    <div class="invalid-feedback">Please input linkedIn account.</div>
                </div>
                <div class="col-md-4 upload-input">
                    <label class="form-label" for="company-logo">Company Logo</label>
                    <label class="preview">
                        <img src="{{ $organization->organizationdetail->company_logo == '' ? asset('assets/icon/upload-icon-2.png') : asset('storage/'.$organization->organizationdetail->company_logo) }}" id="company-logo-preview">
                        <input type="file" id="company-logo" accept="image/*" onchange="showCompanyLogo(event);" name="company-logo">
                        @if (!file_exists( public_path().'/storage/'.$organization->organizationdetail->company_logo))
                            <strong class="company-logo-info">Drag & drop files</strong>
                            <span class="company-logo-info">Supported formates: JPEG & PNG</span>
                        @endif
                        <div class="invalid-feedback">Please upload company logo.</div>
                    </label>
                </div>
                <div class="col-md-4 upload-input">
                    <label class="form-label" for="background-login">Background Login</label>
                    <label class="preview">
                        <img src="{{ $organization->organizationdetail->background_login == '' ? asset('assets/icon/upload-icon-2.png') : asset('storage/'.$organization->organizationdetail->background_login) }}" id="background-login-preview">
                        <input type="file" id="background-login" accept="image/*" onchange="showBackgroundLogin(event);" name="background-login">
                        @if (!file_exists( public_path().'/storage/'.$organization->organizationdetail->background_login))
                            <strong class="company-logo-info">Drag & drop files</strong>
                            <span class="company-logo-info">Supported formates: JPEG & PNG</span>
                        @endif
                        <div class="invalid-feedback">Please upload background login.</div>
                    </label>
                </div>
                <div class="col-md-4 upload-input">
                    <label class="form-label" for="dokumen-logo">Dokumen Logo</label>
                    <label class="preview">
                        <img src="{{ $organization->organizationdetail->dokumen_logo == '' ? asset('assets/icon/upload-icon-2.png') : asset('storage/'.$organization->organizationdetail->dokumen_logo) }}" id="dokumen-logo-preview">
                        <input type="file" id="dokumen-logo" accept="image/*" onchange="showDokumenLogo(event);" name="dokumen-logo">
                        @if (!file_exists( public_path().'/storage/'.$organization->organizationdetail->dokumen_logo))
                            <strong class="company-logo-info">Drag & drop files</strong>
                            <span class="company-logo-info">Supported formates: JPEG & PNG</span>
                        @endif
                        <div class="invalid-feedback">Please upload dokumen logo.</div>
                    </label>
                </div>
            </div>
            <div class="pt-4">
                <button type="reset" class="btn btn-label-secondary float-end">Reset</button>
                <button type="submit" class="btn btn-primary me-sm-3 me-1 float-end">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('template/assets/vendor/libs/masonry/masonry.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('template/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#btn-detail").click(function() {
            if ($("#detail").hasClass("d-none")) {
                $("#detail").removeClass("d-none");
            } else {
                $("#detail").addClass("d-none");
            }
        })

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const bsValidationForms = document.querySelectorAll('.needs-validation');

        // Loop over them and prevent submission
        Array.prototype.slice.call(bsValidationForms).forEach(function (form) {
        form.addEventListener(
            'submit',
            function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    true;
                }

                form.classList.add('was-validated');
            },
            false
        );
        });
    })

    function showCompanyLogo(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("company-logo-preview");
            var info = document.getElementsByClassName("company-logo-info");
            preview.src = src;
            preview.style.display = "block";
            while(info.length > 0){
                info[0].parentNode.removeChild(info[0]);
            }
        }
    }

    function showBackgroundLogin(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("background-login-preview");
            var info = document.getElementsByClassName("background-login-info");
            preview.src = src;
            preview.style.display = "block";
            while(info.length > 0){
                info[0].parentNode.removeChild(info[0]);
            }
        }
    }

    function showDokumenLogo(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("dokumen-logo-preview");
            var info = document.getElementsByClassName("dokumen-logo-info");
            preview.src = src;
            preview.style.display = "block";
            while(info.length > 0){
                info[0].parentNode.removeChild(info[0]);
            }
        }
    }
</script>
@endsection