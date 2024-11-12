@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <!-- Add Order -->
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
                            <label class="text-black font-w500">Срок сдачи</label>
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
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Электронная почта</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Входящие</a></li>
        </ol>
    </div>
    <!-- row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="email-left-box px-0 mb-3">
                        <div class="p-0">
                            <a href="{{ url('email-compose')}}" class="btn btn-primary d-block">Написать</a>
                        </div>
                        <div class="mail-list mt-4">
                            <a href="{{ url('email-inbox')}}" class="list-group-item active"><i
                                    class="fa fa-inbox font-18 align-middle me-2"></i> Входящие <span
                                    class="badge badge-primary badge-sm float-end">198</span> </a>
                            <a href="javascript:void(0);" class="list-group-item"><i
                                    class="fa fa-paper-plane font-18 align-middle me-2"></i>Отправленные</a> 
                            <a href="javascript:void(0);" class="list-group-item"><i
                                    class="fa fa-star font-18 align-middle me-2"></i>Важное <span
                                    class="badge badge-danger text-white badge-sm float-end">47</span>
                            </a>
                            <a href="javascript:void(0);" class="list-group-item"><i
                                    class="mdi mdi-file-document-box font-18 align-middle me-2"></i>Черновики</a>
                            <a href="javascript:void(0);" class="list-group-item"><i
                                    class="fa fa-trash font-18 align-middle me-2"></i>Корзина</a>
                        </div>
                        <div class="intro-title d-flex justify-content-between">
                            <h5>Категории</h5>
                            <i class="icon-arrow-down" aria-hidden="true"></i>
                        </div>
                        <div class="mail-list mt-4">
                            <a href="{{ url('email-inbox')}}" class="list-group-item"><span class="icon-warning"><i
                                        class="fa fa-circle" aria-hidden="true"></i></span>
                                Поддержка </a>
                            <a href="{{ url('email-inbox')}}" class="list-group-item"><span class="icon-primary"><i
                                        class="fa fa-circle" aria-hidden="true"></i></span>
                                Строители </a>
                            <a href="{{ url('email-inbox')}}" class="list-group-item"><span class="icon-success"><i
                                        class="fa fa-circle" aria-hidden="true"></i></span>
                                Поставщики </a>
                        </div>
                    </div>
                    <div class="email-right-box ms-0 ms-sm-4 ms-sm-0">
                        <div class="toolbar mb-4" role="toolbar">
                            <div class="btn-group mb-1 me-2">
                                <button type="button" class="btn btn-primary light px-3"><i class="fa fa-archive"></i></button>
                                <button type="button" class="btn btn-primary light px-3"><i class="fa fa-exclamation-circle"></i></button>
                                <button type="button" class="btn btn-primary light px-3"><i class="fa fa-trash"></i></button>
                            </div>
                            <div class="btn-group mb-1 me-2">
                                <button type="button" class="btn btn-primary light dropdown-toggle px-3" data-bs-toggle="dropdown">
                                    <i class="fa fa-folder"></i> <b class="caret m-l-5"></b>
                                </button>
                                <div class="dropdown-menu"> 
                                    <a class="dropdown-item" href="javascript: void(0);">Социальные сети</a> 
                                    <a class="dropdown-item" href="javascript: void(0);">Акции</a> 
                                    <a class="dropdown-item" href="javascript: void(0);">Обновления</a>
                                    <a class="dropdown-item" href="javascript: void(0);">Форумы</a>
                                </div>
                            </div>
                            <div class="btn-group mb-1 me-2">
                                <button type="button" class="btn btn-primary light dropdown-toggle px-3" data-bs-toggle="dropdown">
                                    <i class="fa fa-tag"></i> <b class="caret m-l-5"></b>
                                </button>
                                <div class="dropdown-menu"> 
                                    <a class="dropdown-item" href="javascript: void(0);">Обновления</a> 
                                    <a class="dropdown-item" href="javascript: void(0);">Социальные сети</a> 
                                    <a class="dropdown-item" href="javascript: void(0);">Акции</a>
                                    <a class="dropdown-item" href="javascript: void(0);">Форумы</a>
                                </div>
                            </div>
                            <div class="btn-group mb-1 me-2">
                                <button type="button" class="btn btn-primary light dropdown-toggle v" data-bs-toggle="dropdown">Еще <span class="caret m-l-5"></span>
                                </button>
                                <div class="dropdown-menu"> <a class="dropdown-item" href="javascript: void(0);">Пометить как непрочитанное</a> <a class="dropdown-item" href="javascript: void(0);">Добавить в задачи</a>
                                    <a class="dropdown-item" href="javascript: void(0);">Добавить звездочку</a> <a class="dropdown-item" href="javascript: void(0);">Без звука</a>
                                </div>
                            </div>
                        </div>
						<div class="email-list mt-3">
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input " id="checkbox2">
												<label class="custom-control-label" for="checkbox2"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Ingredia Nutrisha, A collection of textile samples lay spread out on the table - Samsa was a travelling salesman - and above it there hung a picture</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox3">
												<label class="custom-control-label" for="checkbox3"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox4">
												<label class="custom-control-label" for="checkbox4"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox5">
												<label class="custom-control-label" for="checkbox5"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox6">
												<label class="custom-control-label" for="checkbox6"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Ingredia Nutrisha, A collection of textile samples lay spread out on the table - Samsa was a travelling salesman - and above it there hung a picture</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox7">
												<label class="custom-control-label" for="checkbox7"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox8">
												<label class="custom-control-label" for="checkbox8"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message unread">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox9">
												<label class="custom-control-label" for="checkbox9"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message unread">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox10">
												<label class="custom-control-label" for="checkbox10"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Ingredia Nutrisha, A collection of textile samples lay spread out on the table - Samsa was a travelling salesman - and above it there hung a picture</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox11">
												<label class="custom-control-label" for="checkbox11"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox12">
												<label class="custom-control-label" for="checkbox12"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox13">
												<label class="custom-control-label" for="checkbox13"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox14">
												<label class="custom-control-label" for="checkbox14"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Ingredia Nutrisha, A collection of textile samples lay spread out on the table - Samsa was a travelling salesman - and above it there hung a picture</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message unread">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox15">
												<label class="custom-control-label" for="checkbox15"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox16">
												<label class="custom-control-label" for="checkbox16"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox17">
												<label class="custom-control-label" for="checkbox17"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox18">
												<label class="custom-control-label" for="checkbox18"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Ingredia Nutrisha, A collection of textile samples lay spread out on the table - Samsa was a travelling salesman - and above it there hung a picture</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox19">
												<label class="custom-control-label" for="checkbox19"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message unread">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox20">
												<label class="custom-control-label" for="checkbox20"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
							<div class="message">
								<div>
									<div class="d-flex message-single">
										<div class="ps-1 align-self-center">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="form-check-input" id="checkbox21">
												<label class="custom-control-label" for="checkbox21"></label>
											</div>
										</div>
										<div class="ms-2">
											<button class="border-0 bg-transparent align-middle p-0"><i
													class="fa fa-star" aria-hidden="true"></i></button>
										</div>
									</div>
									<a href="{{ url('email-read')}}" class="col-mail col-mail-2">
										<div class="subject">Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of</div>
										<div class="date">11:49 am</div>
									</a>
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(".fa.fa-star").click(function () {
        $(this).toggleClass("yellow");
    });
</script>
@endpush

