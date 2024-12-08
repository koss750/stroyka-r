<footer class="footer-email">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <p>© {{ date('Y') }} Стройка.com</p>
            </div>
            <div class="col-sm-8">
                <ul>
                    <li class="footer-email-item"><a href="{{ route('terms.and.conditions') }}">Политика конфиденциальности</a></li>
                    @if (isset($project))
                        <li class="footer-email-item"><a href="{{ route('fiscal.receipt', $project['payment_reference']) }}">Фискальный чек</a></li>
                    @endif
                    <li class="footer-email-item"><a href="{{ route('terms.and.conditions') }}">Условия использования</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>