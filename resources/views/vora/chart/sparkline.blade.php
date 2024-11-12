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
                <li class="breadcrumb-item"><a href="javascript:void(0)">Charts</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Sparkline</a></li>
            </ol>
        </div>
        <!-- row -->

        <div class="row">
            <div class="col-xl-3 col-xxl-4 col-sm-6">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Line Chart</h4>
                    </div>
                    <div class="card-body">
                        <span id="sparklinedash"></span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-sm-6">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Site Traffic</h4>
                    </div>
                    <div class="card-body">
                        <div class="ico-sparkline">
                            <div id="sparkline8"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-sm-6">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Site Traffic</h4>
                    </div>
                    <div class="card-body">
                        <div class="ico-sparkline">
                            <div id="sparkline9"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-sm-6">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Bar Chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="ico-sparkline">
                            <div id="spark-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-sm-6">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Stacked Bar CHART</h4>
                    </div>
                    <div class="card-body">
                        <div class="ico-sparkline">
                            <div id="StackedBarChart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-sm-6">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Tristate charts</h4>
                    </div>
                    <div class="card-body">
                        <div class="ico-sparkline">
                            <div id="tristate"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-sm-6">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Composite Line Chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="ico-sparkline">
                            <div id="sparkline-composite-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-sm-6">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Composite Bar Chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="ico-sparkline">
                            <div id="composite-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-sm-6">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Bullet Chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="ico-sparkline">
                            <div id="bullet-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-sm-6">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Pie Chart</h4>
                    </div>
                    <div class="card-body">
                        <div class="ico-sparkline">
                            <div id="sparkline11"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-sm-6">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Box Plot</h4>
                    </div>
                    <div class="card-body">
                        <div class="ico-sparkline">
                            <div id="boxplot"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
