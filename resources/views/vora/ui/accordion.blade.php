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
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Accordion</a></li>
        </ol>
    </div>
    <!-- row -->
    <!-- Row starts -->
    <div class="row">
        <!-- Column starts -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-block">
                    <h4 class="card-title">Default Accordion</h4>
                    <p class="m-0 subtitle">Default accordion. Add <code>accordion</code> class in root</p>
                </div>
                <div class="card-body">
                    <!-- Default accordion -->
                    <div id="accordion-one" class="accordion accordion-primary">
                        <div class="accordion__item">
                            <div class="accordion__header rounded-lg" id="headingOne" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-controls="collapseOne"   aria-expanded="true" role="button">
                                <span class="accordion__header--text">Accordion Header One</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-bs-parent="#accordion-one">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed rounded-lg" id="headingTwo" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-controls="collapseTwo"   role="button" aria-expanded="true">
                                <span class="accordion__header--text">Accordion Header Two</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordion-one">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed rounded-lg" id="headingThree" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-controls="collapseThree"  role="button"  aria-expanded="true">
                                <span class="accordion__header--text">Accordion Header Three</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-bs-parent="#accordion-one">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column ends -->
        <!-- Column starts -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-block">
                    <h4 class="card-title">Accordion bordered</h4>
                    <p class="m-0 subtitle">Accordion with border. Add class <code>accordion-bordered</code> with the class <code> accordion</code></p>
                </div>
                <div class="card-body">
                    <div id="accordion-two" class="accordion accordion-danger-solid">
                        <div class="accordion__item">
                            <div class="accordion__header" id="accord-2One" data-bs-toggle="collapse" data-bs-target="#collapse2One" aria-controls="collapse2One"   aria-expanded="true"  role="button"> 
                                <span class="accordion__header--text">Accordion Header One</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="collapse2One" class="collapse accordion__body show" aria-labelledby="accord-2One" data-bs-parent="#accordion-two">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed" id="accord-2Two" data-bs-toggle="collapse" data-bs-target="#collapse2Two" aria-controls="collapse2Two"   aria-expanded="true"  role="button"> 
                                <span class="accordion__header--text">Accordion Header Two</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="collapse2Two" class="collapse accordion__body" aria-labelledby="accord-2Two" data-bs-parent="#accordion-two">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed"  id="accord-2Three" data-bs-toggle="collapse" data-bs-target="#collapse2Three" aria-controls="collapse2Three"   aria-expanded="true"  role="button">
                                 <span class="accordion__header--text">Accordion Header Three</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="collapse2Three" class="collapse accordion__body" aria-labelledby="accord-2Three" data-bs-parent="#accordion-two">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column ends -->
        <!-- Column starts -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-block">
                    <h4 class="card-title">Accordion without space</h4>
                    <p class="m-0 subtitle">Add <code>accordion-no-gutter</code> class with <code>accordion</code></p>
                </div>
                <div class="card-body">
                    <div id="accordion-three" class="accordion accordion-no-gutter accordion-header-bg">
                        <div class="accordion__item">
                            <div class="accordion__header" id="accord-3One" data-bs-toggle="collapse" data-bs-target="#collapse3One" aria-controls="collapse3One"   aria-expanded="true"  role="button">
                                <span class="accordion__header--text">Accordion Header One</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="collapse3One" class="collapse accordion__body show" aria-labelledby="accord-3One" data-bs-parent="#accordion-three">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed" data-bs-toggle="collapse" data-bs-target="#no-gutter_collapseTwo">
                                <span class="accordion__header--text">Accordion Header Two</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="no-gutter_collapseTwo" class="collapse accordion__body" data-bs-parent="#accordion-three">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed" data-bs-toggle="collapse" data-bs-target="#no-gutter_collapseThree">
                                <span class="accordion__header--text">Accordion Header Three</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="no-gutter_collapseThree" class="collapse accordion__body" data-bs-parent="#accordion-three">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column ends -->
        <!-- Column starts -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-block">
                    <h4 class="card-title">Accordion without space with border</h4>
                    <p class="m-0 subtitle">Add <code>accordion-no-gutter accordion-bordered</code> class with <code>accordion</code></p>
                </div>
                <div class="card-body">
                    <div id="accordion-four" class="accordion accordion-no-gutter accordion-bordered">
                        <div class="accordion__item">
                            <div class="accordion__header" data-bs-toggle="collapse" data-bs-target="#bordered_no-gutter_collapseOne">
                                <span class="accordion__header--text">Accordion Header One</span>
                                <span class="accordion__header--indicator style_two"></span>
                            </div>
                            <div id="bordered_no-gutter_collapseOne" class="collapse accordion__body show" data-bs-parent="#accordion-four">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed" data-bs-toggle="collapse" data-bs-target="#bordered_no-gutter_collapseTwo">
                                <span class="accordion__header--text">Accordion Header Two</span>
                                <span class="accordion__header--indicator style_two"></span>
                            </div>
                            <div id="bordered_no-gutter_collapseTwo" class="collapse accordion__body" data-bs-parent="#accordion-four">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed" data-bs-toggle="collapse" data-bs-target="#bordered_no-gutter_collapseThree">
                                <span class="accordion__header--text">Accordion Header Three</span>
                                <span class="accordion__header--indicator style_two"></span>
                            </div>
                            <div id="bordered_no-gutter_collapseThree" class="collapse accordion__body" data-bs-parent="#accordion-four">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column ends -->
        <!-- Column starts -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-block">
                    <h4 class="card-title">Accordion indicator in left position</h4>
                    <p class="m-0 subtitle">Add <code>accordion-left-indicator</code> class with <code>accordion</code></p>
                </div>
                <div class="card-body">
                    <div id="accordion-five" class="accordion accordion-left-indicator">
                        <div class="accordion__item">
                            <div class="accordion__header" data-bs-toggle="collapse" data-bs-target="#left-indicator_collapseOne">
                                <span class="accordion__header--text">Accordion Header One</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="left-indicator_collapseOne" class="collapse accordion__body show" data-bs-parent="#accordion-five">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed" data-bs-toggle="collapse" data-bs-target="#left-indicator_collapseTwo">
                                <span class="accordion__header--text">Accordion Header Two</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="left-indicator_collapseTwo" class="collapse accordion__body" data-bs-parent="#accordion-five">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed" data-bs-toggle="collapse" data-bs-target="#left-indicator_collapseThree">
                                <span class="accordion__header--text">Accordion Header Three</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="left-indicator_collapseThree" class="collapse accordion__body" data-bs-parent="#accordion-five">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column ends -->
        <!-- Column starts -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-block">
                    <h4 class="card-title">Accordion with icon</h4>
                    <p class="m-0 subtitle">Add <code>accordion-with-icon</code> class with <code>accordion</code></p>
                </div>
                <div class="card-body">
                    <div id="accordion-six" class="accordion accordion-with-icon">
                        <div class="accordion__item">
                            <div class="accordion__header" data-bs-toggle="collapse" data-bs-target="#with-icon_collapseOne">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header One</span>
                                <span class="accordion__header--indicator indicator_bordered"></span>
                            </div>
                            <div id="with-icon_collapseOne" class="collapse accordion__body show" data-bs-parent="#accordion-six">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed" data-bs-toggle="collapse" data-bs-target="#with-icon_collapseTwo">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Two</span>
                                <span class="accordion__header--indicator indicator_bordered"></span>
                            </div>
                            <div id="with-icon_collapseTwo" class="collapse accordion__body" data-bs-parent="#accordion-six">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed" data-bs-toggle="collapse" data-bs-target="#with-icon_collapseThree">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Three</span>
                                <span class="accordion__header--indicator indicator_bordered"></span>
                            </div>
                            <div id="with-icon_collapseThree" class="collapse accordion__body" data-bs-parent="#accordion-six">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column ends -->
        <!-- Column starts -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-block">
                    <h4 class="card-title">Accordion header background</h4>
                    <p class="m-0 subtitle">Add <code>accordion-header-bg</code> class with <code>accrodion</code></p>
                </div>
                <div class="card-body">
                    <div id="accordion-seven" class="accordion accordion-header-bg accordion-bordered">
                        <div class="accordion__item">
                            <div class="accordion__header accordion__header--primary" data-bs-toggle="collapse" data-bs-target="#header-bg_collapseOne">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header One</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="header-bg_collapseOne" class="collapse accordion__body show" data-bs-parent="#accordion-seven">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed accordion__header--info" data-bs-toggle="collapse" data-bs-target="#header-bg_collapseTwo">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Two</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="header-bg_collapseTwo" class="collapse accordion__body" data-bs-parent="#accordion-seven">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed accordion__header--success" data-bs-toggle="collapse" data-bs-target="#header-bg_collapseThree">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Three</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="header-bg_collapseThree" class="collapse accordion__body" data-bs-parent="#accordion-seven">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column ends -->
        <!-- Column starts -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-block">
                    <h4 class="card-title">Accordion solid background</h4>
                    <p class="m-0 subtitle">Add class <code>accordion-solid-bg</code> with <code>accordion</code></p>
                </div>
                <div class="card-body">
                    <div id="accordion-eight" class="accordion accordion-solid-bg">
                        <div class="accordion__item">
                            <div class="accordion__header accordion__header--primary" data-bs-toggle="collapse" data-bs-target="#solid-bg_collapseOne">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header One</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="solid-bg_collapseOne" class="collapse accordion__body show" data-bs-parent="#accordion-eight">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed accordion__header--info" data-bs-toggle="collapse" data-bs-target="#solid-bg_collapseTwo">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Two</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="solid-bg_collapseTwo" class="collapse accordion__body" data-bs-parent="#accordion-eight">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed accordion__header--success" data-bs-toggle="collapse" data-bs-target="#solid-bg_collapseThree">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Three</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="solid-bg_collapseThree" class="collapse accordion__body" data-bs-parent="#accordion-eight">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column ends -->
        <!-- Column starts -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-block">
                    <h4 class="card-title">Accordion active background</h4>
                    <p class="m-0 subtitle">Add class <code>accordion-active-header</code> with <code>accordion</code></p>
                </div>
                <div class="card-body">
                    <div id="accordion-nine" class="accordion accordion-active-header">
                        <div class="accordion__item">
                            <div class="accordion__header" data-bs-toggle="collapse" data-bs-target="#active-header_collapseOne">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header One</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="active-header_collapseOne" class="collapse accordion__body show" data-bs-parent="#accordion-nine">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed" data-bs-toggle="collapse" data-bs-target="#active-header_collapseTwo">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Two</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="active-header_collapseTwo" class="collapse accordion__body" data-bs-parent="#accordion-nine">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed" data-bs-toggle="collapse" data-bs-target="#active-header_collapseThree">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Three</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="active-header_collapseThree" class="collapse accordion__body" data-bs-parent="#accordion-nine">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column ends -->
        <!-- Column starts -->
        <div class="col-xl-6">
            <div class="card transparent-card">
                <div class="card-header d-block">
                    <h4 class="card-title">Accordion header shadow</h4>
                    <p class="m-0 subtitle">Add <code>accordion-header-shadow</code> and <code>accordion-rounded</code> class with <code>accordion</code></p>
                </div>
                <div class="card-body">
                    <div id="accordion-ten" class="accordion accordion-header-shadow accordion-rounded">
                        <div class="accordion__item">
                            <div class="accordion__header collapsed accordion__header--primary" data-bs-toggle="collapse" data-bs-target="#header-shadow_collapseOne">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header One</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="header-shadow_collapseOne" class="collapse accordion__body show" data-bs-parent="#accordion-ten">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed accordion__header--info" data-bs-toggle="collapse" data-bs-target="#header-shadow_collapseTwo">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Two</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="header-shadow_collapseTwo" class="collapse accordion__body" data-bs-parent="#accordion-ten">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed accordion__header--success" data-bs-toggle="collapse" data-bs-target="#header-shadow_collapseThree">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Three</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="header-shadow_collapseThree" class="collapse accordion__body" data-bs-parent="#accordion-ten">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column ends -->
        <!-- Column starts -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-block">
                    <h4 class="card-title">Accordion rounded stylish</h4>
                    <p class="m-0 subtitle">Add <code>accordion-rounded-stylish</code> class with <code>accordion</code></p>
                </div>
                <div class="card-body">
                    <div id="accordion-eleven" class="accordion accordion-rounded-stylish accordion-bordered">
                        <div class="accordion__item">
                            <div class="accordion__header accordion__header--primary" data-bs-toggle="collapse" data-bs-target="#rounded-stylish_collapseOne">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header One</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="rounded-stylish_collapseOne" class="collapse accordion__body show" data-bs-parent="#accordion-eleven">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed accordion__header--info" data-bs-toggle="collapse" data-bs-target="#rounded-stylish_collapseTwo">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Two</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="rounded-stylish_collapseTwo" class="collapse accordion__body" data-bs-parent="#accordion-eleven">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed accordion__header--success" data-bs-toggle="collapse" data-bs-target="#rounded-stylish_collapseThree">
                                <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Three</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="rounded-stylish_collapseThree" class="collapse accordion__body" data-bs-parent="#accordion-eleven">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column ends -->
        <!-- Column starts -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-block">
                    <h4 class="card-title">Accordion gradient</h4>
                    <p class="m-0 subtitle">Add <code>accordion-gradient</code> class with <code>accordion</code></p>
                </div>
                <div class="card-body">
                    <div id="accordion-twelve" class="accordion accordion-rounded-stylish accordion-gradient">
                        <div class="accordion__item">
                            <div class="accordion__header accordion__header--primary" data-bs-toggle="collapse" data-bs-target="#gradient_collapseOne"> <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header One</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="gradient_collapseOne" class="collapse accordion__body show" data-bs-parent="#accordion-twelve">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed accordion__header--info" data-bs-toggle="collapse" data-bs-target="#gradient_collapseTwo"> <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Two</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="gradient_collapseTwo" class="collapse accordion__body" data-bs-parent="#accordion-twelve">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__header collapsed accordion__header--success" data-bs-toggle="collapse" data-bs-target="#gradient_collapseThree"> <span class="accordion__header--icon"></span>
                                <span class="accordion__header--text">Accordion Header Three</span>
                                <span class="accordion__header--indicator"></span>
                            </div>
                            <div id="gradient_collapseThree" class="collapse accordion__body" data-bs-parent="#accordion-twelve">
                                <div class="accordion__body--text">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column ends -->
    </div>
    <!-- Row ends -->
</div>
@endsection