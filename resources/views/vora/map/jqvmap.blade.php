@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <!-- Add Order -->
    <div class="modal fade" id="addOrderModalside">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Создать проект</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        @csrf
                        <div class="form-group">
                            <label class="text-black font-w500">Название проекта</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="text-black font-w500">Срок сдачи</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="text-black font-w500">Имя клиента</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary">СОЗДАТЬ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Карта</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">jqvmap</a></li>
        </ol>
    </div>
    <!-- row -->

    <!-- Vectormap -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Карта мира</h4>
                </div>
                <div class="card-body">
                    <div id="world-map"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">США</h4>
                </div>
                <div class="card-body">
                    <div id="usa" class="height400"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
