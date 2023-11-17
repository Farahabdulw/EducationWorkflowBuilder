@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Form Categories')

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
    <style>
        div.dataTables_wrapper div.dataTables_filter {
            text-align: left !important;
        }
    </style>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}" />
    </script>

@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms/categories.js') }}"></script>
    <script src="{{ asset('assets/js/forms/form-render.min.js') }}"></script>
    <script src="{{ asset('assets/js/forms/form-builder.min.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Form Categories</h5>
        </div>
        <div class="card-body row browser-default-validation d-flex flex-colmun">
            <div class="col-md-8 col-sm-12">
                <form id="addCategoryForm">
                    <div class="d-flex flex-row gap-2 col-md-7 col-lg-7 col-sm-12 pb-3">
                        <div class="input-group">
                            <span class="input-group-text">Category Name*</span>
                            <input type="text" id="name" aria-label="Category Name" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-7 col-lg-7 col-sm-12 pb-3">
                        <label for="description" class="form-label">Description*</label>
                        <textarea class="form-control" id="description" placeholder="Category Description" required></textarea>
                    </div>
                    <div class="d-flex flex-row gap-2 col-md-7 col-lg-7 col-sm-12">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Add Category</button>
                    </div>
                </form>
            </div>

            <div class="col-md-4 col-sm-12">
                <table id="categoriesTable" class="table">
                    <thead>
                        <tr>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>


        </div>
        <div id="fb-editor" class="px-4 p-b-2 pt-0"></div>
    @endsection
