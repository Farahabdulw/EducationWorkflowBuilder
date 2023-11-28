@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Users')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
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
        <form id="add-form" method="post" action=""
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

            <div class="col-md-7 col-lg-7 col-sm-12 p-b-0">
                <label for="departments" class="form-label">Department</label>
                <select id="departments" class="select2-hidden-accessible" name="state">
                </select>
            </div>

            <div class="d-flex flex-row gap-2 col-md-7 col-lg-7 col-sm-12">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Add Course</button>
            </div>
        </form>


    </div>
@endsection
