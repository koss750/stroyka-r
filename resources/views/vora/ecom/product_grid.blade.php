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
			<li class="breadcrumb-item"><a href="javascript:void(0)">Layout</a></li>
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Blank</a></li>
		</ol>
	</div>
	<div class="row">
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
			<div class="card">
				<div class="card-body">
					<div class="new-arrival-product">
						<div class="new-arrivals-img-contnent">
							<img class="img-fluid" src="{{ asset('images/product/1.jpg')}}" alt="">
						</div>
						<div class="new-arrival-content text-center mt-3">
							<h4><a href="{{ url('ecom-product-detail')}}">Bonorum et Malorum</a></h4>
							<ul class="star-rating">
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star-half-empty"></i></li>
								<li><i class="fa fa-star-half-empty"></i></li>
							</ul>
							<span class="price">$761.00</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
			<div class="card">
				<div class="card-body">
					<div class="new-arrival-product">
						<div class="new-arrivals-img-contnent">
							<img class="img-fluid" src="{{ asset('images/product/2.jpg')}}" alt="">
						</div>
						<div class="new-arrival-content text-center mt-3">
							<h4><a href="{{ url('ecom-product-detail')}}">Striped Dress</a></h4>
							<ul class="star-rating">
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
							</ul>
							<span class="price">$159.00</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
			<div class="card">
				<div class="card-body">
					<div class="new-arrival-product">
						<div class="new-arrivals-img-contnent">
							<img class="img-fluid" src="{{ asset('images/product/3.jpg')}}" alt="">
						</div>
						<div class="new-arrival-content text-center mt-3">
							<h4><a href="{{ url('ecom-product-detail')}}">BBow polka-dot</a></h4>
							<ul class="star-rating">
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
							</ul>
							<span class="price">$357.00</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
			<div class="card">
				<div class="card-body">
					<div class="new-arrival-product">
						<div class="new-arrivals-img-contnent">
							<img class="img-fluid" src="{{ asset('images/product/4.jpg')}}" alt="">
						</div>
						<div class="new-arrival-content text-center mt-3">
							<h4><a href="{{ url('ecom-product-detail')}}">Z Product 360</a></h4>
							<ul class="star-rating">
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star-half-empty"></i></li>
								<li><i class="fa fa-star-half-empty"></i></li>
							</ul>
							<span class="price">$654.00</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
			<div class="card">
				<div class="card-body">
					<div class="new-arrival-product">
						<div class="new-arrivals-img-contnent">
							<img class="img-fluid" src="{{ asset('images/product/5.jpg')}}" alt="">
						</div>
						<div class="new-arrival-content text-center mt-3">
							<h4><a href="{{ url('ecom-product-detail')}}">Chair Grey</a></h4>
							<ul class="star-rating">
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
							</ul>
							<span class="price">$369.00</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
			<div class="card">
				<div class="card-body">
					<div class="new-arrival-product">
						<div class="new-arrivals-img-contnent">
							<img class="img-fluid" src="{{ asset('images/product/6.jpg')}}" alt="">
						</div>
						<div class="new-arrival-content text-center mt-3">
							<h4><a href="{{ url('ecom-product-detail')}}">fox sake withe</a></h4>
							<ul class="star-rating">
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
							</ul>
							<span class="price">$245.00</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
			<div class="card">
				<div class="card-body">
					<div class="new-arrival-product">
						<div class="new-arrivals-img-contnent">
							<img class="img-fluid" src="{{ asset('images/product/7.jpg')}}" alt="">
						</div>
						<div class="new-arrival-content text-center mt-3">
							<h4><a href="{{ url('ecom-product-detail')}}">Back Bag</a></h4>
							<ul class="star-rating">
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
							</ul>
							<span class="price">$364.00</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
			<div class="card">
				<div class="card-body">
					<div class="new-arrival-product">
						<div class="new-arrivals-img-contnent">
							<img class="img-fluid" src="{{ asset('images/product/1.jpg')}}" alt="">
						</div>
						<div class="new-arrival-content text-center mt-3">
							<h4><a href="{{ url('ecom-product-detail')}}">FLARE DRESS</a></h4>
							<ul class="star-rating">
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star"></i></li>
								<li><i class="fa fa-star-half-empty"></i></li>
								<li><i class="fa fa-star-half-empty"></i></li>
							</ul>
							<span class="price">$548.00</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection