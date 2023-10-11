<!-- Menu -->
@php
    $active = null;
    $route = Route::currentRouteName();

    if($route == "dashboard")
        $active = 'active';
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}">
        <img src="{{ asset('template/assets/img/website/dethix-logo.svg') }}" />
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{$active}}">
            <a href="{{ route('dashboard') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-smart-home"></i>
            <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
        <!-- Dashboards -->
        
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Menu</span>
        </li>
        <!-- Delivery Order -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-receipt"></i>
                <div data-i18n="Delivery Order">Delivery Order</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                <a href="{{ route('request-waybill') }}" class="menu-link">
                    <div data-i18n="Request Waybill">Request Waybill</div>
                </a>
                </li>
                <li class="menu-item">
                <a href="{{ route('waybill-list') }}" class="menu-link">
                    <div data-i18n="Waybill List">Waybill List</div>
                </a>
                </li>
                <li class="menu-item">
                <a href="{{ route('adjustment') }}" class="menu-link">
                    <div data-i18n="Adjustment">Adjustment</div>
                </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a href="{{ route('inbound') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-truck"></i>
                <div data-i18n="Inbound">Inbound</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('routing') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-lifebuoy"></i>
                <div data-i18n="Routing">Routing</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('create-record') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-archive"></i>
                <div data-i18n="Delivery Record">Delivery Record</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('cod-collection') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-server"></i>
                <div data-i18n="COD Collection">COD Collection</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-clipboard"></i>
                <div data-i18n="Report">Report</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Inbound">Inbound</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Waybill">Waybill</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Transfer">Transfer</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Delivery Record">Delivery Record</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="COD Collection">COD Collection</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item {{ in_array($route,['configuration.vendor.index','configuration.hub.index','configuration.courier.index']) ? 'active open' : ''}}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div data-i18n="Configuration">Configuration</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Organization">Organization</div>
                    </a>
                </li>
                <li class="menu-item {{ $route == 'configuration.hub.index' ? 'active' : ''}}">
                    <a href="{{ route('configuration.hub.index') }}" class="menu-link">
                        <div data-i18n="Hub">Hub</div>
                    </a>
                </li>
                <li class="menu-item {{ $route == 'configuration.vendor.index' ? 'active' : ''}}">
                    <a href="{{ route('configuration.vendor.index') }}" class="menu-link">
                        <div data-i18n="Vendor">Vendor</div>
                    </a>
                </li>
                <li class="menu-item {{ $route == 'configuration.courier.index' ? 'active' : ''}}">
                    <a href="{{ route('configuration.courier.index') }}" class="menu-link">
                        <div data-i18n="Courier">Courier</div>
                    </a>
                </li>
                <li class="menu-item {{ $route == 'configuration.region.index' ? 'active' : ''}}">  
                    <a href="{{ route('configuration.region.index') }}" class="menu-link">
                        <div data-i18n="Master Origin">Master Region</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="User Access">User Access</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>
<!-- / Menu -->