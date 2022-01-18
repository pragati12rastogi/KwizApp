   <li class="@isset($menu['child']) nav-item  @endisset @if($menu['link']==Request::getRequestUri()) menu-is-opening menu-open @else {{$menu['link']}} @endif">
    <a href="{{ $menu['link'] }}" class="nav-link ">
        <i class="nav-icon {{ $menu['icon'] }}"></i> 
        <p>
            {{ $menu['name'] }}
            @isset($menu['child'])
            <i class="right fas fa-angle-left"></i>
            @endisset
        </p>
    </a>
    @isset($menu['child'])
        <ul class="nav nav-treeview">
            @each('layouts.sidebar', $menu['child'], 'menu')
        </ul>
    @endisset
    </li>
