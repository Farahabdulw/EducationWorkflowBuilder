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

    <div class="d-flex justify-content-between">
        <div class="d-flex flex-column pt-3 pb-2 ">
            <h4 class="mb-0">
                <span class="text-muted fw-light">Add a new Course</span>
            </h4>
            <p class="text-muted">Version : 1</p>
        </div>
        <div class="d-flex flex-column align-items-end pt-3 pb-2">
            <p class="text-muted mb-0">Last Revision:</p>
            <p class="text-muted">20/2/1 12 AM by Developer</p>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5>Course Specification</h5>
        </div>
        <div id="add-form" method="post"
            class="card-body row-gap-md-1 row-gap-lg-0 row-gap-sm-0 row d-flex browser-default-validation">
            @csrf

            <div class="col-md-6 col-lg-4 col-sm-12 pb-1">
                <label for="title" class="form-label">Course Title</label>
                <input type="text" id="title" aria-label="Course Title" class="form-control" required>
            </div>

            <div class="col-md-6 col-lg-4 col-sm-12 pb-1">
                <label for="code" class="form-label">Course Code</label>
                <input type="text" class="form-control" id="code" required>
            </div>

            <div class="col-md-6 col-lg-4 col-sm-12 pb-1">
                <label for="code" class="form-label">Course Program</label>
                <input type="text" class="form-control" id="code" required>
            </div>

            <div class="col-md-6 col-lg-4 col-sm-12 pb-1">
                <label for="departments" class="form-label">Department</label>
                <select id="departments" class="select2-hidden-accessible">
                </select>
            </div>

            <div class="col-md-6 col-lg-4 col-sm-12 pb-1">
                <label for="colleges" class="form-label">College</label>
                <select id="colleges" class="select2-hidden-accessible">
                </select>
            </div>

            <div class="col-md-6 col-lg-4 col-sm-12 pb-1">
                <label for="institutions" class="form-label">Institution</label>
                <select id="institutions" class="select2-hidden-accessible">
                </select>
            </div>

            {{-- <div class="col-md-6 col-lg-4 col-sm-12 PLOS border rounded p-3">
                <label for="TagifyPLOSList" class="form-label d-block">PLOs</label>
                <input id="TagifyPLOSList" class="tagify-email-list" tabindex="-1">
                <button type="button" class="btn btn-sm rounded-pill btn-icon btn-outline-primary mb-1 waves-effect">
                    <span class="tf-icons ti ti-plus">
                    </span>
                </button>
            </div> --}}



            {{-- <div class="col-md-6 col-lg-4 col-sm-12 border rounded p-3">
                <label class="form-label d-block">CLOs</label>
                <div class="row ps-2">
                    <div class="col-lg-10 col-sm-12">
                        <div class="row pt-1">
                            <div class="col-md-6 col-lg-4 col-sm-12 PLOS">
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
                            <div class="col-md-6 col-lg-4 col-sm-12 PLOS">
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
                            <div class="col-md-6 col-lg-4 col-sm-12 PLOS">
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

            <div class="col-md-6 col-lg-4 col-sm-12 border rounded p-3">
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
                                <td class="text-center" colspan="3"> No student were added</td>
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

            </div> --}}

        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h5>Course Identification</h5>
        </div>
        <div class="card-body d-flex">
            <div class="col-12">
                <div class=" col-md-6 col-lg-6 col-sm-12 pt-2">
                    <div class="input-group">
                        <span class="input-group-text">Credit Hours</span>
                        <input type="number" aria-label="First name" class="form-control">
                        <span class="input-group-text">Tatorial Hours</span>
                        <input type="number" aria-label="Last name" class="form-control">
                    </div>
                </div>
            </div>

            <div class="border py-3">
                <div class="col-12">
                    <label class="col-2 switch switch-lg">
                        <input type="checkbox" class="switch-input">
                        <span class="switch-toggle-slider">
                            <span class="switch-on">
                                <i class="ti ti-check"></i>
                            </span>
                            <span class="switch-off">
                                <i class="ti ti-x"></i>
                            </span>
                        </span>
                        <span class="switch-label">University</span>
                    </label>
                    <label class="col-2 switch switch-lg">
                        <input type="checkbox" class="switch-input">
                        <span class="switch-toggle-slider">
                            <span class="switch-on">
                                <i class="ti ti-check"></i>
                            </span>
                            <span class="switch-off">
                                <i class="ti ti-x"></i>
                            </span>
                        </span>
                        <span class="switch-label">College</span>
                    </label>
                    <label class="col-2 switch switch-lg">
                        <input type="checkbox" class="switch-input">
                        <span class="switch-toggle-slider">
                            <span class="switch-on">
                                <i class="ti ti-check"></i>
                            </span>
                            <span class="switch-off">
                                <i class="ti ti-x"></i>
                            </span>
                        </span>
                        <span class="switch-label">Department</span>
                    </label>
                    <label class="col-2 switch switch-lg">
                        <input type="checkbox" class="switch-input">
                        <span class="switch-toggle-slider">
                            <span class="switch-on">
                                <i class="ti ti-check"></i>
                            </span>
                            <span class="switch-off">
                                <i class="ti ti-x"></i>
                            </span>
                        </span>
                        <span class="switch-label">Track</span>
                    </label>
                    <label class="col-2 switch switch-lg">
                        <input type="checkbox" class="switch-input">
                        <span class="switch-toggle-slider">
                            <span class="switch-on">
                                <i class="ti ti-check"></i>
                            </span>
                            <span class="switch-off">
                                <i class="ti ti-x"></i>
                            </span>
                        </span>
                        <span class="switch-label">Others</span>
                    </label>
                </div>
                <div class="col-12">
                    <label class="col-2 switch switch-lg">
                        <input type="checkbox" class="switch-input">
                        <span class="switch-toggle-slider">
                            <span class="switch-on">
                                <i class="ti ti-check"></i>
                            </span>
                            <span class="switch-off">
                                <i class="ti ti-x"></i>
                            </span>
                        </span>
                        <span class="switch-label">Required</span>
                    </label>
                    <label class="col-2 switch switch-lg">
                        <input type="checkbox" class="switch-input">
                        <span class="switch-toggle-slider">
                            <span class="switch-on">
                                <i class="ti ti-check"></i>
                            </span>
                            <span class="switch-off">
                                <i class="ti ti-x"></i>
                            </span>
                        </span>
                        <span class="switch-label">Elective</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="d-flex flex-row pt-3 col-md-6 col-lg-4 col-sm-12">
            <button id="formSubmition" class="btn btn-primary waves-effect waves-light">Add Course</button>
        </div>
    </div>
@endsection
