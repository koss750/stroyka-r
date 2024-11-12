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
	<div class="row">
		<div class="col-xl-5">
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-body d-sm-flex d-block align-items-center">
							<div class="d-flex me-auto mb-sm-0 mb-2 align-items-center">
								<img src="{{ asset('images/users/1.png')}}" alt="" width="60" class="rounded-circle me-3">
								<div>
									<h5 class="fs-18 text-black font-w600">Peter Parkur</h5>
									<div class="dropdown">
										<a href="javascript:void(0)" class="text-primary-active" data-bs-toggle="dropdown" aria-expanded="false">
											<svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
												<circle cx="4" cy="4" r="4" fill="#2953E8"/>
											</svg>
											Available
											<i class="las la-angle-down text-dark ms-2"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item" href="javascript:void(0);">Available</a>
											<a class="dropdown-item" href="javascript:void(0);">Unavailable</a>
										</div>
									</div>
								</div>
							</div>
							<a href="{{ url('contacts')}}" class="btn btn-primary"><i class="las la-comment-dots me-2 scale5"></i>+ New</a>
						</div>
					</div>
				</div>
				<div class="col-xl-12">
					<div class="card">
						<div class="card-header d-sm-flex d-block shadow-sm border-0 align-items-center">
							<div class="card-action card-tabs">
								<ul class="nav nav-tabs" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" data-bs-toggle="tab" href="#AllMessage" role="tab" aria-selected="false">
											All Message
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-bs-toggle="tab" href="#Unread" role="tab" aria-selected="false">
											Unread
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-bs-toggle="tab" href="#Archived" role="tab" aria-selected="true">
											Archived
										</a>
									</li>
								</ul>
							</div>
						</div>
						<div class="card-body message-bx dlab-scroll height520" id="message-bx">
							<div class="tab-content">
								<div class="tab-pane fade show active" id="AllMessage" role="tabpanel">
									<div class="media mb-4">
										<div class="image-bx">
											<img src="{{ asset('images/users/2.png')}}" alt="" class="rounded-circle me-sm-4 me-2 img-1">
										</div>
										<div class="media-body">
											<div class="d-flex mb-sm-2 mb-0">
												<h6 class="text-black mb-0 font-w600 fs-16">Olivia Rellaq</h6>
												<span class="ms-auto fs-14">25m ago</span>
											</div>
											<p class="text-black">Nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
										</div>
									</div>
									<div class="media mb-4">
										<div class="image-bx me-sm-4 me-2">
											<img src="{{ asset('images/users/3.png')}}" alt="" class="rounded-circle img-1">
											<span class="active"></span>
										</div>
										<div class="media-body">
											<div class="d-flex mb-sm-2 mb-0">
												<h6 class="text-black font-w600 fs-16 mb-0">Roberto Charloz</h6>
												<span class="ms-auto fs-14">2m ago</span>
											</div>
											<p class="text-black">Hey, check my design update last night. Tell me what you think and if that is OK. I hear client said they want to change the layout concept</p>
										</div>
									</div>
									<div class="media mb-4">
										<div class="image-bx me-sm-4 me-2">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle img-1">
											<span class="active"></span>
										</div>
										<div class="media-body">
											<div class="d-flex mb-sm-2 mb-0">
												<h6 class="text-black font-w600 fs-16 mb-0">Laura Chyan</h6>
												<span class="ms-auto fs-14">5m ago</span>
											</div>
											<p class="text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut</p>
										</div>
									</div>
									<div class="media mb-4">
										<div class="image-bx me-sm-4 me-2">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle img-1">
											<span class="active"></span>
										</div>
										<div class="media-body">
											<div class="d-flex mb-sm-2 mb-0">
												<h6 class="text-black font-w600 fs-16 mb-0">Keanu Tipes</h6>
												<span class="ms-auto fs-14">41m ago</span>
											</div>
											<p class="text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut</p>
										</div>
									</div>
									<div class="media mb-4">
										<div class="image-bx">
											<img src="{{ asset('images/users/6.png')}}" alt="" class="rounded-circle img-1 me-sm-4 me-2">
										</div>
										<div class="media-body">
											<div class="d-flex mb-sm-2 mb-0">
												<h6 class="text-black font-w600 fs-16 mb-0">Olivia Rellaq</h6>
												<span class="ms-auto fs-14">25m ago</span>
											</div>
											<p class="text-black">Nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
										</div>
									</div>
									<div class="media">
										<div class="image-bx">
											<img src="{{ asset('images/users/7.png')}}" alt="" class="rounded-circle img-1 me-sm-4 me-2">
										</div>
										<div class="media-body">
											<div class="d-flex mb-sm-2 mb-0">
												<h6 class="text-black font-w600 fs-16 mb-0">Olivia Rellaq</h6>
												<span class="ms-auto fs-14">25m ago</span>
											</div>
											<p class="text-black">Nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="Unread" role="tabpanel">
									<div class="media mb-4">
										<div class="image-bx me-sm-4 me-2">
											<img src="{{ asset('images/users/3.png')}}" alt="" class="rounded-circle img-1">
											<span class="active"></span>
										</div>
										<div class="media-body">
											<div class="d-flex mb-sm-2 mb-0">
												<h6 class="text-black font-w600 fs-16 mb-0">Roberto Charloz</h6>
												<span class="ms-auto fs-14">2m ago</span>
											</div>
											<p class="text-black">Hey, check my design update last night. Tell me what you think and if that is OK. I hear client said they want to change the layout concept</p>
										</div>
									</div>
									<div class="media mb-4">
										<div class="image-bx me-sm-4 me-2">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle img-1">
											<span class="active"></span>
										</div>
										<div class="media-body">
											<div class="d-flex mb-sm-2 mb-0">
												<h6 class="text-black font-w600 fs-16 mb-0">Keanu Tipes</h6>
												<span class="ms-auto fs-14">41m ago</span>
											</div>
											<p class="text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut</p>
										</div>
									</div>
									<div class="media mb-4">
										<div class="image-bx me-sm-4 me-2">
											<img src="{{ asset('images/users/6.png')}}" alt="" class="rounded-circle img-1">
											<span class="active"></span>
										</div>
										<div class="media-body">
											<div class="d-flex mb-sm-2 mb-0">
												<h6 class="text-black font-w600 fs-16 mb-0">Olivia Rellaq</h6>
												<span class="ms-auto fs-14">25m ago</span>
											</div>
											<p class="text-black">Nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
										</div>
									</div>
									<div class="media">
										<div class="image-bx me-sm-4 me-2">
											<img src="{{ asset('images/users/7.png')}}" alt="" class="rounded-circle img-1">
											<span class="active"></span>
										</div>
										<div class="media-body">
											<div class="d-flex mb-sm-2 mb-0">
												<h6 class="text-black font-w600 fs-16 mb-0">Olivia Rellaq</h6>
												<span class="ms-auto fs-14">25m ago</span>
											</div>
											<p class="text-black">Nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="Archived" role="tabpanel">
									<div class="media mb-4">
										<div class="image-bx me-sm-4 me-2">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle img-1">
											<span class="active"></span>
										</div>
										<div class="media-body">
											<div class="d-flex mb-sm-2 mb-0">
												<h6 class="text-black font-w600 fs-16 mb-0">Laura Chyan</h6>
												<span class="ms-auto fs-14">5m ago</span>
											</div>
											<p class="text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut</p>
										</div>
									</div>
									<div class="media mb-4">
										<div class="image-bx me-sm-4 me-2">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle img-1">
											<span class="active"></span>
										</div>
										<div class="media-body">
											<div class="d-flex mb-sm-2 mb-0">
												<h6 class="text-black font-w600 fs-16 mb-0">Keanu Tipes</h6>
												<span class="ms-auto fs-14">41m ago</span>
											</div>
											<p class="text-black">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut</p>
										</div>
									</div>
									<div class="media mb-4">
										<div class="image-bx me-sm-4 me-2">
											<img src="{{ asset('images/users/6.png')}}" alt="" class="rounded-circle img-1">
											<span class="active"></span>
										</div>
										<div class="media-body">
											<div class="d-flex mb-2">
												<h6 class="text-black font-w600 fs-16 mb-0">Olivia Rellaq</h6>
												<span class="ms-auto fs-14">25m ago</span>
											</div>
											<p class="text-black">Nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
										</div>
									</div>
									<div class="media">
										<div class="image-bx me-sm-4 me-2">
											<img src="{{ asset('images/users/7.png')}}" alt="" class="rounded-circle img-1">
											<span class="active"></span>
										</div>
										<div class="media-body">
											<div class="d-flex mb-sm-2 mb-0">
												<h6 class="text-black font-w600 fs-16 mb-0">Olivia Rellaq</h6>
												<span class="ms-auto fs-14">25m ago</span>
											</div>
											<p class="text-black">Nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer pt-0 border-0">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-7">
			<div class="row">
				<div class="col-xl-12">
					<div class="card  message-bx chat-box">
						<div class="card-header border-0 shadow-sm">
							<div class="d-flex me-auto align-items-center">
								<div class="image-bx me-sm-4 me-2">
									<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle img-1">
									<span class="active"></span>
								</div>
								<div>
									<h5 class="text-black font-w600 mb-sm-1 mb-0 title-nm">Roberto Charloz</h5>
									<span>Last seen 4:23 AM</span>
								</div>
							</div>
						</div>
						<div class="card-body dlab-scroll height640" id="chartBox">
							<div class="media mb-4 justify-content-end align-items-end">
								<div class="message-sent">
									<p class="mb-1">
										sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet
									</p>
									<span class="fs-12">9.30 AM</span>
								</div>
								<div class="image-bx ms-sm-4 ms-2 mb-4">
									<img src="{{ asset('images/users/9.png')}}" alt="" class="rounded-circle img-1">
									<span class="active"></span>
								</div>
							</div>
							<div class="media mb-4  justify-content-end align-items-end">
								<div class="message-sent">
									<p class="mb-1">
										nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea
									</p>
									<span class="fs-12">9.30 AM</span>
								</div>
								<div class="image-bx ms-sm-4 ms-2 mb-4">	
									<img src="{{ asset('images/users/9.png')}}" alt="" class="rounded-circle img-1">
									<span class="active"></span>
								</div>
							</div>
							<div class="media mb-4  justify-content-start align-items-start">
								<div class="image-bx me-sm-4 me-2">
									<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle img-1">
									<span class="active"></span>
								</div>
								<div class="message-received">
									<p class="mb-1">
										Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptat
									</p>
									<span class="fs-12">4.30 AM</span>
								</div>
							</div>
							<div class="media justify-content-start align-items-start">
								<div class="image-bx me-sm-4 me-2">
									<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle img-1">
									<span class="active"></span>
								</div>
								<div class="message-received">
									<p class="mb-1">
										Hey, check my design update last night. Tell me what you think and if that is OK. I hear client said they want to change the layout concept
									</p>
									<span class="fs-12">4.30 AM</span>
								</div>
							</div>
						</div>
						<div class="card-footer border-0 type-massage">
							<div class="input-group">
								<textarea class="form-control" placeholder="Type message..."></textarea>
								<div class="input-group-append">
									<button type="button" class="btn shadow-none pe-0"><i class="las la-paperclip scale5"></i></button>
									<button type="button" class="btn shadow-none"><i class="las la-image scale5"></i></button>
									<button type="button" class="btn btn-primary light rounded">SEND</button>
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