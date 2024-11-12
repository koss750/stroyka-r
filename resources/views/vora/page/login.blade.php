@extends('layouts.fullwidth')

@section('content')
<div class="col-xl-12">
	<div class="card">
		<div class="card-body p-0">
			<div class="row m-0">
				<div class="col-xl-6 col-md-6 sign text-center">
					<div>
						<div class="text-center my-5">
							<a href="{{ url('index')}}"><img src="{{ asset('images/logo-dark.png')}}" alt=""></a>
						</div>
						<img src="{{ asset('images/log.png')}}" class="img-fix bitcoin-img sd-shape7"></img>
					</div>
				</div>
				<div class="col-xl-6 col-md-6">
					<div class="sign-in-your py-4 px-2">
						<h4 class="fs-20">Войдите в свой аккаунт</h4>
						<span>Добро пожаловать обратно! Войдите с вашими данными, которые вы указали<br> во время регистрации</span>
						<form action="{{ url('index')}}">
                        @csrf
							<div class="mb-3">
								<label class="mb-1"><strong>Email</strong></label>
								<input type="email" class="form-control" value="hello@example.com">
							</div>
							<div class="mb-3">
								<label class="mb-1"><strong>Пароль</strong></label>
								<input type="password" class="form-control" value="Password">
							</div>
							<div class="row d-flex justify-content-between mt-4 mb-2">
								<div class="mb-3">
								   <div class="form-check custom-checkbox ms-1">
										<input type="checkbox" class="form-check-input" id="basic_checkbox_1">
										<label class="form-check-label" for="basic_checkbox_1">Запомнить мои предпочтения</label>
									</div>
								</div>
								<div class="mb-3">
									<a href="{{ url('page-forgot-password')}}">Забыли пароль?</a>
								</div>
							</div>
							<div class="text-center">
								<button type="submit" class="btn btn-primary btn-block">Войти</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
