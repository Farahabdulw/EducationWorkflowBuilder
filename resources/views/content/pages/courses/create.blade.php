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
            <p class="text-muted version">Version : 1</p>
        </div>
        <div class="d-flex flex-column align-items-end pt-3 pb-2">
            <p class="text-muted mb-0">Last Revision:</p>
            <p class="text-muted lastModifed">20/2/1 12 AM by Developer</p>
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
                <label for="program" class="form-label">Course Program</label>
                <input type="text" class="form-control" id="program" required>
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
        <div class="card-body d-flex flex-column ">
            <div class="col-12 d-flex justify-content-between">
                <div class=" col-md-6 col-lg-6 col-sm-12 pt-2">
                    <div class="input-group">
                        <span class="input-group-text creditHours">Credit Hours</span>
                        <input type="number" aria-label="First name" class="form-control">
                        <span class="input-group-text tatorialHours">Tatorial Hours</span>
                        <input type="number" aria-label="Last name" class="form-control">
                    </div>
                </div>
            </div>

            <div class="mt-2 border rounded p-3">
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
                <div class="col-12 pt-2">
                    <label class="col-2 switch switch-lg">
                        <input type="checkbox" class="switch-input" id="requiredCheckbox" name="subject-stat">
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
                        <input type="checkbox" class="switch-input" id="electiveCheckbox" name="subject-stat">
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

            <div class="col-12 d-flex justify-content-between">
                <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                    <div class="input-group">
                        <span class="input-group-text">Course Level or Year to be Offered</span>
                        <input type="number" aria-label="level" class="form-control coruseLevel">
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex flex-column pt-2">
                <label for="description" class="form-label description">Course Description</label>
                <textarea id="description" rows="3" class="form-control"
                    style="overflow: hidden; overflow-wrap: break-word; resize: none; text-align: start; height: 83px;"></textarea>
            </div>
            <div class="d-flex justify-content-between ">
                <div class="col-lg-6 col-md-12 pe-1 col-sm-12 pt-2">
                    <table id="preRequirements" class="table table-striped">
                        <thead>
                            <tr>
                                <th> Course Pre-requirements </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            {{-- <tr class="noData">
                            <td class="text-center"> No pre-requirements were added</td>
                        </tr> --}}
                            <tr>
                                <td>math 101</td>
                            </tr>
                            <tr>
                                <td>fundamentals of algebra</td>
                            </tr>
                        </tbody>
                    </table>
                    <form id="addPreRequirements" class="pt-1">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-sm-12">
                                <input type="text" class="name form-control" placeholder="Add a Pre-requirement ">
                            </div>
                            <div class="col-md-1 col-lg-1 col-sm-12 d-flex justify-content-evenly">
                                <button class="btn btn-label-primary mb-4">
                                    <i class="ti ti-check me-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 col-md-12 ps-1 col-sm-12 py-2">
                    <table id="coRequisites" class="table table-striped">
                        <thead>
                            <tr>
                                <th> Course Co-requisites</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td>lab math 101</td>
                            </tr>
                            {{-- <tr class="noData">
                            <td class="text-center"> No co-requisites were added</td>
                        </tr> --}}
                        </tbody>
                    </table>
                    <form id="addCoRequisites" class="pt-1">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-sm-12">
                                <input type="text" class="name form-control" placeholder="Add a Pre-requirement ">
                            </div>
                            <div class="col-md-1 col-lg-1 col-sm-12 d-flex justify-content-evenly">
                                <button class="btn btn-label-primary mb-4">
                                    <i class="ti ti-check me-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12">
                <table id="mainObjective" class="table table-striped">
                    <thead>
                        <tr>
                            <th> Course Main Objective </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                        <tr class="noData">
                            <td class="text-center"> No main objectives were added</td>
                        </tr>
                    </tbody>
                </table>
                <form id="addMainObjective" class="pt-1">
                    <div class="row">
                        <div class="col-md-11 col-lg-11 col-sm-12">
                            <input type="text" class="name form-control" placeholder="Add a main objective">
                        </div>
                        <div class="col-md-1 col-lg-1 col-sm-12 d-flex justify-content-evenly">
                            <button class="btn btn-label-primary mb-4">
                                <i class="ti ti-check me-1"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h5>Teaching mode</h5>
        </div>
        <div class="card-body">
            <div class="col-12">
                <table id="teachingMode" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width:10%"> NO </th>
                            <th> Mode of Instruction </th>
                            <th> Contact Hours </th>
                            <th> Percentage </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <tr class="noData">
                            <td>1</td>
                            <td> Traditional classroom</td>
                            <td> From 10:00 AM To 12:00 AM </td>
                            <td> 10% </td>
                        </tr>
                    </tbody>
                </table>
                <form id="addTeachingMode" class="pt-1">
                    <div class="row">
                        <div class="col-1"></div>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            <input type="text" class="name form-control">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            <input type="text" class="name form-control">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            <input type="text" class="name form-control">
                        </div>
                        <div class="col-md-1 col-lg-1 col-sm-12 d-flex justify-content-evenly">
                            <button class="btn btn-label-primary mb-4">
                                <i class="ti ti-check me-1"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h5>Contact Hours</h5>
        </div>
        <div class="card-body">
            <div class="col-12">
                <table id="contactHours" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width:10%"> NO </th>
                            <th> Activity </th>
                            <th> Contact Hours </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <tr class="noData">
                            <td>1</td>
                            <td> Traditional classroom</td>
                            <td> 10% </td>
                        </tr>
                    </tbody>
                </table>
                <form id="addContactHours" class="pt-1">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <input type="text" class="name form-control">
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <input type="text" class="name form-control">
                        </div>
                        <div class="col-md-1 col-lg-1 col-sm-12 d-flex justify-content-evenly">
                            <button class="btn btn-label-primary mb-4">
                                <i class="ti ti-check me-1"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h5>Instructional Framework</h5>
        </div>
        <div class="card-body">
            <div class="col-12">
                <table id="instructionalFramwork" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width:10%"> Code </th>
                            <th> Course Learning Outcomes </th>
                            <th> Code of CLOs aligned with program </th>
                            <th> Teaching Strategies </th>
                            <th> Assessment Methods </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <tr class="group">
                            <td> 1.0 </td>
                            <td colspan="4">Knowledge and understanding</td>
                        </tr>
                        <tr class="even">
                            <td>1</td>
                            <td>Define key concepts in the field</td>
                            <td>CLO-001</td>
                            <td>Lectures, Readings</td>
                            <td>Written Exam</td>
                        </tr>
                        <tr class="even">
                            <td>2</td>
                            <td>Explain the historical development of the subject</td>
                            <td>CLO-002</td>
                            <td>Class Discussions, Research</td>
                            <td>Research Paper</td>
                        </tr>
                        <tr class="even">
                            <td>3</td>
                            <td>Apply theoretical frameworks to real-world scenarios</td>
                            <td>CLO-003</td>
                            <td>Case Studies, Group Projects</td>
                            <td>Presentations</td>
                        </tr>
                        <tr class="even">
                            <td>4</td>
                            <td>Analyze and interpret data relevant to the subject</td>
                            <td>CLO-004</td>
                            <td>Lab Work, Data Analysis</td>
                            <td>Lab Report</td>
                        </tr>

                        <tr class="group">
                            <td colspan="5">Skills</td>
                        </tr>

                        <tr class="group">
                            <td colspan="5">Values, autonomy responsibility</td>
                        </tr>
                    </tbody>
                </table>
                {{-- <form id="addInstructionalFramwork" class="pt-1">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <input type="text" class="name form-control">
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <input type="text" class="name form-control">
                        </div>
                        <div class="col-md-1 col-lg-1 col-sm-12 d-flex justify-content-evenly">
                            <button class="btn btn-label-primary mb-4">
                                <i class="ti ti-check me-1"></i>
                            </button>
                        </div>
                    </div>
                </form> --}}
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="d-flex flex-row pt-3 col-md-6 col-lg-4 col-sm-12">
            <button id="formSubmition" class="btn btn-primary waves-effect waves-light">Add Course</button>
        </div>
    </div>

@endsection
