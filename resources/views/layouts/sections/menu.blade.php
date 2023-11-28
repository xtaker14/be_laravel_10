<!-- Menu -->
@php
    $route = Route::currentRouteName();
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('template/assets/img/website/dethix-logo.svg') }}" style="width: 96px;"/>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @can('dashboard.read')
            <!-- Dashboards -->
            <li class="menu-item {{ $route == 'dashboard' ? 'active' : ''}}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboard">Dashboard</div>
                </a>
            </li>
            <!-- Dashboards -->
        @endcan
        
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Menu</span>
        </li>
        <!-- Delivery Order -->
        @if (Auth::user()->can('request-waybill.read') || Auth::user()->can('waybill-list.read') || Auth::user()->can('adjustment.read'))
        <li class="menu-item {{ in_array($route,['request-waybill','waybill-list','adjustment.master-waybill', 'adjustment.single-waybill', 'adjustment.delivery-process']) ? 'active open' : ''}}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-receipt"></i>
                <div data-i18n="Delivery Order">Delivery Order</div>
            </a>
            <ul class="menu-sub">
                @can('request-waybill.read')
                <li class="menu-item {{ $route == 'request-waybill' ? 'active' : ''}}">
                    <a href="{{ route('request-waybill') }}" class="menu-link">
                        <div data-i18n="Request Waybill">Request Waybill</div>
                    </a>
                </li>
                @endcan
                @can('waybill-list.read')
                <li class="menu-item {{ $route == 'waybill-list' ? 'active' : ''}}">
                    <a href="{{ route('waybill-list') }}" class="menu-link">
                        <div data-i18n="Waybill List">Waybill List</div>
                    </a>
                </li>
                @endcan
                @can('adjustment.read')
                <li class="menu-item {{ in_array($route,['adjustment.master-waybill', 'adjustment.single-waybill', 'adjustment.delivery-process']) ? 'active' : ''}}">
                    <a href="{{ route('adjustment.master-waybill') }}" class="menu-link">
                        <div data-i18n="Adjustment">Adjustment</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif
        @can('inbound.read')
        <li class="menu-item {{ $route == 'inbound' ? 'active' : ''}}">
            <a href="{{ route('inbound') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-truck"></i>
                <div data-i18n="Inbound">Inbound</div>
            </a>
        </li>
        @endcan
        @can('transfer.read')
        <li class="menu-item {{ $route == 'transfer' ? 'active' : ''}}">
            <a href="{{ route('transfer') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-lifebuoy"></i>
                <div data-i18n="Routing">Transfer</div>
            </a>
        </li>
        @endcan
        @can('delivery-record.read')
        <li class="menu-item {{ $route == 'create-record' ? 'active' : ''}}">
            <a href="{{ route('create-record') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-archive"></i>
                <div data-i18n="Delivery Record">Delivery Record</div>
            </a>
        </li>
        @endcan
        @can('cod-collection.read')
        <li class="menu-item {{ $route == 'cod-collection.index' ? 'active' : ''}}">
            <a href="{{ route('cod-collection.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-server"></i>
                <div data-i18n="COD Collection">COD Collection</div>
            </a>
        </li>
        @endcan
        @if (Auth::user()->can('report-inbound.read') || Auth::user()->can('report-delivery-order.read') || Auth::user()->can('report-transfer.read') || Auth::user()->can('report-delivery-record.read') || Auth::user()->can('report-cod-collection.read'))
        <li class="menu-item {{ in_array($route,['report.inbound','report.waybill']) ? 'active open' : ''}}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-clipboard"></i>
                <div data-i18n="Report">Report</div>
            </a>
            <ul class="menu-sub">
                @can('report-inbound.read')
                <li class="menu-item {{ $route == 'report.inbound' ? 'active' : ''}}">
                    <a href="{{ route('report.inbound') }}" class="menu-link">
                        <div data-i18n="Inbound">Inbound</div>
                    </a>
                </li>
                @endcan
                @can('report-delivery-order.read')
                <li class="menu-item {{ $route == 'report.waybill' ? 'active' : ''}}">
                    <a href="{{ route('report.waybill') }}" class="menu-link">
                        <div data-i18n="Waybill">Waybill</div>
                    </a>
                </li>
                @endcan
                @can('report-transfer.read')
                <li class="menu-item {{ $route == 'report.transfer' ? 'active' : ''}}">
                    <a href="{{ route('report.transfer') }}" class="menu-link">
                        <div data-i18n="Transfer">Transfer</div>
                    </a>
                </li>
                @endcan
                @can('report-delivery-record.read')
                <li class="menu-item {{ $route == 'report.delivery-record-report' ? 'active' : ''}}">
                    <a href="{{ route('report.delivery-record-report') }}" class="menu-link">
                        <div data-i18n="Delivery Record">Delivery Record</div>
                    </a>
                </li>
                @endcan
                @can('report-cod-collection.read')
                <li class="menu-item {{ $route == 'report.cod-report' ? 'active' : ''}}">
                    <a href="{{ route('report.cod-report') }}" class="menu-link">
                        <div data-i18n="COD Collection">COD Collection</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif
        @if (Auth::user()->can('organization.read') || Auth::user()->can('master-hub.read') || Auth::user()->can('master-vendor.read') || Auth::user()->can('master-courier.read') || Auth::user()->can('master-region.read') || Auth::user()->can('user-access.read'))
        <li class="menu-item {{ in_array($route,['configuration.organization.index','configuration.vendor.index','configuration.hub.index','configuration.courier.index', 'configuration.user.index']) ? 'active open' : ''}}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div data-i18n="Configuration">Configuration</div>
            </a>
            <ul class="menu-sub">
                @can('organization.read')
                <li class="menu-item {{ $route == 'configuration.organization.index' ? 'active' : ''}}">
                    <a href="{{ route('configuration.organization.index') }}" class="menu-link">
                        <div data-i18n="Organization">Organization</div>
                    </a>
                </li>
                @endcan
                @can('master-hub.read')
                <li class="menu-item {{ $route == 'configuration.hub.index' ? 'active' : ''}}">
                    <a href="{{ route('configuration.hub.index') }}" class="menu-link">
                        <div data-i18n="Hub">Hub</div>
                    </a>
                </li>
                @endcan
                @can('master-vendor.read')
                <li class="menu-item {{ $route == 'configuration.vendor.index' ? 'active' : ''}}">
                    <a href="{{ route('configuration.vendor.index') }}" class="menu-link">
                        <div data-i18n="Vendor">Vendor</div>
                    </a>
                </li>
                @endcan
                @can('master-courier.read')
                <li class="menu-item {{ $route == 'configuration.courier.index' ? 'active' : ''}}">
                    <a href="{{ route('configuration.courier.index') }}" class="menu-link">
                        <div data-i18n="Courier">Courier</div>
                    </a>
                </li>
                @endcan
                @can('master-region.read')
                <li class="menu-item {{ $route == 'configuration.region.index' ? 'active' : ''}}">  
                    <a href="{{ route('configuration.region.index') }}" class="menu-link">
                        <div data-i18n="Master Origin">Master Region</div>
                    </a>
                </li>
                @endcan
                @can('user-access.read')
                <li class="menu-item {{ $route == 'configuration.user.index' ? 'active' : ''}}">
                    <a href="{{ route('configuration.user.index') }}" class="menu-link">
                        <div data-i18n="User Access">User Access</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif
    </ul>
</aside>
<!-- / Menu -->