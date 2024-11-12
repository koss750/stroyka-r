<div class="headerMenu">
    <ul class="nav">
        @auth
            <li><a href="">Личный Кабинет</a></li>
        @else
            <li><a href="">Регистрация</a></li>
            <li><a href="">Войти</a></li>
        @endauth
    </ul>
</div>