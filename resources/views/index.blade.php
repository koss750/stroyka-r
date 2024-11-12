
    @include('components.header')

<body>
@include('components.top')
    <main>
        <section class="section-main">
            <section class="section-01">
                <div class="container_new_section" style="margin-top:105px" >
                    <h1>
                        Проекты и сметы
                    </h1>
                    <div class="inner">
                        <div class="box">
                            <div class="image">
                                <img src="/assets/images/OCBd2.jpg" alt="Дом из бревна" loading="lazy">
                            </div>
                            <div class="content">
                                <a href="/browse/doma_iz_brevna" aria-label="Перейти к проектам домов из бревна">
                                    <h2><span>Дома из Бревна</span></h2>
                                    <span class="cta">Перейти</span>
                                </a>
                            </div>
                        </div>
                        <div class="box">
                            <div class="image">
                                <img src="/assets/images/PBd.jpg" alt="Дом из бруса" loading="lazy">
                            </div>
                            <div class="content">
                                <a href="/browse/doma_iz_brusa" aria-label="Перейти к проектам домов из бруса">
                                    <h2><span>Дома из Бруса</span></h2>
                                    <span class="cta">Перейти</span>
                                </a>
                            </div>
                        </div>
                        <div class="box">
                            <div class="image">
                                <img src="/assets/images/OCBb.jpg" alt="Баня из бревна" loading="lazy">
                            </div>
                            <div class="content">
                                <a href="/browse/bani_iz_brevna" aria-label="Перейти к проектам бань из бревна">
                                    <h2><span>Бани из бревна</span></h2>
                                    <span class="cta">Перейти</span>
                                </a>
                            </div>
                        </div>
                        <div class="box">
                            <div class="image">
                                <img src="/assets/images/PBb.jpg" alt="Баня из бруса" loading="lazy">
                            </div>
                            <div class="content">
                                <a href="/browse/bani_iz_brusa" aria-label="Перейти к проектам бань из бруса">
                                    <h2><span>Бани из бруса</span></h2>
                                    <span class="cta">Перейти</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container_new_section foundations_section">
                    <h1>Расчет фундаментов</h1>
                    <div class="inner">
                        <div class="box">
                            <div class="image">
                                <img src="https://xn--80ardojfh.com/images/foundation-lenta.jpg" alt="Фундамент ленточный" loading="lazy">
                            </div>
                            <div class="content">
                                <a href="/fundament/fundament-lentochnyj/" aria-label="Перейти к расчету фундамента ленточного">
                                    <h2><span>Фундамент ленточный</span></h2>
                                    <span class="cta">Перейти</span>
                                </a>
                            </div>
                        </div>

                        <div class="box">
                            <div class="image">
                                <img src="https://xn--80ardojfh.com/images/foundation-sr.jpg" alt="Фундамент свайно-ростверковый" loading="lazy">
                            </div>
                            <div class="content">
                                <a href="/fundament/fundament-svayno-rostverkovyy/" aria-label="Перейти к расчету фундамента свайно-ростверкового">
                                    <h2><span>Фундамент свайно-ростверковый</span></h2>
                                    <span class="cta">Перейти</span>
                                </a>
                            </div>
                        </div>
                        <div class="box">
                            <div class="image">
                                <img src="https://xn--80ardojfh.com/images/foundation-lp.jpg" alt="Фундамент ленточный с плитой перекрытия" loading="lazy">
                            </div>
                            <div class="content">
                                <a href="/fundament/fundament-lentochniy-s-plitoy" aria-label="Перейти к расчету фундамента ленточного с плитой перекрытия">
                                    <h2><span>Фундамент ленточный с плитой перекрытия</span></h2>
                                    <span class="cta">Перейти</span>
                                </a>
                            </div>
                        </div>
                        
                        <div class="box">
                            <div class="image">
                                <img src="https://xn--80ardojfh.com/images/foundation-srp.jpg" alt="Фундамент свайно-ростверковый с плитой перекрытия" loading="lazy">
                            </div>
                            <div class="content">
                                <a href="/fundament/fundament-svayno-rostverkovyy-s-plitoy" aria-label="Перейти к расчету фундамента свайно-ростверкового с плитой перекрытия">
                                    <h2><span>Фундамент свайно-ростверковый с плитой перекрытия</span></h2>
                                    <span class="cta">Перейти</span>
                                </a>
                            </div>
                        </div>
                        <div class="box">
                            <div class="image">
                                <img src="https://xn--80ardojfh.com/images/foundation-mp.jpg" alt="Фундамент монолитная плита" loading="lazy">
                            </div>
                            <div class="content">
                                <a href="/fundament/fundament-monolitnaya-plita" aria-label="Перейти к расчету фундамента монолитной плиты">
                                    <h2><span>Фундамент монолитная плита</span></h2>
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

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Стройка.com",
      "url": "https://xn--80ardojfh.com",
      "description": "Проекты и сметы для домов и бань. Расчет фундаментов.",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://xn--80ardojfh.com/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      },
      "mainEntity": {
        "@type": "SiteNavigationElement",
        "name": "Основные категории",
        "description": "Основные разделы проектов и смет",
        "hasPart": [
          {
            "@type": "WebPage",
            "name": "Дома из Бревна",
            "description": "Проекты и сметы домов из бревна",
            "url": "https://xn--80ardojfh.com/browse/doma_iz_brevna"
          },
          {
            "@type": "WebPage",
            "name": "Дома из Бруса",
            "description": "Проекты и сметы домов из бруса",
            "url": "https://xn--80ardojfh.com/browse/doma_iz_brusa"
          },
          {
            "@type": "WebPage",
            "name": "Бани из бревна",
            "description": "Проекты и сметы бань из бревна",
            "url": "https://xn--80ardojfh.com/browse/bani_iz_brevna"
          },
          {
            "@type": "WebPage",
            "name": "Бани из бруса",
            "description": "Проекты и сметы бань из бруса",
            "url": "https://xn--80ardojfh.com/browse/bani_iz_brusa"
          }
        ]
      }
    }
    </script>
</body>
</html>