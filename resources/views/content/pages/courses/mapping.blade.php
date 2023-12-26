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
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/js/courses/typeahead.js') }}"></script>


@endsection

@section('page-script')
    <script src="{{ asset('assets/js/courses/mapping.js?time=' . time()) }}"></script>
    <script src="{{ asset('assets/js/courses/mappingTables.js?time=' . time()) }}"></script>
@endsection

@section('content')
    {{ Breadcrumbs::render('add-course') }}

    <div class="d-flex justify-content-between">
        <div class="d-flex flex-column pt-3 pb-2 ">
            <h4 class="mb-0">
                <span class="text-muted fw-light">Add a new Course Mapping</span>
            </h4>
        </div>
    </div>
    <div id="add-form">
        <div class="card">
            <div class="card-header">
                <h5>Course Specification</h5>
            </div>
            <div class="card-body row-gap-md-1 row-gap-lg-0 row-gap-sm-0 row d-flex">
                <form id='plosRepeater'>
                    <div data-repeater-list="courses">
                        <div data-repeater-item>
                            <div class="col-md-8 col-lg-7 col-sm-12 pb-1">
                                <label for="code" class="form-label">Course Code</label>
                                <input type="text" class="form-control code" name="code" required>
                            </div>

                            <div class="col-md-8 col-lg-7 col-sm-12 pb-1">
                                <label for="levels" class="form-label">Level</label>
                                <select class="form-select levels">
                                    <option value="">select an option</option>
                                    <option value="introduced">Introduced</option>
                                    <option value="practiced">Practiced</option>
                                    <option value="mastered">Mastered</option>
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <h6 class="text-muted">PLOS</h6>
                                <div class="nav-align-left nav-tabs-shadow shadow-none mb-4">
                                    <ul class="nav nav-tabs me-3" role="tablist">
                                        @for ($i = 1; $i <= 7; $i++)
                                            <li class="nav-item" role="presentation">
                                                <button type="button" class="nav-link {{ $i === 1 ? 'active' : '' }}"
                                                    role="tab" data-bs-toggle="tab"
                                                    data-bs-target="#course-plo-so-{{ $i }}"
                                                    aria-controls="course-plo-so-{{ $i }}"
                                                    aria-selected="{{ $i === 1 ? 'true' : 'false' }}">
                                                    PLO{{ $i }}/SO{{ $i }}
                                                </button>
                                            </li>
                                        @endfor
                                    </ul>
                                    <div class="tab-content shadow-none">
                                        @for ($i = 1; $i <= 7; $i++)
                                            <div class="tab-pane fade p-2 {{ $i === 1 ? 'active show' : '' }}"
                                                id="course-plo-so-{{ $i }}" role="tabpanel">

                                                <div class="table-responsive">
                                                    <table id="plo-so-{{ $i }}"
                                                        class="table table-striped overflow-x-auto">
                                                        <thead>
                                                            <tr>
                                                                <th> #CLO </th>
                                                                <th data-name="type"> Type </th>
                                                                <th data-name="description"> Description </th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-border-bottom-0">
                                                            <tr class="noData">
                                                                <td colspan="4" class="text-center"> No records were
                                                                    added</td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="col-md-8 col-lg-7 col-sm-12 pb-1">
                                                    <label for="" class="form-label">Type</label>
                                                    <select class="cloType form-select">
                                                        <option value="">select an option</option>
                                                        <option value="Knowledge and Understanding">Knowledge and
                                                            Understanding</option>
                                                        <option value="Skills">Skills</option>
                                                        <option value="Values">Values</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-8 col-lg-7 col-sm-12 pb-1">
                                                    <label for="description" class="form-label">CLO Description</label>
                                                    <input type="text" aria-label="CLO Description"
                                                        class="form-control description" required>
                                                </div>
                                                <button type="button"
                                                    class="btn btn-label-primary actionBtn add-new-record-btn">
                                                    <i class="fa fa-add"></i>
                                                </button>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                                <div class="d-flex flex-row pt-3 col-12 justify-content-end ">
                                    <button type="button" data-repeater-delete
                                        class="btn btn-danger waves-effect waves-light">Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex flex-row pt-3 col-12 justify-content-between ">
                            <button type="button" data-repeater-create class="btn btn-primary waves-effect waves-light">Add
                                Another Course
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12">
            <div class="d-flex flex-row pt-3 col-12 justify-content-between ">
                {{-- <button type="button" data-repeater-create class="btn btn-primary waves-effect waves-light">Add
                    Another Course </button> --}}
                <button type="button" id="export" class="btn btn-success waves-effect waves-light">Export
                </button>
            </div>
        </div>
    </div>

@endsection
