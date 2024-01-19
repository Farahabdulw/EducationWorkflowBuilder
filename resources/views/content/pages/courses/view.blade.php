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
    <script src="{{ asset('assets/js/courses/typeahead.js') }}"></script>


@endsection

@section('page-script')
    <script src="{{ asset('assets/js/courses/create.js?time=' . time()) }}"></script>
@endsection

@section('content')
    {{ Breadcrumbs::render('view-course', $course->id) }}

    <div class="d-flex justify-content-between">
        <div class="d-flex flex-column pt-3 pb-2 ">
            <h4 class="mb-0">
                <span class="text-muted fw-light">View Course</span>
            </h4>
            <p class="text-muted version">Version : 1</p>

        </div>
        <div class="d-flex flex-column align-items-end pt-3 pb-2">
            <p class="text-muted mb-0">Last Revision:</p>
            <p class="text-muted lastModifed">..</p>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5>Course Specification</h5>
        </div>
        <div class="card-body row-gap-md-1 row-gap-lg-0 row-gap-sm-0 row d-flex">
            <div class="col-md-6 col-lg-4 col-sm-12 pb-1">
                <label for="departments" class="form-label">Title :</label>
                <h3> {{ $course->title }}</h3>
            </div>

            <div class="col-md-6 col-lg-4 col-sm-12 pb-1">
                <label for="departments" class="form-label">Code:</label>
                <h3> {{ $course->code }}</h3>
            </div>

            <div class="col-md-6 col-lg-4 col-sm-12 pb-1">
                <label for="departments" class="form-label">Program:</label>

                <h3> {{ $course->program }}</h3>
            </div>

            <div class="col-md-6 col-lg-4 col-sm-12 pb-1">
                <label for="departments" class="form-label">Department:</label>
                <h3> {{ $course->department->name }}</h3>

            </div>

            <div class="col-md-6 col-lg-4 col-sm-12 pb-1">
                <label for="departments" class="form-label">College:</label>
                <h3> {{ $course->college->name }}</h3>
            </div>

            <div class="col-md-6 col-lg-4 col-sm-12 pb-1">
                <label for="departments" class="form-label">Institution:</label>
                <h3> {{ $course->institution }}</h3>
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
                        <span class="input-group-text creditHours">{{ $course->credit }}</span>
                        <span class="input-group-text tatorialHours">Tatorial Hours</span>
                        <span class="input-group-text tatorialHours">{{ $course->tatorial }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-2 border rounded p-3">
                <div class="col-12 d-lg-flex courseCategories">
                    <div class="col-lg-2 col-md-12 col-sm-12 pb-md-2 pb-sm-2">Course Categories</div>
                    @php
                        $type = json_decode($course->type, true);
                    @endphp
                    @foreach (['university', 'college', 'department', 'track', 'others'] as $category)
                        @php
                            $isChecked = isset($type[$category]) && $type[$category] === 'true';
                        @endphp
                        <label class="col-lg-2 col-md-4 col-sm-6 pb-md-2 pb-sm-2 switch switch-lg">
                            <span class="switch-label tf-icons ti ti-{{ $isChecked ? 'check' : 'x' }}"></span>
                            <span class="switch-label">{{ ucfirst($category) }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="col-12 pt-2 d-lg-flex">
                    <div class="col-lg-2 col-md-12 col-sm-12 pb-md-2 pb-sm-2">enrollment option</div>
                    @php
                        $enrollment = json_decode($course->enrollment, true);
                    @endphp
                    <label class="col-lg-2 col-md-12 col-sm-12 pb-md-2 pb-sm-2 switch switch-lg">
                        <span
                            class="switch-label">{{ isset($enrollment['required']) && $enrollment['required'] === 'true' ? 'Required' : 'Elective' }}</span>
                    </label>
                </div>
            </div>

            <div class="col-12 d-flex justify-content-between">
                <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                    <div class="input-group">
                        <span class="input-group-text">Course be Offered to</span>
                        <span class="input-group-text">{{ $course->level }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex flex-column pt-2">
                <label for="description" class="form-label description">Course Description</label>
                <textarea id="description" rows="3" class="form-control"
                    style="overflow: hidden; overflow-wrap: break-word; resize: none; text-align: start; height: 83px;">{{ $course->description }}</textarea>
            </div>
            <div class="d-lg-flex justify-content-between">
                <div class="col-lg-6 col-md-12 pe-1 col-sm-12 pt-2 table-responsive">
                    <table id="preRequirements" class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width:20%">No.</th>
                                <th data-name="PreRequirment"> Course Pre-requirements </th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($course->preRequisites as $index => $prerequisite)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $prerequisite['name'] }}</td>
                                </tr>
                            @empty
                                <tr class="noData">
                                    <td class="text-center" colspan="3">No prerequisites were added</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6 col-md-12 ps-1 col-sm-12 py-2 table-responsive">
                    <table id="coRequisites" class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width:20%">NO.</th>
                                <th data-name="CoRequirment"> Course Co-requisites</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($course->coRequisites as $index => $corequisite)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $corequisite['name'] }}</td>
                                </tr>
                            @empty
                                <tr class="noData">
                                    <td class="text-center" colspan="3">No co-requisites were added</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-12 table-responsive">
                <table id="courseMainObjective" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width:20%">NO</th>
                            <th data-name="mainObjective"> Course Main Objective </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                        @forelse ($course->mainObjective as $index => $mainObjective)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $mainObjective['name'] }}</td>
                            </tr>
                        @empty
                            <tr class="noData">
                                <td class="text-center" colspan="3">No main objectives were added</td>
                            </tr>
                        @endforelse
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
            <div class="col-12 table-responsive">
                <table id="teachingMode" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width:10%"> NO </th>
                            <th data-name="modeOfInstruction"> Mode of Instruction </th>
                            <th data-name="contactHour"> Contact Hours </th>
                            <th data-name="percentage"> Percentage </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($course->teachingMode as $index => $tMode)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $tMode['mode_of_instruction'] }}</td>
                                <td>{{ $tMode['contact_hours'] }}</td>
                                <td>{{ $tMode['percentage'] }}</td>
                            </tr>
                        @empty
                            <tr class="noData">
                                <td class="text-center" colspan="5">No records were added</td>
                            </tr>
                        @endforelse
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
            <div class="col-12 table-responsive">
                <table id="contactHours " class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width:10%"> NO </th>
                            <th data-name="activity"> Activity </th>
                            <th data-name="contactHour"> Contact Hours </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($course->contactHours as $index => $cHours)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $cHours['activity'] }}</td>
                                <td>{{ $cHours['hours'] }}</td>
                            </tr>
                        @empty
                            <tr class="noData">
                                <td class="text-center" colspan="4">No records were added</td>
                            </tr>
                        @endforelse
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
            <div class="col-12 table-responsive">
                <table id="instructionalFramwork" class="dt-row-grouping table dataTable dtr-column collapsed">
                    <thead>
                        <tr>
                            <th style="width:10%">#CLO </th>
                            <th data-name="leaeningOutcome"> Course Learning Outcomes </th>
                            <th data-name="CLOcode"> Code of CLOs aligned with program </th>
                            <th data-name="teachingStrategie"> Teaching Strategies </th>
                            <th data-name="assessmentMethod"> Assessment Methods </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <tr class="group">
                            <td colspan="5">Knowledge</td>
                        </tr>
                        @forelse ($course->knowledge as $index => $know)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $know['learning_outcome'] }}</td>
                                <td>{{ $know['CLO_code'] }}</td>
                                <td>{{ $know['teaching_strategies'] }}</td>
                                <td>{{ $know['assessment_methods'] }}</td>
                            </tr>
                        @empty
                            <tr class="noData">
                                <td class="text-center" colspan="5">No records were added</td>
                            </tr>
                        @endforelse
                        <tr class="group">
                            <td colspan="5">Skills</td>
                        </tr>
                    </tbody>
                    <tbody class="table-border-bottom-0">
                        @forelse ($course->skills as $index => $skill)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $skill['learning_outcome'] }}</td>
                                <td>{{ $skill['CLO_code'] }}</td>
                                <td>{{ $skill['teaching_strategies'] }}</td>
                                <td>{{ $skill['assessment_methods'] }}</td>
                            </tr>
                        @empty
                            <tr class="noData">
                                <td class="text-center" colspan="5">No records were added</td>
                            </tr>
                        @endforelse
                        <tr class="group">
                            <td colspan="5">Values</td>
                        </tr>
                    </tbody>
                    <tbody class="table-border-bottom-0">
                        @forelse ($course->values as $index => $value)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $value['learning_outcome'] }}</td>
                                <td>{{ $value['CLO_code'] }}</td>
                                <td>{{ $value['teaching_strategies'] }}</td>
                                <td>{{ $value['assessment_methods'] }}</td>
                            </tr>
                        @empty
                            <tr class="noData">
                                <td class="text-center" colspan="5">No records were added</td>
                            </tr>
                        @endforelse
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
            <div class="col-12 table-responsive">
                <table id="courseContent" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width:10%"> NO </th>
                            <th data-name="topic"> List of Topics </th>
                            <th data-name="contactHour"> Contact Hours </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($course->content as $index => $cont)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $cont['topic'] }}</td>
                                <td>{{ $cont['contact_hours'] }}</td>
                            </tr>
                        @empty
                            <tr class="noData">
                                <td class="text-center" colspan="3">No records were added</td>
                            </tr>
                        @endforelse
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
            <div class="col-12 table-responsive">
                <table id="studentsAssessmentActivities" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width:10%"> NO </th>
                            <th data-name="assessmentActivity"> Assessment Activities </th>
                            <th data-name="assessmentTiming"> Assessment timing (in weeks) </th>
                            <th data-name="assessmentpercentage"> Percentage of Total Assessment Score </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                        @forelse ($course->studentsAssessment as $index => $StdAssessment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $StdAssessment['assessment_activity'] }}</td>
                                <td>{{ $StdAssessment['assessment_timing'] }}</td>
                                <td>{{ $StdAssessment['percentage'] }}</td>
                            </tr>
                        @empty
                            <tr class="noData">
                                <td class="text-center" colspan="4">No records were added</td>
                            </tr>
                        @endforelse
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
                            <span class="input-group-text">{{ $course->essential_references }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-between">
                    <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                        <div class="input-group">
                            <span class="input-group-text">Supportive References</span>
                            <span class="input-group-text">{{ $course->supportive_references }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-between">
                    <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                        <div class="input-group">
                            <span class="input-group-text">Electronic Materials</span>
                            <span class="input-group-text">{{ $course->electronic_references }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-between">
                    <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                        <div class="input-group">
                            <span class="input-group-text">Other Learning Materials</span>
                            <span class="input-group-text">{{ $course->other_references }}</span>
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
            <div class="col-12 table-responsive">
                <table id="facilitiesEquipment" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width:10%"> NO </th>
                            <th data-name="item"> Items </th>
                            <th data-name="resource"> Resources </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($course->facilitiesAndEquipment as $index => $StdAssessment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $StdAssessment['items'] }}</td>
                                <td>{{ $StdAssessment['resource'] }}</td>
                            </tr>
                        @empty
                            <tr class="noData">
                                <td class="text-center" colspan="3">No records were added</td>
                            </tr>
                        @endforelse
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
            <div class="col-12 table-responsive">
                <table id="assessmentCourseQualitys" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width:10%"> NO </th>
                            <th data-name="assessmentArea"> Assessment Areas/Issues </th>
                            <th data-name="assessor"> Assessor </th>
                            <th data-name="assessmentMethod"> Assessment Methods </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($course->assessmentQuality as $index => $AssessmentQ)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $AssessmentQ['assessment_area'] }}</td>
                                <td>{{ $AssessmentQ['assessor'] }}</td>
                                <td>{{ $AssessmentQ['assessment_method'] }}</td>
                            </tr>
                        @empty
                            <tr class="noData">
                                <td class="text-center" colspan="4">No records were added</td>
                            </tr>
                        @endforelse
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
                            <span class="input-group-text">{{ $course->approved_by }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-between">
                    <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                        <div class="input-group">
                            <span class="input-group-text">REFERENCE NO.</span>
                            <span class="input-group-text">{{ $course->approval_number }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-between">
                    <div class=" col-md-7 col-lg-7 col-sm-12 pt-2">
                        <div class="input-group">
                            <span class="input-group-text">DATE</span>
                            <span class="input-group-text">{{ $course->approval_date }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="d-flex flex-row pt-3 col-md-6 col-lg-4 col-sm-12 gap-2">
            <a href="/course/export/{{ $course->id }}" class="btn btn-success waves-effect waves-light">Export</a>
            <a href="/course/edit/{{ $course->id }}" class="btn btn-primary waves-effect waves-light">Edit</a>
        </div>
    </div>


@endsection
