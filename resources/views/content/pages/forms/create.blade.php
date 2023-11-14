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
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />

@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/forms/create.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}" />
    </script>

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
        <div class="card-body row browser-default-validation d-flex align-items-end">

            <div class="col-md-5 col-sm-12 mb-4">
                <label for="title" class="form-label">Form Title</label>
                <input type="title" class="form-control" id="title" placeholder="Vication" required="">
            </div>

            <div class="col-md-5 col-sm-12 mb-4" data-select2-id="45">
                <label for="select2Multiple" class="form-label">Form Type</label>
                <select class="js-example-basic-multiple" id="categories" multiple="multiple">
                </select>
            </div>

            <div class="col-md-2 col-sm-12 mb-4">
                <a href="/forms/add/category" class="btn btn-primary waves-effect waves-light">New Category</a>
            </div>
        </div>

        <div id="fb-editor" class="px-4 p-b-4 pt-0"></div>
    @endsection
