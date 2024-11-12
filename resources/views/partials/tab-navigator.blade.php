<nav aria-label="section navigation">
    <ul class="nav nav-tabs custom-tabs justify-content-center">
        @foreach($items as $item)
            <li class="nav-item">
                <a class="nav-link {{ Request::url() == url($item['url']) ? 'active' : '' }}" href="{{ $item['url'] }}">
                    {{ $item['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>