<nav aria-label="breadcrumb">
    <ol class="breadcrumb custom-breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ url('/site') }}" class="text-secondary">Главная страница</a>
        </li>

        @php
        $currentUrl = Request::url();
        $category = '';
        $categoryUrl = '';

        if (strpos($currentUrl, 'dom-iz-brusa') !== false) {
            $category = 'Дома из бруса';
            $categoryUrl = '/browse/doma-iz-brusa';
        } elseif (strpos($currentUrl, 'dom-iz-brevna') !== false) {
            $category = 'Дома из бревна';
            $categoryUrl = '/browse/doma-iz-brevna';
        } elseif (strpos($currentUrl, 'banya-iz-brusa') !== false) {
            $category = 'Бани из бруса';
            $categoryUrl = '/browse/bani-iz-brusa';
        } elseif (strpos($currentUrl, 'banya-iz-brevna') !== false) {
            $category = 'Бани из бревна';
            $categoryUrl = '/browse/bani-iz-brevna';
        }

        $items = [
            ['label' => 'Главная страница', 'url' => '/site'],
            ['label' => $category, 'url' => $categoryUrl],
            ['label' => $design->title ?? 'Текущий проект', 'url' => $currentUrl]
        ];
        @endphp

        @if($category)
        <li class="breadcrumb-item">
            <a href="{{ url($categoryUrl) }}" class="text-secondary">{{ $category }}</a>
        </li>
        @endif

        <li class="breadcrumb-item active">
            <span class="text-dark font-weight-bold">{{ $design->title ?? 'Текущий проект' }}</span>
        </li>
    </ol>
</nav>