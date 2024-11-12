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
	<div class="d-lg-flex d-block mb-3 pb-3 border-bottom">
		<div class="card-action card-tabs mb-lg-0 mb-3  me-auto">
			<ul class="nav nav-tabs tabs-lg" id="project" role="tablist">
				<li class="nav-item" role="presentation">
					<a href="#navpills-1" class="nav-link active"  data-bs-toggle="tab" aria-expanded="false"><span class="badge light badge-primary">27</span>All Projects</a>
				</li>
				<li class="nav-item" role="presentation">
					<a href="#navpills-2" class="nav-link"  data-bs-toggle="tab" aria-expanded="false"><span class="badge light badge-primary">2</span>On Progress</a>
				</li>
				<li class="nav-item" role="presentation">
					<a href="#navpills-3" class="nav-link"  data-bs-toggle="tab" aria-expanded="true"><span class="badge light badge-primary">4</span>Pending</a>
				</li>
				<li class="nav-item" role="presentation">
					<a href="#navpills-4" class="nav-link"  data-bs-toggle="tab" aria-expanded="true"><span class="badge light badge-primary">12</span>Closed</a>
				</li>
			</ul>
		</div>
		<div>
			<a href="{{ url('calendar')}}" class="btn btn-primary rounded">New Project</a>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="tab-content">
				<div class="tab-pane fade show active" id="navpills-1" role="tabpanel">
					<div class="table-responsive card-table rounded table-hover fs-14">
						<table class="table border-no display mb-4 dataTablesCard project-bx" id="example5">
							<thead>
								<tr>
									<th>
										Order ID
									</th>
									<th>
										Deadline
									</th>
									<th>
										Client
									</th>
									<th>
										Customers
									</th>
									<th>
										Action
									</th>
									<th>
										
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Build Branding Persona for Etza.id</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Monday,  Sep 26th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/6.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Kevin Sigh</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/7.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Yuri Hanako</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-info d-block rounded">PROGRESS</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Redesign Kripton Mobile App</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday,  Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/8.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Alex Noer</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/9.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Yoast Esec</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-warning d-block rounded">PENDING</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Redesign Kripton Mobile App</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/10.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/11.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Manage SEO for Eclan Company P..</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/12.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/13.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-info d-block rounded">PROGRESS</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/13.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/14.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-warning d-block rounded">PENDING</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn d-block btn-danger rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-warning d-block rounded">PENDING</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-warning d-block rounded">PENDING</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn d-block btn-danger rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-info d-block rounded">PROGRESS</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="tab-pane fade" id="navpills-2" role="tabpanel">
					<div class="table-responsive  card-table rounded table-hover fs-14">
						<table class="table border-no display mb-4 dataTablesCard project-bx" id="example6">
							<thead>
								<tr>
									<th>
										Order ID
									</th>
									<th>
										Deadline
									</th>
									<th>
										Client
									</th>
									<th>
										Customers
									</th>
									<th>
										Action
									</th>
									<th>
										
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-info d-block rounded">PROGRESS</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Build Branding Persona for Etza.id</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Monday,  Sep 26th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/6.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Kevin Sigh</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/7.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Yuri Hanako</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-info d-block rounded">PROGRESS</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="tab-pane fade" id="navpills-3" role="tabpanel">
					<div class="table-responsive  card-table rounded table-hover fs-14">
						<table class="table border-no display mb-4 dataTablesCard project-bx" id="example7">
							<thead>
								<tr>
									<th>
										Order ID
									</th>
									<th>
										Deadline
									</th>
									<th>
										Client
									</th>
									<th>
										Customers
									</th>
									<th>
										Action
									</th>
									<th>
										
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-info d-block rounded">PROGRESS</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Build Branding Persona for Etza.id</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Monday,  Sep 26th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/6.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Kevin Sigh</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/7.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Yuri Hanako</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-warning d-block rounded">PENDING</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-warning d-block rounded">PENDING</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Build Branding Persona for Etza.id</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Monday,  Sep 26th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/6.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Kevin Sigh</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/7.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Yuri Hanako</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-warning d-block rounded">PENDING</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="tab-pane fade" id="navpills-4" role="tabpanel">
					<div class="table-responsive  card-table rounded table-hover fs-14">
						<table class="table border-no display mb-4 dataTablesCard project-bx" id="example8">
							<thead>
								<tr>
									<th>
										Order ID
									</th>
									<th>
										Deadline
									</th>
									<th>
										Client
									</th>
									<th>
										Customers
									</th>
									<th>
										Action
									</th>
									<th>
										
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Build Branding Persona for Etza.id</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Monday,  Sep 26th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/6.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Kevin Sigh</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/7.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Yuri Hanako</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Build Branding Persona for Etza.id</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Monday,  Sep 26th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/6.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Kevin Sigh</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/7.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Yuri Hanako</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Build Branding Persona for Etza.id</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Monday,  Sep 26th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/6.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Kevin Sigh</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/7.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Yuri Hanako</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Build Branding Persona for Etza.id</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Monday,  Sep 26th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/6.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Kevin Sigh</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/7.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Yuri Hanako</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Build Branding Persona for Etza.id</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Monday,  Sep 26th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/6.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Kevin Sigh</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/7.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Yuri Hanako</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Reduce Website Page Size Omah</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Tuesday, Sep 29th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/4.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Endge Aes</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/5.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Peter Parkur</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div>
											<p class="text-primary mb-sm-2 mb-0">#P-000441425</p>
											<h4 class="title font-w600 mb-2"><a href="{{ url('post-details')}}" class="text-black">Build Branding Persona for Etza.id</a></h4>
											<div class="text-dark  text-nowrap"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>Created on Sep 8th, 2020</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<span class="bolt-icon me-sm-3 me-2"><i class="fa fa-bolt" aria-hidden="true"></i></span>
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Deadline</p>
												<span class="text-black font-w600 text-nowrap">Monday,  Sep 26th 2020</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/6.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark">Client</p>
												<span class="text-black font-w600 text-nowrap">Kevin Sigh</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex align-items-center">
											<img src="{{ asset('images/users/7.png')}}" alt="" class="rounded-circle me-sm-3 me-2 img-2">
											<div>
												<p class="mb-sm-1 mb-0 text-dark text-nowrap">Person in charge</p>
												<span class="text-black font-w600">Yuri Hanako</span>
											</div>
										</div>
									</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-danger d-block rounded">CLOSED</a>
									</td>
									<td>
										<div class="dropdown">
											<a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
													<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="javascript:void(0);">Edit</a>
												<a class="dropdown-item" href="javascript:void(0);">Delete</a>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
	(function($) {
	 
		var table = $('#example5').DataTable({
			searching: false,
			paging:true,
			select: false,
			//info: false,         
			lengthChange:false 
			
		});
		var table = $('#example6').DataTable({
			searching: false,
			paging:true,
			select: false,
			//info: false,         
			lengthChange:false 
			
		});
		var table = $('#example7').DataTable({
			searching: false,
			paging:true,
			select: false,
			//info: false,         
			lengthChange:false 
			
		});
		var table = $('#example8').DataTable({
			searching: false,
			paging:true,
			select: false,
			//info: false,         
			lengthChange:false 
			
		});
		$('#example tbody').on('click', 'tr', function () {
			var data = table.row( this ).data();
			
		});
	   
	})(jQuery);
</script>
@endpush