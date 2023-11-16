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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.repeater@1.2.1/dist/repeater.css">


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
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}" />
    </script>


@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms/create.js') }}"></script>
    <script src="{{ asset('assets/js/forms/form-render.min.js') }}"></script>
    <script src="{{ asset('assets/js/forms/form-builder.min.js') }}"></script>
    @if (request()->is('form/edit*'))
        <script defer src="{{ asset('assets/js/forms/edit.js') }}"></script>
    @endif
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
                <label for="categories" class="form-label">Form Type</label>
                <select class="js-example-basic-multiple" id="categories" multiple="multiple">
                </select>
            </div>

            <div class="col-md-2 col-sm-12 mb-4">
                <a href="/forms/add/category" class="btn btn-primary waves-effect waves-light">New Category</a>
            </div>
        </div>
        <div id="fb-editor" class="px-4 pt-0">

        </div>
        <div class="ms-4 col-lg-4 col-md-6 col-sm-12 mb-4">
            <button class="btn btn-primary waves-effect waves-light" data-bs-target="#uploadFileModel"
                data-bs-toggle="modal">Import Word document</button>
        </div>
    </div>
    <div class="card">
        <h5 class="card-header">WorkFlow</h5>
        <div class="card-body">
            <form class="form-repeater">
                <div data-repeater-list="group-a">
                    <div data-repeater-item>
                        <div class="row">
                            <div class="col-md-7 col-sm-12 mb-4 postion-relative" data-select2-id="45">
                                <label for="users" class="form-label">Users</label>
                                <select class="js-example-basic-multiple workflow-users">
                                    <option value="a">asd</option>
                                    <option value="a">sdfsdf</option>
                                </select>
                            </div>
                            <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                <button type="button" class="btn btn-label-danger mt-4 waves-effect" data-repeater-delete>
                                    <i class="ti ti-x ti-xs me-1"></i>
                                    <span class="align-middle">Delete</span>
                                </button>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="mb-0">
                    <button type="button" class="btn btn-primary waves-effect waves-light" data-repeater-create>
                        <i class="ti ti-plus me-1"></i>
                        <span class="align-middle">Add</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
    <div class="modal fade" id="uploadFileModel" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="mb-2">Upload a Word doc</h3>
                            </div>
                            <div class="row">
                                <div class="col-10 fv-plugins-icon-container d-flex justify-content-end">
                                    <input type="file" id="FileUploaded" name="FileUploaded" class="form-control">
                                </div>
                                <div class="col-2 fv-plugins-icon-container">
                                    <button id="proccessUploadedFile"
                                        class="dt-button create-new btn btn-primary">Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
