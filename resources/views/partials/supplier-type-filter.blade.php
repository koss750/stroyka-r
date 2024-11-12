<nav aria-label="supplier type filter" class="mt-3">
    <ul class="nav nav-tabs custom-tabs justify-content-center">
        <li class="nav-item">
            <a class="nav-link {{ request('type') == null ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                Все
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('type') == 'ltd' ? 'active' : '' }}" href="{{ route('suppliers.index', ['type' => 'ltd']) }}">
                ООО
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('type') == 'se' ? 'active' : '' }}" href="{{ route('suppliers.index', ['type' => 'se']) }}">
                ИП
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('type') == 'brigade' ? 'active' : '' }}" href="{{ route('suppliers.index', ['type' => 'brigade']) }}">
                Бригады
            </a>
        </li>
        
    </ul>
</nav>