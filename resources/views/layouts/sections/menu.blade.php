<!-- Menu -->
@php
    $route = Route::currentRouteName();
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="">
            <img src="{{ asset('template/assets/img/website/www-icon.png') }}" style="width: 96px;"/>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Menu</span>
        </li>

        @if (Auth::user()->can('user-access.read'))
            <li class="menu-item {{ in_array($route, ['configuration.user.index']) ? 'active open' : ''}}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-settings"></i>
                    <div data-i18n="Configuration">Configuration</div>
                </a>
                <ul class="menu-sub">
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