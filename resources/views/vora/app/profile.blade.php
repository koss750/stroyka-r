@extends('layouts.default')


@section('content')
<div class="container-fluid">
    <!-- Add Order -->
    <div class="modal fade" id="addOrderModalside">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Создать Проект</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        @csrf
                        <div class="form-group">
                            <label class="text-black font-w500">Название Проекта</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="text-black font-w500">Срок Сдачи</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="text-black font-w500">Имя Клиента</label>
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
            <li class="breadcrumb-item"><a href="javascript:void(0)">Контакты</a></li>
			<li class="breadcrumb-item"><a href="/contacts">Поставщики</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Borodin Services Ltd</a></li>
        </ol>
    </div>
    <!-- row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="profile card card-body px-3 pt-3 pb-0">
                <div class="profile-head">
                    <div class="photo-content">
                        <div class="cover-photo" style="background:url({{ asset('images/profile/bq.jpg')}})"></div>
                    </div>
                    <div class="profile-info">
                        <div class="profile-photo">
                            <img src="{{ asset('images/profile/profile')}}.png" class="img-fluid rounded-circle" alt="">
                        </div>
                        <div class="profile-details">
                            <div class="profile-name px-3 pt-2">
                                <h4 class="text-primary mb-0">Константин Б</h4>
                                <p>Поставщик</p>
                            </div>
                            <div class="profile-email px-2 pt-2">
                                <h4 class="text-muted mb-0">info@example.com</h4>
                                <p>Email</p>
                            </div>
                            <div class="dropdown ms-auto">
                                <a href="#" class="btn btn-primary light sharp"  data-bs-toggle="dropdown" aria-expanded="true"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg></a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-item"><i class="fa fa-user-circle text-primary me-2"></i> Посмотреть профиль</li>
                                    <li class="dropdown-item"><i class="fa fa-users text-primary me-2"></i> Написать сообщение</li>
                                    <li class="dropdown-item"><i class="fa fa-ban text-primary me-2"></i> Заблокировать</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="profile-statistics mb-5">
                        <div class="text-center">
                            <div class="row">
                                <div class="col">
                                    <h3 class="m-b-0">1856</h3><span>Основан</span>
                                </div>
                                <div class="col">
                                    <h3 class="m-b-0">4</h3><span>Заказов</span>
                                </div>
								<div class="col">
                                    <h3 class="m-b-0">4.5</h3><span>Отзывы</span>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="javascript:void(0);" class="btn btn-primary mb-1"  data-bs-toggle="modal" data-bs-target="#sendMessageModal">Отправить Сообщение</a>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="sendMessageModal">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Отправить Сообщение</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="comment-form">
                                            @csrf
                                            <div class="row"> 
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="text-black font-w600">Имя <span class="required">*</span></label>
                                                        <input type="text" class="form-control" value="author" name="author" placeholder="автор">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="text-black font-w600">Номер Проекта</label>
                                                        <input type="text" class="form-control" placeholder="Проект" name="design">
                                                    </div>
                                                </div>
												<div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="text-black font-w600">Номер заказа (если есть) 
                                                        <input type="text" class="form-control"  placeholder="Заказ" name="order">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="text-black font-w600">Сообщение</label>
                                                        <textarea rows="8" class="form-control" name="message" placeholder="Сообщение"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <input type="submit" value="Отправить" class="submit btn btn-primary" name="submit">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-blog mb-5">
                        <h5 class="text-primary d-inline">Какой нибудь пост о себе</h5><a href="javascript:void(0);" class="pull-right f-s-16">Больше</a>
                        <img src="{{ asset('images/profile/1.jpg')}}" alt="" class="img-fluid mt-4 mb-4 w-100 b-radius">
                        <h4><a href="{{ url('post-details')}}" class="text-black">Заголовок, например наша последняя работа такая то</a></h4>
                        <p class="mb-0">Маленькая река по имени Дуден течет рядом с их местом и снабжает его необходимыми а мы построили тут домики</p>
                    </div>
                    <div class="profile-interest mb-5">
                        <h5 class="text-primary d-inline">Интересы</h5>
                        <div class="row mt-4 sp4" id="lightgallery">
                            <a href="{{ asset('images/profile/2.jpg')}}" data-exthumbimage="{{ asset('images/profile/2.jpg')}}" data-src="{{ asset('images/profile/2.jpg')}}" class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                <img src="{{ asset('images/profile/2.jpg')}}" alt="" class="img-fluid b-radius">
                            </a>
                            <a href="{{ asset('images/profile/3.jpg')}}" data-exthumbimage="{{ asset('images/profile/3.jpg')}}" data-src="{{ asset('images/profile/3.jpg')}}" class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                <img src="{{ asset('images/profile/3.jpg')}}" alt="" class="img-fluid b-radius">
                            </a>
                            <a href="{{ asset('images/profile/4.jpg')}}" data-exthumbimage="{{ asset('images/profile/4.jpg')}}" data-src="{{ asset('images/profile/4.jpg')}}" class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                <img src="{{ asset('images/profile/4.jpg')}}" alt="" class="img-fluid b-radius">
                            </a>
                            <a href="{{ asset('images/profile/3.jpg')}}" data-exthumbimage="{{ asset('images/profile/3.jpg')}}" data-src="{{ asset('images/profile/3.jpg')}}" class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                <img src="{{ asset('images/profile/3.jpg')}}" alt="" class="img-fluid b-radius">
                            </a>
                            <a href="{{ asset('images/profile/4.jpg')}}" data-exthumbimage="{{ asset('images/profile/4.jpg')}}" data-src="{{ asset('images/profile/4.jpg')}}" class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                <img src="{{ asset('images/profile/4.jpg')}}" alt="" class="img-fluid b-radius">
                            </a>
                            <a href="{{ asset('images/profile/2.jpg')}}" data-exthumbimage="{{ asset('images/profile/2.jpg')}}" data-src="{{ asset('images/profile/2.jpg')}}" class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                <img src="{{ asset('images/profile/2.jpg')}}" alt="" class="img-fluid b-radius">
                            </a>
                        </div>
                    </div>
                    <div class="profile-news">
                        <h5 class="text-primary d-inline">Наши Последние Новости</h5>
                        <div class="media pt-3 pb-3">
                            <img src="{{ asset('images/profile/5.jpg')}}" alt="image" class="me-3 rounded" width="75">
                            <div class="media-body">
                                <h5 class="m-b-5"><a href="{{ url('post-details')}}" class="text-black">Работа 2</a></h5>
                                <p class="mb-0">Я поделился этим на своей стене несколько месяцев назад, и я думал.</p>
                            </div>
                        </div>
                        <div class="media pt-3 pb-3">
                            <img src="{{ asset('images/profile/6.jpg')}}" alt="image" class="me-3 rounded" width="75">
                            <div class="media-body">
                                <h5 class="m-b-5"><a href="{{ url('post-details')}}" class="text-black">Работа 3</a></h5>
                                <p class="mb-0">Я поделился этим на своей стене несколько месяцев назад, и я думал.</p>
                            </div>
                        </div>
                        <div class="media pt-3 pb-3">
                            <img src="{{ asset('images/profile/7.jpg')}}" alt="image" class="me-3 rounded" width="75">
                            <div class="media-body">
                                <h5 class="m-b-5"><a href="{{ url('post-details')}}" class="text-black">Работа 4</a></h5>
                                <p class="mb-0">Я поделился этим на своей стене несколько месяцев назад, и я думал.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="profile-tab">
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a href="#my-posts"  data-bs-toggle="tab" class="nav-link active show">Новости</a>
                                </li>
                                <li class="nav-item"><a href="#about-me"  data-bs-toggle="tab" class="nav-link">Наша компания</a>
                                </li>
                                <li class="nav-item"><a href="#profile-settings"  data-bs-toggle="tab" class="nav-link">Еще вкладка</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="my-posts" class="tab-pane fade active show">
                                    <div class="my-post-content pt-3">
                                        <div class="post-input">
                                            <textarea name="textarea" id="textarea" cols="30" rows="7" class="form-control bg-transparent" placeholder="Пожалуйста, напишите, что вы хотите...."></textarea> 
                                            <a href="javascript:void(0);" class="btn btn-primary light px-3" data-bs-toggle="modal" data-bs-target="#linkModal"><i class="fa fa-link"></i> </a>
                                            <a href="javascript:void(0);" class="btn btn-primary light me-1 px-3" data-bs-toggle="modal" data-bs-target="#cameraModal"><i class="fa fa-camera"></i> </a>
                                            <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#postModal">Пост</a>
                                        </div>
                                        <div class="profile-uoloaded-post border-bottom-1 pb-5">
                                            <img src="{{ asset('images/profile/8.jpg')}}" alt="" class="img-fluid">
                                            <a class="post-title" href="{{ url('post-details')}}">
                                                <h3 class="text-black">Коллекция текстильных образцов разложена</h3>
                                            </a>
                                            <p>Прекрасное спокойствие владеет моей душой, подобно этим сладким утрам весны, которые я наслаждаюсь всем сердцем. Я один, и чувствую очарование существования в этом месте, созданном для блаженства душ, подобных моей. Я так счастлив, мой дорогой друг, так поглощен восхитительным чувством спокойного существования, что пренебрегаю своими талантами.</p>
                                            
                                        </div>
                                        <div class="profile-uoloaded-post border-bottom-1 pb-5">
                                            <img src="{{ asset('images/profile/9.jpg')}}" alt="" class="img-fluid">
                                            <a class="post-title" href="{{ url('post-details')}}">
                                                <h3 class="text-black">Коллекция текстильных образцов разложена</h3>
                                            </a>
                                            <p>Прекрасное спокойствие владеет моей душой, подобно этим сладким утрам весны, которые я наслаждаюсь всем сердцем. Я один, и чувствую очарование существования в этом месте, созданном для блаженства душ, подобных моей. Я так счастлив, мой дорогой друг, так поглощен восхитительным чувством спокойного существования, что пренебрегаю своими талантами.</p>
                                           
                                        </div>
                                        <div class="profile-uoloaded-post pb-3">
                                            <img src="{{ asset('images/profile/8.jpg')}}" alt="" class="img-fluid">
                                            <a class="post-title" href="{{ url('post-details')}}">
                                                <h3 class="text-black">Коллекция текстильных образцов разложена</h3>
                                            </a>
                                            <p>Прекрасное спокойствие владеет моей душой, подобно этим сладким утрам весны, которые я наслаждаюсь всем сердцем. Я один, и чувствую очарование существования в этом месте, созданном для блаженства душ, подобных моей. Я так счастлив, мой дорогой друг, так поглощен восхитительным чувством спокойного существования, что пренебрегаю своими талантами.</p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div id="about-me" class="tab-pane fade">
                                    <div class="profile-about-me">
                                        <div class="pt-4 border-bottom-1 pb-3">
                                            <h4 class="text-primary">Обо Мне</h4>
                                            <p class="mb-2">Прекрасное спокойствие владеет моей душой, подобно этим сладким утрам весны, которые я наслаждаюсь всем сердцем. Я один, и чувствую очарование существования в этом месте, созданном для блаженства душ, подобных моей. Я так счастлив, мой дорогой друг, так поглощен восхитительным чувством спокойного существования, что пренебрегаю своими талантами.</p>
                                            <p>Коллекция текстильных образцов разложена на столе - Самса был коммивояжером - и над ним висела картина, которую он недавно вырезал из иллюстрированного журнала и поместил в красивую, позолоченную раму.</p>
                                        </div>
                                    </div>
                                    <div class="profile-skills mb-5">
                                        <h4 class="text-primary mb-2">Навыки</h4>
                                        <a href="javascript:void(0);" class="btn btn-primary light btn-xs mb-1">Что они поставляют</a>
                                        <a href="javascript:void(0);" class="btn btn-primary light btn-xs mb-1">Может что то другое сюда</a>
                                    </div>
                                    <div class="profile-lang  mb-5">
                                        <h4 class="text-primary mb-2">Язык</h4>
                                        <a href="javascript:void(0);" class="text-muted pe-3 f-s-16"><i class="flag-icon flag-icon-us"></i> Английский</a> 
                                        <a href="javascript:void(0);" class="text-muted pe-3 f-s-16"><i class="flag-icon flag-icon-ru"></i> Русский</a>
                                    </div>
                                    <div class="profile-personal-info">
                                        <h4 class="text-primary mb-4">Личная Информация</h4>
                                        <div class="row mb-4 mb-sm-2">
                                            <div class="col-sm-3">
                                                <h5 class="f-w-500">Компания <span class="pull-right d-none d-sm-block">:</span>
                                                </h5>
                                            </div>
                                            <div class="col-sm-9"><span>Borodin Services Ltd</span>
                                            </div>
                                        </div>
                                        <div class="row mb-4 mb-sm-2">
                                            <div class="col-sm-3">
                                                <h5 class="f-w-500">Email <span class="pull-right d-none d-sm-block">:</span>
                                                </h5>
                                            </div>
                                            <div class="col-sm-9"><span>example@examplel.com</span>
                                            </div>
                                        </div>
                                        <div class="row mb-4 mb-sm-2">
                                            <div class="col-sm-3">
                                                <h5 class="f-w-500">Доступность <span class="pull-right d-none d-sm-block">:</span></h5>
                                            </div>
                                            <div class="col-sm-9"><span>Полный рабочий день</span>
                                            </div>
                                        </div>
                                        <div class="row mb-4 mb-sm-2">
                                            <div class="col-sm-3">
                                                <h5 class="f-w-500">Возраст <span class="pull-right d-none d-sm-block">:</span>
                                                </h5>
                                            </div>
                                            <div class="col-sm-9"><span>10 лет на рынке</span>
                                            </div>
                                        </div>
                                        <div class="row mb-4 mb-sm-2">
                                            <div class="col-sm-3">
                                                <h5 class="f-w-500">Местоположение <span class="pull-right d-none d-sm-block">:</span></h5>
                                            </div>
                                            <div class="col-sm-9"><span>Севастополь и АО Крым</span>
                                            </div>
                                        </div>
                                        <div class="row mb-4 mb-sm-2">
                                            <div class="col-sm-3">
                                                <h5 class="f-w-500">Опыт работы <span class="pull-right d-none d-sm-block">:</span></h5>
                                            </div>
                                            <div class="col-sm-9"><span>07 лет опыта</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="profile-settings" class="tab-pane fade">
                                    <div class="pt-3">
                                        <div class="settings-form">
                                            <h4 class="text-primary">Настройка аккаунта</h4>
                                            <form>
                                                @csrf
                                                <div class="form-row row">
                                                    <div class="form-group col-md-6">
                                                        <label>Email</label>
                                                        <input type="email" placeholder="Email" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Пароль</label>
                                                        <input type="password" placeholder="Пароль" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Адрес</label>
                                                    <input type="text" placeholder="1234 Main St" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Адрес 2</label>
                                                    <input type="text" placeholder="Квартира, студия или этаж" class="form-control">
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Город</label>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Регион</label>
                                                        <select class="form-control form-control-lg default-select" id="inputState">
                                                            <option selected="">Выбрать...</option>
                                                            <option>Опция 1</option>
                                                            <option>Опция 2</option>
                                                            <option>Опция 3</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label>Индекс</label>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="form-check-input" id="gridCheck">
                                                        <label class="custom-control-label" for="gridCheck"> Проверьте меня</label>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary" type="submit">Войти</button>
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
    </div>
</div>
@endsection
