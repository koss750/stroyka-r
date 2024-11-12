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
	
	<div class="page-titles">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="javascript:void(0)">Layout</a></li>
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Blank</a></li>
		</ol>
	</div>
	<div class="row">
		@foreach ($designs as $design)
    <div class="col-lg-12 col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="row m-b-30">
                    <div class="col-md-5 col-xxl-12">
                        <div class="new-arrival-product mb-4 mb-xxl-4 mb-md-0">
                            <div class="new-arrivals-img-contnent">
                                <img class="img-fluid" src="{{ asset($design->image_url)}}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 col-xxl-12">
                        <div class="new-arrival-content position-relative">
                            <h4><a href="{{ url('ecom-product-detail', $design->id)}}">{{ $design->title }}</a></h4>
                            <div class="comment-review star-rating">
                                <span class="review-text">({{ $design->reviewCount }} reviews) / </span>
                                <a class="product-review" href="" data-bs-toggle="modal" data-bs-target="#reviewModal">Write a review?</a>
                            </div>
                            <p>Product code: <span class="item">{{ $design->id }}</span> </p>
                            <p>Material: <span class="item">{{ $design->materialType }}</span></p>
                            <p class="text-content">{{ $design->details }}</p>
                            <p class="price">от {{ $design->price }}₽</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
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
								<img class="img-fluid rounded" width="78" src="{{ asset('images/avatar/1.jpg')}}" alt="DexignLab">
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
											<li class="star" title="Excellent" data-value="4">
												<i class="fa fa-star fa-fw"></i>
											</li>
											<li class="star" title="WOW!!!" data-value="5">
												<i class="fa fa-star fa-fw"></i>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="form-group">
								<textarea class="form-control" placeholder="Comment" rows="5"></textarea>
							</div>
							<button class="btn btn-success btn-block">RATE</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection