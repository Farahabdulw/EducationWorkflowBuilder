@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Users')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/forms/create.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}" />

@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms/create.js') }}"></script>
    <script src="{{ asset('assets/js/forms/form-render.min.js') }}"></script>
    <script src="{{ asset('assets/js/forms/form-builder.min.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Create a new Form</h5>
        </div>
        <div class="card-body row browser-default-validation">
            <div class="col-md-5 col-lg-5 col-sm-12">
                <label for="title" class="form-label">Form Title</label>
                <input type="title" class="form-control" id="title" placeholder="Vication" required="">
            </div>
            <div class="col-md-5 col-lg-5 col-sm-12">
                <label for="type" class="form-label">Form Type</label>
                <select class="form-select" id="type" required="">
                    <option value="Vacation">Vacation</option>
                    <option value="Survey">Survey</option>
                    <option value="Feedback">Feedback</option>
                </select>
            </div>
        </div>
        <div id="fb-editor" class="p-2"></div>
    </div>
@endsection
