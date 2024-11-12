
	
    @include('components.header')

<body>
@include('components.top')
    <main>
        <section class="section-main">
        
            <section class="section-01">
            
                <div class="container_new_section" style="margin-top:105px" >
                @include('partials.tab-navigator', ['items' => [
                        ['url' => '/my-orders', 'label' => 'Заказы'],
                        ['url' => '/suppliers', 'label' => 'Строители'],
                        ['url' => '/messages', 'label' => 'Мои переписки'],
                        ['url' => '/profile', 'label' => 'Мои данные'],
                    ]])
                    <div class="inner">
                    
                        <div class="box">
                            <div class="image">
                                <img src="/assets/images/orders.jpeg" alt="Заказы" loading="lazy">
                            </div>
                            <div class="content">
                                <a href="/my-orders" aria-label="Заказы">
                                    <h2><span>Заказы</span></h2>
                                    <span class="cta">Перейти</span>
                                </a>
                            </div>
                        </div>
                        <div class="box">
                            <div class="image">
                                <img src="/assets/images/contractors.jpeg" alt="Исполнители" loading="lazy">
                            </div>
                            <div class="content">
                                <a href="/suppliers" aria-label="Строители">
                                    <h2><span>Строители</span></h2>
                                    <span class="cta">Перейти</span>
                                </a>
                            </div>
                        </div>
                        <div class="box">
                            <div class="image">
                                <img src="/assets/images/messages.jpeg" alt="Переписки" loading="lazy">
                            </div>
                            <div class="content">
                                <a href="/messages" aria-label="Переписки">
                                    <h2><span>Мои переписки</span></h2>
                                    <span class="cta">Перейти</span>
                                </a>
                            </div>
                        </div>
                        <div class="box">
                            <div class="image">
                                <img src="/assets/images/profile.webp" alt="Мои данные" loading="lazy">
                            </div>
                            <div class="content">
                                <a href="/profile" aria-label="Мои данные">
                                    <h2><span>Мои данные</span></h2>
                                    <span class="cta">Перейти</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </main>

    <script>
        // Existing JavaScript
    </script>

    @include('components.podval')

    
</body>
</html>