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
			<li class="breadcrumb-item"><a href="javascript:void(0)">Проекты</a></li>
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Дома из Бруса</a></li>
		</ol>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-xl-3 col-lg-6  col-md-6 col-xxl-5 ">
							<!-- Tab panes -->
							<div class="tab-content ">
								<div role="tabpanel" class="tab-pane fade active show " id="first">
									<img class="img-fluid b-radius" src="{{ asset('http://delta.borodin.services/storage/6/conversions/photo_2023-07-01-17.22.38-mild.jpg')}}" alt="">
								</div>
								<div role="tabpanel" class="tab-pane fade" id="second">
									<img class="img-fluid b-radius" src="{{ asset('http://delta.borodin.services/storage/6/conversions/photo_2023-07-01-17.22.38-mild.jpg')}}" alt="">
								</div>
								<div role="tabpanel" class="tab-pane fade" id="third">
									<img class="img-fluid b-radius" src="{{ asset('http://delta.borodin.services/storage/6/conversions/photo_2023-07-01-17.22.38-mild.jpg')}}" alt="">
								</div>
								<div role="tabpanel" class="tab-pane fade" id="for">
									<img class="img-fluid b-radius" src="{{ asset('http://delta.borodin.services/storage/6/conversions/photo_2023-07-01-17.22.38-mild.jpg')}}" alt="">
								</div>
							</div>
							<div class="tab-slide-content new-arrival-product mb-4 mb-xl-0">
								<!-- Nav tabs -->
								<ul class="nav slide-item-list mt-3" role="tablist">
									<li role="presentation" class="show">
										<a href="#first" role="tab" data-bs-toggle="tab">
											<img class="img-fluid " src="{{ asset('images/tab/1.jpg')}}" alt="" width="50">
										</a>
									</li>
									<li role="presentation">
										<a href="#second" role="tab" data-bs-toggle="tab"><img class="img-fluid" src="{{ asset('images/tab/2.jpg')}}" alt="" width="50"></a>
									</li>
									<li role="presentation">
										<a href="#third" role="tab" data-bs-toggle="tab"><img class="img-fluid" src="{{ asset('images/tab/3.jpg')}}" alt="" width="50"></a>
									</li>
									<li role="presentation">
										<a href="#for" role="tab" data-bs-toggle="tab"><img class="img-fluid" src="{{ asset('images/tab/4.jpg')}}" alt="" width="50"></a>
									</li>
								</ul>
							</div>
						</div>
						<!--Tab slider End-->
						<div class="col-xl-9 col-lg-6  col-md-6 col-xxl-7 col-sm-12">
							<div class="product-detail-content">
								<!--Product details-->
								<div class="new-arrival-content pr">
									<h4>Дом из Бруса (225)
									</h4>
									<div class="star-rating mb-2">
										<ul class="produtct-detail-tag">
											<li><i class="fa fa-star"></i></li>
											<li><i class="fa fa-star"></i></li>
											<li><i class="fa fa-star"></i></li>
											<li><i class="fa fa-star"></i></li>
											<li><i class="fa fa-star"></i></li>
										</ul>
										<span class="review-text">(34 reviews) / </span><a class="product-review" href=""  data-bs-toggle="modal" data-bs-target="#reviewModal">Write a review?</a>
									</div>
									<div class="d-block clearfix mb-2">
										<p class="price float-start">1 050 400 руб.</p>
									</div>
									<p>Product code: <span class="item">0405689</span> </p>
									<p>Brand: <span class="item">Lee</span></p>
									<p>Product tags:&nbsp;&nbsp;
										<span class="badge badge-success light">bags</span>
										<span class="badge badge-success light">clothes</span>
										<span class="badge badge-success light">shoes</span>
										<span class="badge badge-success light">dresses</span>
									</p>
									<p class="text-content">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.
										If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing.</p>
									<div class="filtaring-area my-3">
										<div class="size-filter">
											<h4 class="m-b-15">Select size</h4>
											
											<div class="btn-group" data-bs-toggle="buttons">
												<label class="btn btn-outline-primary light btn-sm"><input type="radio" class="position-absolute invisible" name="options" id="option5"> XS</label>
												<label class="btn btn-outline-primary light btn-sm"><input type="radio" class="position-absolute invisible" name="options" id="option1" checked>SM</label>
												<label class="btn btn-outline-primary light btn-sm"><input type="radio" class="position-absolute invisible" name="options" id="option2"> MD</label>
												<label class="btn btn-outline-primary light btn-sm"><input type="radio" class="position-absolute invisible" name="options" id="option3"> LG</label>
												<label class="btn btn-outline-primary light btn-sm"><input type="radio" class="position-absolute invisible" name="options" id="option4"> XL</label>
											</div>
										</div>
									</div>
									<!--Quantity start-->
									<div class="col-2 px-0">
										<input type="number" name="num" class="form-control input-btn input-number" value="1">
									</div>
									<!--Quanatity End-->
									<div class="shopping-cart mt-3">
										<a class="btn btn-primary btn-lg" href="javascript:void(0)"><i
												class="fa fa-shopping-basket me-2"></i>Add
											to cart</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- review -->
		<div class="modal fade" id="reviewModal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Review</h5>
						<button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form>
							@csrf
							<div class="text-center mb-4">
								<img class="img-fluid rounded" width="78" src="{{ asset('http://delta.borodin.services/storage/6/conversions/photo_2023-07-01-17.22.38-mild.jpg')}}" alt="DexignLab">
							</div>
							<div class="form-group">
								<div class="rating-widget mb-4 text-center">
									<!-- Rating Stars Box -->
									<div class="rating-stars">
										<ul id="stars">
											<li class="star" title="Poor" data-value="1">
												<i class="fa fa-star fa-fw"></i>
											</li>
											<li class="star" title="Fair" data-value="2">
												<i class="fa fa-star fa-fw"></i>
											</li>
											<li class="star" title="Good" data-value="3">
												<i class="fa fa-star fa-fw"></i>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="form-group">
								<textarea class="form-control" placeholder="Comment" rows="5"></textarea>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection