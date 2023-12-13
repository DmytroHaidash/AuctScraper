<header id="app-header">
    <ul class="d-flex align-items-center list-unstyled mb-0">
        <div class="d-flex justify-content-center" id="app-aside">
            @foreach(app('nav')->backend() as $nav)
                <li id="submenu-heading-{{ $loop->iteration }}"
                    class="nav-item">
                    <a href="{!! route($nav->route) !!}" class="d-flex align-items-center mr-2 {{ app('router')->is($nav->route) ? ' is-active' : '' }}">
                        {{ $nav->name }}
                    </a>
                </li>
            @endforeach
        </div>
        <li class="ml-auto">
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
                <svg width="20" height="20" style="transform: scale(-1)">
                    <use xlink:href="#exit"></use>
                </svg>
            </a>
        </li>
    </ul>
</header>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
