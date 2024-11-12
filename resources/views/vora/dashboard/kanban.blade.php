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
		<div class="col-xl-12">
			<div class="card">
				<div class="card-body">
					<div class="d-md-flex d-block mb-md-4 mb-3 align-items-end">
						<div class="me-auto pe-3 mb-md-0 mb-3">
							<h2 class="title-num text-black font-w600">Base Vora’s Project v2.4</h2>
							<span class="fs-14">Created by Lidya Chan on June 31, 2020</span>
						</div>
						<ul class="users-lg">
							<li><img src="{{ asset('images/users/14.png')}}" alt=""></li>
							<li><img src="{{ asset('images/users/15.png')}}" alt=""></li>
							<li><img src="{{ asset('images/users/16.png')}}" alt=""></li>
							<li><img src="{{ asset('images/users/17.png')}}" alt=""></li>
							<li><img src="{{ asset('images/users/18.png')}}" alt=""></li>
							<li><img src="{{ asset('images/users/19.png')}}" alt=""></li>
							<li><img src="{{ asset('images/users/20.png')}}" alt=""></li>
						</ul>
					</div>
					<div class="row">
						<p class="fs-14 me-auto col-lg-6">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
						<div class="col-lg-6 text-lg-end text-start">
							<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addOrderModal" class="btn btn-primary rounded me-3 mb-sm-0 mb-2"><i class="fa fa-user me-3 scale5" aria-hidden="true"></i>New Contact</a>
							<a href="javascript:void(0)" class="btn btn-light rounded me-3 mb-sm-0 mb-2"><i class="fa fa-pencil-square me-3 scale5" aria-hidden="true"></i>Edit</a>
							<a href="javascript:void(0)" class="btn btn-light rounded mb-sm-0 mb-2"><i class="fa fa-lock me-3 scale5" aria-hidden="true"></i>Private</a>
						</div>
						<!-- Add Order -->
						<div class="modal fade" id="addOrderModal">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Add Contact</h5>
										<button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<form>
											@csrf
											<div class="form-group">
												<label class="text-black font-w500">First Name</label>
												<input type="text" class="form-control">
											</div>
											<div class="form-group">
												<label class="text-black font-w500">Last Name</label>
												<input type="text" class="form-control">
											</div>
											<div class="form-group">
												<label class="text-black font-w500">Address</label>
												<input type="text" class="form-control">
											</div>
											<div class="form-group">
												<button type="button" class="btn btn-primary">SAVE</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row kanban-bx">
		<div class="col">
			<div class="card dropzoneContainer">
				<span class="line bg-secondary"></span>
				<div class="card-header shadow-sm">
					<div>
						<h4 class="fs-20 mb-0 font-w600 text-black">To-Do List (24)</h4>
						<span class="fs-14">Lorem ipsum dolor sit amet</span>
					</div>
					<a href="{{ url('contacts')}}" class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
				</div>
				<div class="card-body draggable-zone  loadmore-content dlab-scroll  pb-0" id="RecentActivitiesContent">
					<div class="border-bottom pb-4 mb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-success rounded-xl mb-2">Graphic Deisgner</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Visual Graphic for Presentation to Client</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/1.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/2.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/3.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/4.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">24%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar progress-animated" style="width: 24%; height:7px;" role="progressbar">
										<span class="sr-only">24% Complete</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="border-bottom pb-4 mb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-secondary rounded-xl mb-2">Digital Marketing</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Build Database Design for Fasto Admin v2</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/5.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/6.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/7.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/8.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">79%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar progress-animated" style="width: 79%; height:7px;" role="progressbar">
										<span class="sr-only">79% Complete</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="border-bottom pb-4 mb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-warning rounded-xl mb-2">Programmer</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Make Promotional Ads for Instagram Fasto’s</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/9.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/10.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/11.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/12.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">36%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar progress-animated" style="width: 36%; height:7px;" role="progressbar">
										<span class="sr-only">36% Complete</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="pb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-secondary rounded-xl mb-2">Digital Marketing</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Build Database Design for Fasto Admin v2</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/5.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/6.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/7.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/8.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">79%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar progress-animated" style="width: 79%; height:7px;" role="progressbar">
										<span class="sr-only">79% Complete</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<a class="btn d-block btn-primary light dlab-load-more" href="javascript:void(0)"  rel="{{ url('ajax/recent-activities')}}" id="RecentActivities"><strong>26</strong> Tasks More</a>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card dropzoneContainer">
				<span class="line bg-warning"></span>
				<div class="card-header shadow-sm">
					<div>
						<h4 class="fs-20 mb-0 font-w600 text-black">OnProgress (2)</h4>
						<span class="fs-14">Lorem ipsum dolor sit amet</span>
					</div>
					<a href="{{ url('contacts')}}" class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
				</div>
				<div class="card-body draggable-zone  pb-0">
					<div class="border-bottom pb-4 mb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-info rounded-xl mb-2">UX Writer</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Caption description for onboarding screens</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/1.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/2.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/3.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/4.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">24%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar progress-animated" style="width: 24%; height:7px;" role="progressbar">
										<span class="sr-only">24% Complete</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="pb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-secondary rounded-xl mb-2">Digital Marketing</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Branding New Visual Vora</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/5.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/6.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">79%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar progress-animated" style="width: 79%; height:7px;" role="progressbar">
										<span class="sr-only">79% Complete</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col ">
			<div class="card dropzoneContainer">
				<span class="line bg-success"></span>
				<div class="card-header shadow-sm">
					<div>
						<h4 class="fs-20 mb-0 font-w600 text-black">Done (3)</h4>
						<span class="fs-14">Lorem ipsum dolor sit amet</span>
					</div>
					<a href="{{ url('contacts')}}" class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
				</div>
				<div class="card-body draggable-zone  pb-0">
					<div class="border-bottom pb-4 mb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-warning rounded-xl mb-2">Sales Marketing</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Get 1,000 New Leads for Next Month Target</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/9.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">36%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar  progress-animated" style="width: 36%; height:7px;" role="progressbar">
										<span class="sr-only">36% Complete</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="border-bottom pb-4 mb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-success rounded-xl mb-2">Graphic Deisgner</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Visual Graphic for Presentation to Client</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/1.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/2.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/3.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/4.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">24%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar progress-animated" style="width: 24%; height:7px;" role="progressbar">
										<span class="sr-only">24% Complete</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="pb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-warning rounded-xl mb-2">Programmer</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Make Promotional Ads for Instagram Fasto’s</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/5.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/6.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">79%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar progress-animated" style="width: 79%; height:7px;" role="progressbar">
										<span class="sr-only">79% Complete</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card dropzoneContainer">
				<span class="line bg-danger"></span>
				<div class="card-header shadow-sm">
					<div>
						<h4 class="fs-20 mb-0 font-w600 text-black">Revised (0)</h4>
						<span class="fs-14">Lorem ipsum dolor sit amet</span>
					</div>
					<a href="{{ url('contacts')}}" class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
				</div>
				<div class="card-body draggable-zone  pb-0">
					<div class="pb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-secondary rounded-xl mb-2">Digital Marketing</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Build Database Design for Fasto Admin v2</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/5.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/6.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/7.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/8.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">79%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar progress-animated" style="width: 79%; height:7px;" role="progressbar">
										<span class="sr-only">79% Complete</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card dropzoneContainer">
				<span class="line bg-dark"></span>
				<div class="card-header shadow-sm">
					<div>
						<h4 class="fs-20 mb-0 font-w600 text-black">To-Do List (24)</h4>
						<span class="fs-14">Lorem ipsum dolor sit amet</span>
					</div>
					<a href="{{ url('contacts')}}" class="plus-icon"><i class="fa fa-plus" aria-hidden="true"></i></a>
				</div>
				<div class="card-body draggable-zone  pb-0">
					<div class="border-bottom pb-4 mb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-success rounded-xl mb-2">Graphic Deisgner</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Visual Graphic for Presentation to Client</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/1.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/2.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/3.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/4.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">24%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar progress-animated" style="width: 24%; height:7px;" role="progressbar">
										<span class="sr-only">24% Complete</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="border-bottom pb-4 mb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-secondary rounded-xl mb-2">Digital Marketing</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Build Database Design for Fasto Admin v2</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/5.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/6.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/7.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/8.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">79%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar progress-animated" style="width: 79%; height:7px;" role="progressbar">
										<span class="sr-only">79% Complete</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="border-bottom pb-4 mb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-warning rounded-xl mb-2">Programmer</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Make Promotional Ads for Instagram Fasto’s</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/9.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/10.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/11.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/12.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">36%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar progress-animated" style="width: 36%; height:7px;" role="progressbar">
										<span class="sr-only">36% Complete</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="pb-4 draggable-handle draggable">
						<a href="javascript:void(0)" class="btn btn-sm btn-secondary rounded-xl mb-2">Digital Marketing</a>
						<p class="font-w600"><a href="{{ url('post-details')}}" class="text-black">Build Database Design for Fasto Admin v2</a></p>
						<div class="row justify-content-between">
							<ul class="users col-6">
								<li><img src="{{ asset('images/users/5.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/6.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/7.jpg')}}" alt=""></li>
								<li><img src="{{ asset('images/users/8.jpg')}}" alt=""></li>
							</ul>
							<div class="col-6 ps-0">
								<h6 class="fs-14">Progress
									<span class="pull-right font-w600">79%</span>
								</h6>
								<div class="progress" style="height:7px;">
									<div class="progress-bar progress-animated" style="width: 79%; height:7px;" role="progressbar">
										<span class="sr-only">79% Complete</span>
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