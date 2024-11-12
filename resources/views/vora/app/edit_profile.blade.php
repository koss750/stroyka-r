@extends('layouts.default')

@section('content')
<div class="container-fluid">
	<!-- row -->
	<div class="row">
		<div class="col-xl-3 col-lg-4">
			<div class="clearfix">
				<div class="card card-bx author-profile m-b30">
					<div class="card-body">
						<div class="p-5">
							<div class="author-profile">
								<div class="author-media">
									<img src="{{ asset('images/profile.jpg')}}" alt="">
									<div class="upload-link" title="" data-bs-toggle="tooltip" data-placement="right" data-original-title="update">
										<input type="file" class="update-flie">
										<i class="fa fa-camera"></i>
									</div>
								</div>
								<div class="author-info">
									<h6 class="title">John</h6>
									<span>Developer</span>
								</div>
							</div>
						</div>
						<div class="info-list">
							<ul>
								<li><a href="{{ url('app-profile')}}">Models</a><span>36</span></li>
								<li><a href="{{ url('app-profile')}}">Gallery</a><span>3</span></li>
								<li><a href="{{ url('app-profile')}}">Lessons</a><span>1</span></li>
							</ul>
						</div>
					</div>
					<div class="card-footer">
						<div class="input-group mb-3">
							<div class="form-control rounded text-center">Portfolio</div>
						</div>
						<div class="input-group">
							<a href="https://www.dexignlab.com/" class="form-control text-primary rounded text-start ">https://www.dexignlab.com/</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-9 col-lg-8">
			<div class="card  card-bx m-b30">
				<div class="card-header">
					<h6 class="title">Account setup</h6>
				</div>
				<form class="profile-form">
					@csrf
					<div class="card-body">
						<div class="row">
							<div class="col-sm-6 m-b30">
								<label class="form-label">Name</label>
								<input type="text" class="form-control" value="John">
							</div>
							<div class="col-sm-6 m-b30">
								<label class="form-label">Surname</label>
								<input type="text" class="form-control">
							</div>
							<div class="col-sm-6 m-b30">
								<label class="form-label">Specialty</label>
								<input type="text" class="form-control" value="Developer">
							</div>
							<div class="col-sm-6 m-b30">
								<label class="form-label">Skills</label>
								<input type="text" class="form-control" value="HTML,  JavaScript,  PHP">
							</div>
							<div class="col-sm-6 m-b30">
								<label class="form-label">Gender</label>
								<select class="selectpicker w-100 mh-auto form-control form-control-lg">
									<option>Male</option>
									<option>Female</option>
								</select>
							</div>
							<div class="col-sm-6 m-b30">
								<div class="example">
									<label class="form-label">Birth</label>
									<input class="form-control " type="text" name="daterange" placeholder="2017-06-04" id="mdate">
								</div>	
							</div>
							<div class="col-sm-6 m-b30">
								<label class="form-label">Phone</label>
								<input type="text" class="form-control" value="+123456789">
							</div>
							<div class="col-sm-6 m-b30">
								<label class="form-label">Email adress</label>
								<input type="text" class="form-control" value="demo@gmail.com">
							</div>
							<div class="col-sm-6 m-b30">
								<label class="form-label ">Country</label>
								<select class="selectpicker w-100 mh-auto form-control form-control-lg">
									<option>Russia</option>
									<option>Canada</option>
									<option>China</option>
									<option>India</option>
								</select>
							</div>
							<div class="col-sm-6 m-b30">
								<label class="form-label">City</label>
								<select class="selectpicker w-100 mh-auto form-control form-control-lg">
									<option>Krasnodar</option>
									<option>Tyumen</option>
									<option>Chelyabinsk</option>
									<option>Moscow</option>
								</select>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<button class="btn btn-primary">UPDATE</button>
						<a href="{{ url('page-register')}}" class="text-primary btn-link">Forgot your password?</a>
					</div>
				</form>
			</div>
		</div>
	</div>	
	<!--**********************************
		Footer start
	***********************************-->
	<div class="footer">
		<div class="copyright">
			<p>Copyright © Designed &amp; Developed by <a href="https://dexignlab.com/" target="_blank">DexignLab</a> 2022</p>
		</div>
	</div>
	<!--**********************************
		Footer end
	***********************************-->
</div>
@endsection