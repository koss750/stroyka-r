@extends('layouts.default')

@section('content')
<div class="container-fluid">
	<!-- Add Order -->
	<div class="modal fade" id="addOrderModalside">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Create Project</h5>
					<button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						@csrf
						<div class="form-group">
							<label class="text-black font-w500">Project Name</label>
							<input type="text" class="form-control">
						</div>
						<div class="form-group">
							<label class="text-black font-w500">Deadline</label>
							<input type="date" class="form-control">
						</div>
						<div class="form-group">
							<label class="text-black font-w500">Client Name</label>
							<input type="text" class="form-control">
						</div>
						<div class="form-group">
							<button type="button" class="btn btn-primary">CREATE</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="page-titles">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="javascript:void(0)">Shop </a></li>
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Checkout</a></li>
		</ol>
	</div>
	<div class="row">
		<div class="col-xl-12">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-lg-4 order-lg-2 mb-5">
							<h4 class="d-flex justify-content-between align-items-center mb-3">
								<span class="text-muted">Your cart</span>
								<span class="badge badge-primary badge-pill">3</span>
							</h4>
							<ul class="list-group mb-3">
								<li class="list-group-item d-flex justify-content-between lh-condensed">
									<div>
										<h6 class="my-0">Проект 220</h6>
										<small class="text-muted">Смета с персонализированными настройками</small>
									</div>
									<span class="text-muted">250 руб.</span>
								</li>
								<li class="list-group-item d-flex justify-content-between">
									<span>Итого</span>
									<strong>250 руб.</strong>
								</li>
							</ul>

							<form>
								@csrf
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Promo code">
									<div class="input-group-append">
										<button type="submit" class="btn btn-primary">Применить</button>
									</div>
								</div>
							</form>
						</div>
						<div class="col-lg-8 order-lg-1">
							<h4 class="mb-3">Ваш адрес</h4>
							<form class="needs-validation" novalidate="">
								@csrf
								<div class="row">
									<div class="col-md-6 mb-3">
										<label for="firstName">Имя</label>
										<input type="text" class="form-control" id="firstName" placeholder="" value="" required="">
										<div class="invalid-feedback">
											Введите имя
										</div>
									</div>
									<div class="col-md-6 mb-3">
										<label for="lastName">Фамилия</label>
										<input type="text" class="form-control" id="lastName" placeholder="" value="" required="">
										<div class="invalid-feedback">
											Введите фамилию
										</div>
									</div>
								</div>

								<div class="mb-3">
									<label for="email">Email <span
											class="text-muted">(Optional)</span></label>
									<input type="email" class="form-control" id="email" placeholder="you@example.com">
									<div class="invalid-feedback">
										Введите email
									</div>
								</div>

								<div class="mb-3">
									<label for="address">Адрес</label>
									<input type="text" class="form-control" id="address" placeholder="1234 Main St" required="">
									<div class="invalid-feedback">
										Please enter your shipping address.
									</div>
								</div>

								<div class="mb-3">
									<label for="address2"> <span
											class="text-muted">(Optional)</span></label>
									<input type="text" class="form-control" id="address2" placeholder="Apartment or suite">
								</div>

								
								<div class="custom-control custom-checkbox mb-2">
									<input type="checkbox" class="form-check-input" id="save-info">
									<label class="custom-control-label" for="save-info">Сохранить информацию на будущее</label>
								</div>
								<hr class="mb-4">

								<h4 class="mb-3">Payment</h4>

								<div class="d-block my-3">
									<div class="custom-control custom-radio mb-2">
										<input id="credit" name="paymentMethod" type="radio" class="form-check-input" checked="" required="">
										<label class="custom-control-label" for="credit">Оплата картой</label>
									</div>
									<div class="custom-control custom-radio mb-2">
										<input id="debit" name="paymentMethod" type="radio" class="form-check-input" required="">
										<label class="custom-control-label" for="debit">Оплата СБП</label>
									</div>
									<div class="custom-control custom-radio mb-2">
										<input id="paypal" name="paymentMethod" type="radio" class="form-check-input" required="">
										<label class="custom-control-label" for="paypal">Оплачу потом</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 mb-3">
										<label for="cc-name">Имя на карте</label>
										<input type="text" class="form-control" id="cc-name" placeholder="" required="">
										<small class="text-muted">Имя, как оно отображено на карте</small>
									</div>
									<div class="col-md-6 mb-3">
										<label for="cc-number">Номер карты</label>
										<input type="text" class="form-control" id="cc-number" placeholder="" required="">
									</div>
								</div>
								<div class="row">
									<div class="col-md-3 mb-3">
										<label for="cc-expiration">Expiration</label>
										<input type="text" class="form-control" id="cc-expiration" placeholder="" required="">
										<div class="invalid-feedback">
											Expiration date required
										</div>
									</div>
									<div class="col-md-3 mb-3">
										<label for="cc-expiration">CVV</label>
										<input type="text" class="form-control" id="cc-cvv" placeholder="" required="">
										<div class="invalid-feedback">
											Security code required
										</div>
									</div>
								</div>
								<hr class="mb-4">
								<button class="btn btn-primary btn-lg d-block w-100" type="submit">Оплатить</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection