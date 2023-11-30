@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Add Course')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.repeater@1.2.1/dist/repeater.css"> --}}


@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/user-add.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>


@endsection

@section('page-script')
    <script src="{{ asset('assets/js/courses/create.js') }}"></script>
@endsection

@section('content')
    {{ Breadcrumbs::render('add-course') }}

    <div class="card">
        <div class="card-header">
            <h5>Add a new Course</h5>
        </div>
        <div id="add-form" method="post" action=""
            class="card-body col d-flex flex-column gap-3 browser-default-validation">
            @csrf
            <div class="d-flex flex-row gap-2 col-md-7 col-lg-7 col-sm-12">
                <div class="input-group">
                    <span class="input-group-text">Course Title</span>
                    <input type="text" id="title" aria-label="Math 101" class="form-control" required>
                </div>
            </div>

            <div class="col-md-7 col-lg-7 col-sm-12">
                <label for="code" class="form-label">Course Code</label>
                <input type="text" class="form-control" id="code" placeholder="A345fxg45" required>
            </div>

            <div class="col-md-7 col-lg-7 col-sm-12 PLOS border rounded p-3">
                <label for="TagifyPLOSList" class="form-label d-block">PLOs</label>
                <input id="TagifyPLOSList" class="tagify-email-list" tabindex="-1">
                <button type="button" class="btn btn-sm rounded-pill btn-icon btn-outline-primary mb-1 waves-effect">
                    <span class="tf-icons ti ti-plus">
                    </span>
                </button>
            </div>


            <div class="col-md-7 col-lg-7 col-sm-12 border rounded p-3">
                <label class="form-label d-block">CLOs</label>
                <div class="row ps-2">
                    <div class="col-lg-10 col-sm-12">
                        <div class="row pt-1">
                            <div class="col-md-7 col-lg-7 col-sm-12 PLOS">
                                <label for="TagifyKnowlegeList" class="form-label text-muted d-block">Knowledge and
                                    understanding</label>
                                <input id="TagifyKnowlegeList" class="ps-1 tagify-items tagify-email-list" tabindex="-1">
                                <button type="button"
                                    class="btn btn-sm rounded-pill btn-icon btn-outline-primary mb-1 waves-effect">
                                    <span class="tf-icons ti ti-plus">
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="row pt-1">
                            <div class="col-md-7 col-lg-7 col-sm-12 PLOS">
                                <label for="TagifySkillsList" class="form-label text-muted d-block">Skills</label>
                                <input id="TagifySkillsList" class="ps-1 tagify-items tagify-email-list" tabindex="-1">
                                <button type="button"
                                    class="btn btn-sm rounded-pill btn-icon btn-outline-primary mb-1 waves-effect">
                                    <span class="tf-icons ti ti-plus">
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="row pt-1">
                            <div class="col-md-7 col-lg-7 col-sm-12 PLOS">
                                <label for="TagifyValuesList" class="form-label text-muted d-block">values</label>
                                <input id="TagifyValuesList" class="ps-1 tagify-items tagify-email-list" tabindex="-1">
                                <button type="button"
                                    class="btn btn-sm rounded-pill btn-icon btn-outline-primary mb-1 waves-effect">
                                    <span class="tf-icons ti ti-plus">
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7 col-lg-7 col-sm-12 border rounded p-3">
                <p>Student Info</p>
                <div class="row">
                    <table id="students" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">

                            <tr class="noData">
                                <td class="text-center" colspan="3" > No student were added</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <form id="addStudent" action="">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <input type="text" class="name form-control" placeholder="Student Name">
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <input type="text" class="id form-control" placeholder="Student ID">
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-12 d-flex justify-content-evenly">
                            <button class="btn btn-label-primary mb-4">
                                <i class="ti ti-check me-1"></i>
                            </button>
                        </div>
                    </div>
                </form>

            </div>

            <div class="col-md-7 col-lg-7 col-sm-12 p-b-0">
                <label for="departments" class="form-label">Department</label>
                <select id="departments" class="select2-hidden-accessible" name="state">
                </select>
            </div>
            <div class="d-flex flex-row gap-2 col-md-7 col-lg-7 col-sm-12">
                <button id="formSubmition" class="btn btn-primary waves-effect waves-light">Add Course</button>
            </div>
        </div>
    </div>
@endsection
