<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">
    <channel>
        <title>Портал Стройка.com</title>
        <link>https://xn--80ardojfh.com</link>
        <description>Дома из бруса и бревна</description>
        <language>ru</language>
        @foreach($designs as $design)
            <item turbo="true">
                <title>{{ $design->title }}</title>
                <link>{{ route('design.show', $design->slug) }}</link>
                <pubDate>{{ $design->created_at->toRfc822String() }}</pubDate>
                <metrics>
                    <yandex schema_identifier="Идентификатор">
                        <breadcrumblist>
                            <breadcrumb url="https://xn--80ardojfh.com/browse/doma_iz_brusa" text="Дома из бруса"/>
                            <breadcrumb url="https://xn--80ardojfh.com/browse/doma_iz_brevna" text="Дома из бревна"/>
                            <breadcrumb url="https://xn--80ardojfh.com/browse/bani_iz_brusa" text="Бани из бруса"/>
                            <breadcrumb url="https://xn--80ardojfh.com/browse/bani_iz_brevna" text="Бани из бревна"/>
                        </breadcrumblist>
                    </yandex>
                </metrics>
                <turbo:content>
                    <![CDATA[
                        <header>
                            <h1>{{ $design->title }}</h1>
                            <figure>
                                <img src="{{ $design->image_url }}" />
                            </figure>
                        </header>
                        
                        <h2>Домокомплект: {{ $design->etiketka }} ₽</h2>
                        
                        <p>Площадь: {{ $design->size }} м²</p>
                    ]]>
                </turbo:content>
            </item>
        @endforeach
    </channel>
</rss>