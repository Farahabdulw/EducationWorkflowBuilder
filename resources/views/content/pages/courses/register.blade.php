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
    <script src="{{ asset('assets/js/courses/register.js?time=' . time()) }}"></script>
@endsection

@section('content')
    {{ Breadcrumbs::render('register') }}

    <div class="card">
        <div class="card-header">
            <h5>Register new students Portal</h5>
        </div>
        <div class="card-body row-gap-md-1 row-gap-lg-0 row-gap-sm-0 row d-flex">
            <form id="add-form" method="post">
                <div class="col-md-6 col-lg-4 col-sm-12 pb-1">
                    <label for="courses" class="form-label">Chosse a Course</label>
                    <select id="courses" class="select2-hidden-accessible">
                    </select>
                </div>

                <table id="students" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10%">No.</th>
                            <th data-name="name" style="width: 40%"> Student Name</th>
                            <th style="width: 40%" data-name="std_id"> Student ID</th>
                            <th style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <tr class="noData">
                            <td class="text-center" colspan="4"> No Studets were added</td>
                        </tr>
                        <tr class="students">
                            <td>
                                <button type="button" class="btn btn-label-primary add-new-record-btn">
                                    <i class="fa fa-add"></i>
                                </button>
                            </td>
                            <td>
                                <input type="text" class="form-control students-inp" name="name">
                            </td>
                            <td>
                                <input type="text" class="form-control students-inp" name="std_id">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex flex-row gap-2 col-md-7 col-lg-7 col-sm-12">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                </div>
            </form>
        </div>

    @endsection
