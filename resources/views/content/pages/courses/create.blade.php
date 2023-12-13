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
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <style>
        .dark-style tr.group,
        .dark-style tr.group:hover {
            background-color: rgba(134, 146, 208, .1) !important;
        }
    </style>

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
    <script src="{{ asset('assets/vendor/libs/bloodhound/bloodhound.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>

@endsection

@section('page-script')
    <script src="{{ asset('assets/js/courses/create.js?time=' . time()) }}"></script>
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
    <form id="add-form" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5>Course Specification</h5>
            </div>
            <div class="card-body row-gap-md-1 row-gap-lg-0 row-gap-sm-0 row d-flex">
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
                            <input type="number" aria-label="First name" min=0 class="form-control">
                            <span class="input-group-text tatorialHours" >Tatorial Hours</span>
                            <input type="number" aria-label="Last name" min=0 class="form-control">
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
                            <input type="checkbox" class="switch-input courseType " id="requiredCheckbox" name="subject-stat">
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
                            <input type="checkbox" class="switch-input courseType " id="electiveCheckbox" name="subject-stat">
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
                                    <th style="width: 10%">No.</th>
                                    <th> Course Pre-requirements </th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                {{-- <tr class="noData">
                            <td class="text-center"> No pre-requirements were added</td>
                        </tr> --}}
                                <tr>
                                    <td>1</td>
                                    <td>math 101</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>fundamentals of algebra</td>
                                </tr>
                                <tr class="add-new-record preRequirements">
                                    <td>
                                        <button type="button" class="btn btn-label-primary add-new-record-btn">
                                            <i class="fa fa-add"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="preRequirements-inp"
                                            id="preRequirements-inp">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6 col-md-12 ps-1 col-sm-12 py-2">
                        <table id="coRequisites" class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width:10%">NO.</th>
                                    <th> Course Co-requisites</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <tr>
                                    <td>1</td>
                                    <td>lab math 101</td>
                                </tr>
                                <tr class="add-new-record coRequisites">
                                    <td>
                                        <button type="button" class="btn btn-label-primary add-new-record-btn">
                                            <i class="fa fa-add"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="coRequisites-inp"
                                            id="coRequisites-inp">
                                    </td>
                                </tr>
                                {{-- <tr class="noData">
                            <td class="text-center"> No co-requisites were added</td>
                        </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-12">
                    <table id="mainObjective" class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%">NO</th>
                                <th> Course Main Objective </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr class="noData">
                                <td class="text-center" colspan="2"> No main objectives were added</td>
                            </tr>
                            <tr class="add-new-record courseMainObjective">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="courseMainObjective"
                                        id="courseMainObjective">
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                            <tr class="add-new-record teachingMode">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="modeInstruction"
                                        id="modeInstruction">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="contactHours" id="contactHours">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="percentage" id="percentage">
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                            <tr class="add-new-record values">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="activity" id="activity">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="contactHours" id="contactHours">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h5>Instructional Framework</h5>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <table id="instructionalFramwork" class="dt-row-grouping table dataTable dtr-column collapsed">
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
                                <td>1.1</td>
                                <td>Define key concepts in the field</td>
                                <td>CLO-001</td>
                                <td>Lectures, Readings</td>
                                <td>Written Exam</td>
                            </tr>
                            <tr class="add-new-record knowledge">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="courseLearningOutcomes-knowledge"
                                        id=" courseLearningOutcomes-knowledge">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="codeCLOs-knowledge"
                                        id="codeCLOs-knowledge">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="teachingStrategies-knowledge"
                                        id=" teachingStrategies-knowledge">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="assessmentMethods-knowledge"
                                        id="assessmentMethods-knowledge">
                                </td>
                            </tr>
                            <tr class="group">
                                <td> 2.0 </td>
                                <td colspan="5">Skills</td>
                            </tr>
                            <tr class="add-new-record skills">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="courseLearningOutcomes-skills"
                                        id=" courseLearningOutcomes-skills">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="codeCLOs-skills"
                                        id="codeCLOs-skills">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="teachingStrategies-skills"
                                        id=" teachingStrategies-skills">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="assessmentMethods-skills"
                                        id="assessmentMethods-skills">
                                </td>
                            </tr>
                            <tr class="group">
                                <td> 3.0 </td>
                                <td colspan="5">Values, autonomy responsibility</td>
                            </tr>
                            <tr class="add-new-record values">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="courseLearningOutcomes-values"
                                        id=" courseLearningOutcomes-values">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="codeCLOs-values"
                                        id="codeCLOs-values">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="teachingStrategies-values"
                                        id=" teachingStrategies-values">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="assessmentMethods-values"
                                        id="assessmentMethods-values">
                                </td>
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
        <div class="card mt-3">
            <div class="card-header">
                <h5>Course Content</h5>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <table id="courseContent" class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%"> NO </th>
                                <th> List of Topics </th>
                                <th> Contact Hours </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr class="noData">
                                <td>1</td>
                                <td> Traditional classroom</td>
                                <td> 13:00 - 16:00 </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="1">Total</td>
                                <td>3 hours</td>
                            </tr>
                            {{-- <tr class="noData text-center">
                            <td colspan="3" >No topics were added</td>
                        </tr> --}}
                            <tr class="add-new-record courseContent">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="topic-courseContent"
                                        id="topic-courseContent">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="contactHours-courseContent"
                                        id="contactHours-courseContent">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h5>Students Assessment Activities</h5>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <table id="studentsAssessmentActivities" class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%"> NO </th>
                                <th> Assessment Activities </th>
                                <th> Assessment timing (in weeks) </th>
                                <th> Percentage of Total Assessment Score </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            {{-- <tr class="noData">
                            <td>1</td>
                            <td> Traditional classroom</td>
                            <td> 10% </td>
                        </tr> --}}
                            <tr class="noData text-center">
                                <td colspan="4">No assessments were added</td>
                            </tr>
                            <tr class="add-new-record studentsAssessmentActivities">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="assessmentActivity"
                                        id="assessmentActivity">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="assessmentTiming"
                                        id="assessmentTiming">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="assessmentScore"
                                        id="assessmentScore">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h5>Learning Resources and Facilities </h5>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <div class="col-12 d-flex justify-content-between">
                        <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                            <div class="input-group">
                                <span class="input-group-text">Essential References</span>
                                <input type="text" aria-label="level" class="form-control essentialReferences">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                            <div class="input-group">
                                <span class="input-group-text">Supportive References</span>
                                <input type="text" aria-label="level" class="form-control supportiveReferences">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                            <div class="input-group">
                                <span class="input-group-text">Electronic Materials</span>
                                <input type="text" aria-label="level" class="form-control electronicMaterials">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                            <div class="input-group">
                                <span class="input-group-text">Other Learning Materials</span>
                                <input type="text" aria-label="level" class="form-control otherLearningMaterials">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h5>Required Facilities and equipment</h5>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <table id="studentsAssessmentActivities" class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%"> NO </th>
                                <th> Items </th>
                                <th> Resources </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            {{-- <tr class="noData">
                            <td>1</td>
                            <td> Traditional classroom</td>
                            <td> 10% </td>
                        </tr> --}}
                            <tr class="noData text-center">
                                <td colspan="3">No resources were added</td>
                            </tr>
                            <tr class="add-new-record facilitiesEquipment">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="items" id="items">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="resources" id="resources">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h5>Assessment of Course Quality </h5>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <table id="assessmentCourseQualitys" class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%"> NO </th>
                                <th> Assessment Areas/Issues </th>
                                <th> Assessor </th>
                                <th> Assessment Methods </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            {{-- <tr class="noData">
                            <td>1</td>
                            <td> Traditional classroom</td>
                            <td> 10% </td>
                        </tr> --}}
                            <tr class="noData text-center">
                                <td colspan="4">No Assessments were added</td>
                            </tr>
                            <tr class="add-new-record facilitiesEquipment">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="assessmentAreas"
                                        id="assessmentAreas">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="Assessor" id="Assessor">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="assessmentMethods"
                                        id="assessmentMethods">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h5><b> Specification Approval </b></h5>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <div class="col-12 d-flex justify-content-between">
                        <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                            <div class="input-group">
                                <span class="input-group-text">COUNCIL / COMMITTEE</span>
                                <input type="text" aria-label="level" class="form-control councilOrCommitte">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                            <div class="input-group">
                                <span class="input-group-text">REFERENCE NO.</span>
                                <input type="text" aria-label="level" class="form-control referenceNumber">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                            <div class="input-group">
                                <span class="input-group-text">DATE</span>
                                <input type="text" aria-label="level" class="form-control date">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="d-flex flex-row pt-3 col-md-6 col-lg-4 col-sm-12">
                <button type="submit" id="formSubmition" class="btn btn-primary waves-effect waves-light">Add
                    Course</button>
            </div>
        </div>
    </form>


@endsection
