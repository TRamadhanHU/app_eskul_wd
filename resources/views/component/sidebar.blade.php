<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    {{-- @dd(Request::url()) --}}

    {{-- loop nav menu --}}
    @foreach (config('navmenu') as $menu)
        {{-- check if menu has key child --}}
        @if (array_key_exists('child', $menu))
            <li class="nav-item {{ Request::is($menu['code'] . '*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#{{ $menu['code'] }}"
                    aria-expanded="true" aria-controls="{{ $menu['code'] }}">
                    <i class="{{ $menu['icon'] }}"></i>
                    <span>{{ $menu['name'] }}</span>
                </a>
                <div id="{{ $menu['code'] }}" class="collapse {{ Request::is($menu['code'] . '*') ? 'show' : '' }}">
                    <div class="bg-white py-2 collapse-inner rounded collapse-item">
                        {{-- loop child menu --}}
                        @foreach ($menu['child'] as $child)
                            <a class="collapse-item {{ Request::url() == url($child['url']) ? 'active' : '' }}" href="{{ url($child['url']) }}">{{ $child['name'] }}</a>
                        @endforeach
                    </div>
                </div>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link" href="{{ url($menu['url']) }}">
                    <i class="{{ $menu['icon'] }}"></i>
                    <span>{{ $menu['name'] }}</span>
                </a>
            </li>
        @endif
    @endforeach

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline mt-auto">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
