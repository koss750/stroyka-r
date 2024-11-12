@extends('layouts.default')

@section('content')
<div class="container-fluid">
	<!-- Добавить заказ -->
	<div class="modal fade" id="addOrderModalside">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Создать проект</h5>
					<button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						@csrf
						<div class="form-group">
							<label class="text-black font-w500">Название проекта</label>
							<input type="text" class="form-control">
						</div>
						<div class="form-group">
							<label class="text-black font-w500">Срок выполнения</label>
							<input type="date" class="form-control">
						</div>
						<div class="form-group">
							<label class="text-black font-w500">Имя клиента</label>
							<input type="text" class="form-control">
						</div>
						<div class="form-group">
							<button type="button" class="btn btn-primary">СОЗДАТЬ</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="d-md-flex d-block mb-3 pb-3 border-bottom">
		<div class="card-action card-tabs mb-md-0 mb-3  me-auto">
			<ul class="nav nav-tabs tabs-lg">
				<li class="nav-item">
					<a href="#navpills-1" class="nav-link active" data-bs-toggle="tab" aria-expanded="false"><span class="badge light badge-primary">2</span>Поставщики</a>
				</li>	
				<li class="nav-item">
					<a href="#navpills-2" class="nav-link" data-bs-toggle="tab" aria-expanded="false"><span class="badge light badge-primary">0</span>Исполнители</a>
				</li>	
			</ul>
		</div>
		<div>
			<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addOrderModal"  class="btn btn-primary rounded"><i class="fa fa-user me-2 scale5" aria-hidden="true"></i>+ Новый контакт</a>
			<!-- Добавить заказ -->
			<div class="modal fade" id="addOrderModal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Добавить контакт</h5>
							<button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form>
								@csrf
								<div class="form-group">
									<label class="text-black font-w500">Имя</label>
									<input type="text" class="form-control">
								</div>
								<div class="form-group">
									<label class="text-black font-w500">Фамилия</label>
									<input type="text" class="form-control">
								</div>
								<div class="form-group">
									<label class="text-black font-w500">Адрес</label>
									<input type="text" class="form-control">
								</div>
								<div class="form-group">
									<button type="button" class="btn btn-primary">СОХРАНИТЬ</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-9 col-xxl-8 col-lg-8">
			<div class="tab-content">
				<div class="tab-pane fade show active" id="navpills-1" role="tabpanel">
					<div class="row loadmore-content" id="RecentActivitiesContent">
						<!-- Замена имен на названия российских строительных компаний -->
						<div class="col-xl-4 col-xxl-6 col-sm-6">
							<div class="card contact-bx">	
								<div class="card-body">
									<div class="media">
										<div class="image-bx me-3 me-lg-2 me-xl-3 ">
											<img src="{{ asset('images/users/13.jpg')}}" alt="" class="rounded-circle" width="90">
											<span class="active"></span>
										</div>
										<div class="media-body">
											<h6 class="fs-20 font-w600 mb-0"><a href="{{ url('app-profile')}}" class="text-black">СтройМатериалы</a></h6>
											<p class="fs-14">ООО "СтройГрупп"</p>
											<ul>
												<li><a href="{{ url('messages')}}"><i class="las la-sms"></i></a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-4 col-xxl-6 col-sm-6">
							<div class="card contact-bx">	
								<div class="card-body">
									<div class="media">
										<div class="image-bx me-3 me-lg-2 me-xl-3 ">
											<img src="{{ asset('images/users/13.jpg')}}" alt="" class="rounded-circle" width="90">
											<span class="active"></span>
										</div>
										<div class="media-body">
											<h6 class="fs-20 font-w600 mb-0"><a href="{{ url('app-profile')}}" class="text-black">Константин Б.</a></h6>
											<p class="fs-14">Borodin Services Ltd</p>
											<ul>
												<li><a href="{{ url('messages')}}"><i class="las la-sms"></i></a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Продолжение списка контактов с названиями компаний -->
					</div>
					<div class="row">
						<div class="col-xl-12 mb-4 text-center">
							<a href="javascript:void(0)" class="btn btn-outline-primary mx-auto dlab-load-more"  rel="{{ url('ajax/contacts')}}" id="RecentActivities">Загрузить еще</a>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="navpills-2" role="tabpanel">
					<div class="row loadmore-content" id="RecentActivities2Content">
						<!-- Контент ожидающих приглашений с измененными на российские строительные компании именами -->
					</div>
					<div class="row">
						<div class="col-xl-12 mb-4 text-center">
							<a href="javascript:void(0)" class="btn btn-outline-primary mx-auto dlab-load-more"  rel="{{ url('ajax/contacts')}}" id="RecentActivities2">Загрузить еще</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-xxl-4 col-lg-4">
			<!-- Боковая панель профиля -->
		</div>
	</div>
</div>
@endsection
