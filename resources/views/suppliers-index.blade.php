@extends('layouts.alternative')


@section('canonical', '')


@section('additional_head')
<title>Строители</title>
<meta name="description" content="Строители на портале Стройка.com. Здесь вы можете найти и выбрать строительную компанию для выполнения вашего заказа.">
@endsection

@section('content')
<div class="container-fluid">
	@include('partials.tab-navigator', ['items' => [
			['url' => '/my-orders', 'label' => 'Заказы'],
			['url' => '/suppliers', 'label' => 'Строители'],
			['url' => '/messages', 'label' => 'Мои переписки'],
			['url' => '/profile', 'label' => 'Мои данные'],
		]])
	@include('partials.supplier-type-filter')

	<div class="row">
		@forelse($suppliers as $supplier)
			<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
				<div class="card h-100">
					<div class="card-body">
						<div class="row">
							<div class="col-5 text-center">
								<img src="{{ $supplier->profile_picture_url ?? asset('images/profile/default.png') }}" class="img-fluid rounded-circle mb-3" alt="{{ $supplier->company_name }}">
								<div class="rating">
									@if($supplier->yandex_maps_link)
										@for ($i = 1; $i <= $supplier->state_status; $i++)
											<a href="{{ $supplier->yandex_maps_link }}" target="_blank" class="ml-2 no-text-decoration" title="Посмотреть на Яндекс Картах">
												<i class="fas fa-star {{ $i <= 5 ? 'text-warning' : 'text-muted' }}"></i>
											</a>
										@endfor
										<p class="text-muted extra-small mb-2">
											Рейтинг от "Яндекс Карты"
										</p>
									@else
										@for ($i = 1; $i <= 5; $i++)
											<i class="fas fa-star text-muted"></i>
										@endfor
										<p class="text-muted extra-small mb-2">
											Рейтинг от "Яндекс Карты" недоступен
										</p>
									@endif
								</div>
							</div>
							<div class="col-7">
								<h4 class="mb-1">{{ $supplier->company_name }}</h4>
								<div class="row mt-3">
									<div class="col-12">
										@php
											$regions = $supplier->regions->pluck('name')->first();
											$isLong = strlen($regions) > 66;
										@endphp
										
										<p class="mb-0">
											<strong>Регион:</strong>
											<span class="{{ $isLong ? 'regions-preview' : '' }}">
												{{ $isLong ? Str::limit($regions, 66) : $regions }}
											</span>
											
											@if ($isLong)
												<span class="regions-full d-none">{{ $regions }}</span>
												<a class="regions-toggle" href="#" role="button">
													<span class="show-more">Показать все</span>
													<span class="show-less d-none">Скрыть</span>
												</a>
											@endif
										</p>
									</div>
								</div>
								<p class="text-muted small mb-2">
									<i class="fas fa-check-circle text-success"></i> ИНН: {{ $supplier->inn ?? 'Не указан' }}
								</p>
								<p class="text-muted small mb-2">
									<i class="fas fa-check-circle text-success"></i> ОГРН: {{ $supplier->ogrn ?? 'Не указан' }}
								</p>
								
								
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-6">
								<a href="{{ route('supplier.profile', $supplier->id) }}" class="btn btn-primary btn-block">Профиль</a>
							</div>
							<div class="col-6">
								@if($supplier->user_id !== Auth::id())
									<a href="{{ route('messages.index') }}?supplier_id={{ $supplier->user_id }}" 
									   class="btn btn-outline-primary btn-block">Сообщение</a>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		@empty
			<div class="col-12">
				<p>На данный момент нет одобренных исполнителей.</p>
			</div>
		@endforelse
	</div>
</div>
@endsection

@section('additional_scripts')
<script>
	$(document).ready(function() {
		$('.regions-toggle').click(function(e) {
			e.preventDefault();
			$(this).siblings('.regions-preview, .regions-full').toggleClass('d-none');
			$(this).find('.show-more, .show-less').toggleClass('d-none');
		});
	});
</script>
@endsection

<style>
	.no-text-decoration {
		text-decoration: none;
	}
</style>
