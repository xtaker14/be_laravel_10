<!-- Menu -->
@php
    $route = Route::currentRouteName();
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
        <li class="menu-item {{ $route == 'dashboard' ? 'active' : ''}}">
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
        <li class="menu-item {{ in_array($route,['request-waybill','waybill-list','adjustment']) ? 'active open' : ''}}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-receipt"></i>
                <div data-i18n="Delivery Order">Delivery Order</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ $route == 'request-waybill' ? 'active' : ''}}">
                <a href="{{ route('request-waybill') }}" class="menu-link">
                    <div data-i18n="Request Waybill">Request Waybill</div>
                </a>
                </li>
                <li class="menu-item {{ $route == 'waybill-list' ? 'active' : ''}}">
                <a href="{{ route('waybill-list') }}" class="menu-link">
                    <div data-i18n="Waybill List">Waybill List</div>
                </a>
                </li>
                <li class="menu-item {{ $route == 'adjustment' ? 'active' : ''}}">
                <a href="{{ route('adjustment') }}" class="menu-link">
                    <div data-i18n="Adjustment">Adjustment</div>
                </a>
                </li>
            </ul>
        </li>
        <li class="menu-item {{ $route == 'inbound' ? 'active' : ''}}">
            <a href="{{ route('inbound') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-truck"></i>
                <div data-i18n="Inbound">Inbound</div>
            </a>
        </li>
        <li class="menu-item {{ $route == 'transfer' ? 'active' : ''}}">
            <a href="{{ route('transfer') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-lifebuoy"></i>
                <div data-i18n="Routing">Transfer</div>
            </a>
        </li>
        <li class="menu-item {{ $route == 'create-record' ? 'active' : ''}}">
            <a href="{{ route('create-record') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-archive"></i>
                <div data-i18n="Delivery Record">Delivery Record</div>
            </a>
        </li>
        <li class="menu-item {{ $route == 'cod-collection.index' ? 'active' : ''}}">
            <a href="{{ route('cod-collection.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-server"></i>
                <div data-i18n="COD Collection">COD Collection</div>
            </a>
        </li>
        <li class="menu-item {{ in_array($route,['report.inbound']) ? 'active open' : ''}}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-clipboard"></i>
                <div data-i18n="Report">Report</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ $route == 'report.inbound' ? 'active' : ''}}">
                    <a href="{{ route('report.inbound') }}" class="menu-link">
                        <div data-i18n="Inbound">Inbound</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Waybill">Waybill</div>
                    </a>
                </li>
                <li class="menu-item {{ $route == 'report.transfer' ? 'active' : ''}}">
                    <a href="{{ route('report.transfer') }}" class="menu-link">
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
        <li class="menu-item {{ in_array($route,['configuration.vendor.index','configuration.hub.index','configuration.courier.index','configuration.region.index']) ? 'active open' : ''}}">
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