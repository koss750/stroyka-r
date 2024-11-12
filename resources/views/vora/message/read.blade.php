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
			<li class="breadcrumb-item"><a href="javascript:void(0)">Email</a></li>
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Read</a></li>
		</ol>
	</div>
	<!-- row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<div class="email-left-box generic-width px-0 mb-5">
						<div class="p-0">
							<a href="{{ url('email-compose')}}" class="btn btn-primary d-block">Compose</a>
						</div>
						<div class="mail-list mt-4">
							<a href="{{ url('email-inbox')}}" class="list-group-item active"><i
									class="fa fa-inbox font-18 align-middle me-2"></i> Inbox <span
									class="badge badge-primary badge-sm float-end">198</span> </a>
							<a href="javascript:void(0);" class="list-group-item"><i
									class="fa fa-paper-plane font-18 align-middle me-2"></i>Sent</a> <a href="javascript:void(0);" class="list-group-item"><i
									class="fa fa-star font-18 align-middle me-2"></i>Important <span
									class="badge badge-danger badge-sm text-white float-end">47</span>
							</a>
							<a href="javascript:void(0);" class="list-group-item"><i
									class="mdi mdi-file-document-box font-18 align-middle me-2"></i>Draft</a><a href="javascript:void(0);" class="list-group-item"><i
									class="fa fa-trash font-18 align-middle me-2"></i>Trash</a>
						</div>
						<div class="intro-title d-flex justify-content-between">
							<h5>Categories</h5>
							<i class="fa fa-chevron-down" aria-hidden="true"></i>
						</div>
						<div class="mail-list mt-4">
							<a href="{{ url('email-inbox')}}" class="list-group-item"><span class="icon-warning"><i
										class="fa fa-circle" aria-hidden="true"></i></span>
								Work </a>
							<a href="{{ url('email-inbox')}}" class="list-group-item"><span class="icon-primary"><i
										class="fa fa-circle" aria-hidden="true"></i></span>
								Private </a>
							<a href="{{ url('email-inbox')}}" class="list-group-item"><span class="icon-success"><i
										class="fa fa-circle" aria-hidden="true"></i></span>
								Support </a>
							<a href="{{ url('email-inbox')}}" class="list-group-item"><span class="icon-dpink"><i
										class="fa fa-circle" aria-hidden="true"></i></span>
								Social </a>
						</div>
					</div>
					<div class="email-right-box ms-0 ms-sm-4 ms-sm-0">
						<div class="row">
							<div class="col-12">
								<div class="right-box-padding">
								   
									<div class="read-content">
										<div class="media pt-3 d-sm-flex d-block justify-content-between">
											<div class="clearfix mb-3 d-flex">
												<img class="me-3 rounded" width="50" alt="image" src="{{ asset('images/avatar/1.jpg')}}">
												<div class="media-body me-2">
													<h5 class="text-primary mb-0 mt-1">Ingredia Nutrisha</h5>
													<p class="mb-0">20 May 2018</p>
												</div>
											</div>
											<div class="clearfix mb-3">
												<a href="javascript:void(0);" class="btn btn-primary px-3 light">Ответить </a>
												<a href="javascript:void(0);" class="btn btn-primary px-3 light ms-2"><i class="fa fa-trash"></i></a>
											</div>
										</div>
										<hr>
										<div class="media mb-2 mt-3">
											<div class="media-body"><span class="pull-right">07:23 AM</span>
												<h5 class="my-1 text-primary">A collection of textile samples lay spread</h5>
												<p class="read-content-email">
													To: Me, info@example.com</p>
											</div>
										</div>
										<div class="read-content-body">
											<h5 class="mb-4">Hi,Ingredia,</h5>
											<p class="mb-2"><strong>Ingredia Nutrisha,</strong> A collection of textile samples lay spread out on the table - Samsa was a travelling salesman - and above it there hung a picture</p>
											<p class="mb-2">Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for
												the far World of Grammar. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus.
											</p>
											<p class="mb-2">Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut
												metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum
												rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar,</p>
											<h5 class="pt-3">Kind Regards</h5>
											<p>Mr Smith</p>
											<hr>
										</div>
										<div class="read-content-attachment">
											<h6><i class="fa fa-download mb-2"></i> Attachments
												<span>(3)</span></h6>	
											<div class="row attachment">
												<div class="col-auto mb-2">
													<a href="javascript:void(0);" class="btn btn-outline-primary btn-xs">My-Photo.png</a>
												</div>
												<div class="col-auto mb-2">
													<a href="javascript:void(0);" class="btn btn-outline-primary btn-xs">My-File.docx</a>
												</div>
												<div class="col-auto mb-2">
													<a href="javascript:void(0);" class="btn btn-outline-primary btn-xs">My-Resume.pdf</a>
												</div>
											</div>
										</div>
										<hr>
										<div class="form-group pt-3">
											<textarea name="write-email" id="write-email" cols="30" rows="5" class="form-control" placeholder="It's really an amazing.I want to know more about it..!"></textarea>
										</div>
									</div>
									<div class="text-end">
										<button class="btn btn-primary " type="button">Send</button>
									</div>
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