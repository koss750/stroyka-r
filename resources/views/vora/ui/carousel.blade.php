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
			<li class="breadcrumb-item"><a href="javascript:void(0)">Bootstrap</a></li>
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Carousel</a></li>
		</ol>
	</div>
	<!-- row -->
	<div class="row">
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body p-4">
				<h4 class="card-intro-title">Slides only</h4>
					<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
						<ol class="carousel-indicators">
							<li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active">
							</li>
							<li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" class=""></li>
							<li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" class=""></li>
						</ol>
						<div class="carousel-inner">
							<div class="carousel-item active">
								<img class="d-block w-100" src="{{ asset('images/big/img1.jpg')}}" alt="First slide">
							</div>
							<div class="carousel-item">
								<img class="d-block w-100" src="{{ asset('images/big/img2.jpg')}}" alt="Second slide">
							</div>
							<div class="carousel-item">
								<img class="d-block w-100" src="{{ asset('images/big/img3.jpg')}}" alt="Third slide">
							</div>
						</div><a class="carousel-control-prev" href="#carouselExampleIndicators" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span> <span
								class="sr-only">Previous</span> </a><a class="carousel-control-next" href="#carouselExampleIndicators" data-bs-slide="next"><span
								class="carousel-control-next-icon"></span>
							<span class="sr-only">Next</span></a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body p-4">
				<h4 class="card-intro-title">With Captions</h4>
					<div class="bootstrap-carousel">
						<div class="carousel slide" data-bs-ride="carousel">
							<div class="carousel-inner">
								<div class="carousel-item active">
									<img class="d-block w-100" src="{{ asset('images/big/img2.jpg')}}" alt="First slide">
									<div class="carousel-caption d-none d-md-block">
										<h5>First slide label</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img class="d-block w-100" src="{{ asset('images/big/img3.jpg')}}" alt="Second slide">
									<div class="carousel-caption d-none d-md-block">
										<h5>Second slide label</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img class="d-block w-100" src="{{ asset('images/big/img4.jpg')}}" alt="Third slide">
									<div class="carousel-caption d-none d-md-block">
										<h5>Third slide label</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body p-4">
					<h4 class="card-intro-title">Slides only</h4>
					<div class="bootstrap-carousel">
						<div class="carousel slide" data-bs-ride="carousel">
							<div class="carousel-inner">
								<div class="carousel-item active">
									<img class="d-block w-100" src="{{ asset('images/big/img3.jpg')}}" alt="First slide">
								</div>
								<div class="carousel-item">
									<img class="d-block w-100" src="{{ asset('images/big/img4.jpg')}}" alt="Second slide">
								</div>
								<div class="carousel-item">
									<img class="d-block w-100" src="{{ asset('images/big/img5.jpg')}}" alt="Third slide">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body p-4">
					<h4 class="card-intro-title mb-4">Slides With indicators</h4>
					<div class="bootstrap-carousel">
						<div id="carouselExampleIndicators2" class="carousel slide" data-bs-ride="carousel">
							<ol class="carousel-indicators">
								<li data-bs-target="#carouselExampleIndicators2" data-bs-slide-to="0" class="active"></li>
								<li data-bs-target="#carouselExampleIndicators2" data-bs-slide-to="1"></li>
								<li data-bs-target="#carouselExampleIndicators2" data-bs-slide-to="2"></li>
							</ol>
							<div class="carousel-inner">
								<div class="carousel-item active">
									<img class="d-block w-100" src="{{ asset('images/big/img4.jpg')}}" alt="First slide">
								</div>
								<div class="carousel-item">
									<img class="d-block w-100" src="{{ asset('images/big/img5.jpg')}}" alt="Second slide">
								</div>
								<div class="carousel-item">
									<img class="d-block w-100" src="{{ asset('images/big/img6.jpg')}}" alt="Third slide">
								</div>
							</div><a class="carousel-control-prev" href="#carouselExampleIndicators2" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span> <span
									class="sr-only">Previous</span> </a><a class="carousel-control-next" href="#carouselExampleIndicators2" data-bs-slide="next"><span
									class="carousel-control-next-icon"></span>
								<span class="sr-only">Next</span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body p-4">
					<h4 class="card-intro-title mb-4">Slides With captions</h4>
					<div class="bootstrap-carousel">
						<div data-bs-ride="carousel" class="carousel slide" id="carouselExampleCaptions">
							<ol class="carousel-indicators">
								<li class="active" data-bs-slide-to="0" data-bs-target="#carouselExampleCaptions">
								</li>
								<li data-bs-slide-to="1" data-bs-target="#carouselExampleCaptions" class=""></li>
								<li data-bs-slide-to="2" data-bs-target="#carouselExampleCaptions" class=""></li>
							</ol>
							<div class="carousel-inner">
								<div class="carousel-item active">
									<img class="d-block w-100" src="{{ asset('images/big/img5.jpg')}}" alt="">
									<div class="carousel-caption d-none d-md-block">
										<h5>First slide label</h5>
										<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img alt="" class="d-block w-100" src="{{ asset('images/big/img6.jpg')}}">
									<div class="carousel-caption d-none d-md-block">
										<h5>Second slide label</h5>
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
									</div>
								</div>
								<div class="carousel-item">
									<img alt="" class="d-block w-100" src="{{ asset('images/big/img7.jpg')}}">
									<div class="carousel-caption d-none d-md-block">
										<h5>Third slide label</h5>
										<p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.
										</p>
									</div>
								</div>
							</div><a data-bs-slide="prev" href="#carouselExampleCaptions" class="carousel-control-prev"><span
									class="carousel-control-prev-icon"></span> <span
									class="sr-only">Previous</span>
							</a><a data-bs-slide="next" href="#carouselExampleCaptions" class="carousel-control-next"><span
									class="carousel-control-next-icon"></span> <span
									class="sr-only">Next</span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
@endsection