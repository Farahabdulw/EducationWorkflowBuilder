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
        .dark-style tr[name="instructionalFramwork-group"],
        .dark-style tr[name="instructionalFramwork-group"]:hover {
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
    <script src="{{ asset('assets/js/courses/typeahead.js') }}"></script>


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
            <p class="text-muted lastModifed">New Course</p>
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
                    <input type="text" class="form-control" id="institutions" required>
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
                            <input type="number" aria-label="credit Hours" id="creditHours" min=0 class="form-control">
                            <span class="input-group-text tatorialHours">Tatorial Hours</span>
                            <input type="number" aria-label="tatorial Hours" id="tatorialHours" min=0 class="form-control">
                        </div>
                    </div>
                </div>

                <div class="mt-2 border rounded p-3">
                    <div class="col-12 d-lg-flex courseCategories">
                        <div class="col-lg-2 col-md-12 col-sm-12 pb-md-2 pb-sm-2">Course Categories</div>
                        <label class="col-lg-2 col-md-4 col-sm-6 pb-md-2 pb-sm-2 switch switch-lg">
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
                        <label class="col-lg-2 col-md-4 col-sm-6 pb-md-2 pb-sm-2 switch switch-lg">
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
                        <label class="col-lg-2 col-md-4 col-sm-6 pb-md-2 pb-sm-2 switch switch-lg">
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
                        <label class="col-lg-2 col-md-4 col-sm-6 pb-md-2 pb-sm-2 switch switch-lg">
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
                        <label class="col-lg-2 col-md-4 col-sm-6 pb-md-2 pb-sm-2 switch switch-lg">
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
                    <div class="col-12 pt-2 d-lg-flex">
                        <div class="col-lg-2 col-md-12 col-sm-12 pb-md-2 pb-sm-2">enrollment option</div>

                        <label class="col-lg-2 col-md-12 col-sm-12 pb-md-2 pb-sm-2 switch switch-lg">
                            <input type="checkbox" class="switch-input courseType" checked id="requiredCheckbox"
                                name="subject-stat">
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
                        <label class="col-lg-2 col-md-12 col-sm-12 pb-md-2 pb-sm-2 switch switch-lg">
                            <input type="checkbox" class="switch-input courseType " id="electiveCheckbox"
                                name="subject-stat">
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
                            <input type="text" aria-label="level" id="level" class="form-control coruseLevel">
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex flex-column pt-2">
                    <label for="description" class="form-label description">Course Description</label>
                    <textarea id="description" rows="3" class="form-control"
                        style="overflow: hidden; overflow-wrap: break-word; resize: none; text-align: start; height: 83px;"></textarea>
                </div>
                <div class="d-lg-flex justify-content-between">
                    <div class="col-lg-6 col-md-12 pe-1 col-sm-12 pt-2">
                        <table id="preRequirements" class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10%">No.</th>
                                    <th data-name="PreRequirment"> Course Pre-requirements </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <tr class="noData">
                                    <td class="text-center" colspan="3"> No pre-requirements were added</td>
                                </tr>
                                <tr class="preRequirements">
                                    <td>
                                        <button type="button" class="btn btn-label-primary add-new-record-btn">
                                            <i class="fa fa-add"></i>
                                        </button>
                                    </td>
                                    <td colspan="2">
                                        <input type="text" class="form-control preRequirements-inp"
                                            name="preRequirements-inp">
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
                                    <th data-name="CoRequirment"> Course Co-requisites</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <tr class="noData">
                                    <td class="text-center" colspan="3"> No co-requisites were added</td>
                                </tr>
                                <tr class="coRequisites">
                                    <td>
                                        <button type="button" class="btn btn-label-primary add-new-record-btn">
                                            <i class="fa fa-add"></i>
                                        </button>
                                    </td>
                                    <td colspan="2">
                                        <input type="text" class="form-control coRequisites-inp"
                                            name="coRequisites-inp">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-12">
                    <table id="courseMainObjective" class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%">NO</th>
                                <th data-name="mainObjective"> Course Main Objective </th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr class="noData">
                                <td class="text-center" colspan="3"> No main objectives were added</td>
                            </tr>
                            <tr class="courseMainObjective">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td colspan="2">
                                    <input type="text" class="form-control courseMainObjective-inp"
                                        name="courseMainObjective">
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
                                <th data-name="modeOfInstruction"> Mode of Instruction </th>
                                <th data-name="contactHour"> Contact Hours </th>
                                <th data-name="percentage"> Percentage </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr class="noData">
                                <td colspan="5" class="text-center"> No records were added</td>
                            </tr>
                            <tr class="teachingMode">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control teachingMode-inp" name="modeInstruction">
                                </td>
                                <td>
                                    <input type="text" class="form-control teachingMode-inp" name="contactHours">
                                </td>
                                <td>
                                    <input type="number" class="form-control teachingMode-inp" max=100 min=0
                                        name="percentage">
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
                                <th data-name="activity"> Activity </th>
                                <th data-name="contactHour"> Contact Hours </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr class="noData">
                                <td colspan="4" class="text-center"> No records were added</td>
                            </tr>
                            <tr class="contactHours">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control contactHours-inp" name="activity">
                                </td>
                                <td>
                                    <input type="text" class="form-control contactHours-inp" name="contactHours">
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
                                <th data-name="leaeningOutcome"> Course Learning Outcomes </th>
                                <th data-name="CLOcode"> Code of CLOs aligned with program </th>
                                <th data-name="teachingStrategie"> Teaching Strategies </th>
                                <th data-name="assessmentMethod"> Assessment Methods </th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr name="instructionalFramwork-group" data-name="knowledge" class="instructionalFramwork">
                                <td> 1.0 </td>
                                <td colspan="6">Knowledge and understanding</td>
                            </tr>

                            <tr class="instructionalFramwork" name="add-record">
                                <td>
                                    <button type="button" data-group = "1"
                                        class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control instructionalFramwork-inp"
                                        name="courseLearningOutcomes-knowledge">
                                </td>
                                <td>
                                    <input type="text" class="form-control instructionalFramwork-inp"
                                        name="codeCLOs-knowledge">
                                </td>
                                <td>
                                    <input type="text" class="form-control instructionalFramwork-inp"
                                        name="teachingStrategies-knowledge">
                                </td>
                                <td>
                                    <input type="text" class="form-control instructionalFramwork-inp"
                                        name="assessmentMethods-knowledge">
                                </td>
                            </tr>
                            <tr name="instructionalFramwork-group" data-name="skills" class="instructionalFramwork">
                                <td> 2.0 </td>
                                <td colspan="6">Skills</td>
                            </tr>
                            <tr class="instructionalFramwork" name="add-record">
                                <td>
                                    <button type="button" data-group = "2"
                                        class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control instructionalFramwork-inp"
                                        name="courseLearningOutcomes-skills">
                                </td>
                                <td>
                                    <input type="text" class="form-control instructionalFramwork-inp"
                                        name="codeCLOs-skills">
                                </td>
                                <td>
                                    <input type="text" class="form-control instructionalFramwork-inp"
                                        name="teachingStrategies-skills">
                                </td>
                                <td>
                                    <input type="text" class="form-control instructionalFramwork-inp"
                                        name="assessmentMethods-skills">
                                </td>
                            </tr>
                            <tr name="instructionalFramwork-group" data-name="values" class="instructionalFramwork">
                                <td> 3.0 </td>
                                <td colspan="6">Values, autonomy responsibility</td>
                            </tr>
                            <tr class="instructionalFramwork" name="add-record">
                                <td>
                                    <button type="button" data-group = "3"
                                        class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control instructionalFramwork-inp"
                                        name="courseLearningOutcomes-values">
                                </td>
                                <td>
                                    <input type="text" class="form-control instructionalFramwork-inp"
                                        name="codeCLOs-values">
                                </td>
                                <td>
                                    <input type="text" class="form-control instructionalFramwork-inp"
                                        name="teachingStrategies-values">
                                </td>
                                <td>
                                    <input type="text" class="form-control instructionalFramwork-inp"
                                        name="assessmentMethods-values">
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                                <th data-name="topic"> List of Topics </th>
                                <th data-name="contactHour"> Contact Hours </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">

                            <tr class="noData text-center">
                                <td colspan="4">No topics were added</td>
                            </tr>
                            <tr class="courseContent">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control courseContent-inp"
                                        name="topic-courseContent">
                                </td>
                                <td>
                                    <input type="text" class="form-control courseContent-inp"
                                        name="contactHours-courseContent">
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
                                <th data-name="assessmentActivity"> Assessment Activities </th>
                                <th data-name="assessmentTiming"> Assessment timing (in weeks) </th>
                                <th data-name="assessmentpercentage"> Percentage of Total Assessment Score </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">

                            <tr class="noData text-center">
                                <td colspan="5">No assessments were added</td>
                            </tr>

                            <tr class="studentsAssessmentActivities">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control studentsAssessmentActivities-inp"
                                        name="assessmentActivity">
                                </td>
                                <td>
                                    <input type="text" class="form-control studentsAssessmentActivities-inp"
                                        name="assessmentTiming">
                                </td>
                                <td>
                                    <input type="number" class="form-control studentsAssessmentActivities-inp"
                                        name="assessmentScore">
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
                                <input type="text" aria-label="essentialReferences"
                                    class="form-control essentialReferences">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                            <div class="input-group">
                                <span class="input-group-text">Supportive References</span>
                                <input type="text" aria-label="supportiveReferences"
                                    class="form-control supportiveReferences">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                            <div class="input-group">
                                <span class="input-group-text">Electronic Materials</span>
                                <input type="text" aria-label="electronicMaterials"
                                    class="form-control electronicMaterials">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                            <div class="input-group">
                                <span class="input-group-text">Other Learning Materials</span>
                                <input type="text" aria-label="otherLearningMaterials"
                                    class="form-control otherLearningMaterials">
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
                    <table id="facilitiesEquipment" class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%"> NO </th>
                                <th data-name="item"> Items </th>
                                <th data-name="resource"> Resources </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr class="noData text-center">
                                <td colspan="4">No resources were added</td>
                            </tr>
                            <tr class="facilitiesEquipment">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control facilitiesEquipment-inp" name="items">
                                </td>
                                <td>
                                    <input type="text" class="form-control facilitiesEquipment-inp" name="resources">
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
                                <th data-name="assessmentArea"> Assessment Areas/Issues </th>
                                <th data-name="assessor"> Assessor </th>
                                <th data-name="assessmentMethod"> Assessment Methods </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr class="noData text-center">
                                <td colspan="5">No Assessments were added</td>
                            </tr>
                            <tr class="assessmentCourseQualitys">
                                <td>
                                    <button type="button" class="btn btn-label-primary add-new-record-btn">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </td>
                                <td>
                                    <input type="text" class="form-control assessmentCourseQualitys-inp"
                                        name="assessmentAreas">
                                </td>
                                <td>
                                    <input type="text" class="form-control assessmentCourseQualitys-inp"
                                        name="Assessor">
                                </td>
                                <td>
                                    <input type="text" class="form-control assessmentCourseQualitys-inp"
                                        name="assessmentMethods">
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
                                <input type="text" aria-label="council Or Committe"
                                    class="form-control councilOrCommitte">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                            <div class="input-group">
                                <span class="input-group-text">REFERENCE NO.</span>
                                <input type="text" aria-label="referenceNumber" class="form-control referenceNumber">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                            <div class="input-group">
                                <span class="input-group-text">DATE</span>
                                <input type="date" aria-label="level" class="form-control date">
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
