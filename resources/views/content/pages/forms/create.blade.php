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
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />


@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/forms/create.css') }}">

@endsection

@section('vendor-script')
    {{-- <script src="https://unpkg.com/@yaireo/tagify@4.17.9/dist/tagify.min.js" /> --}}

    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>

    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}" />
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/docxtemplater/3.42.0/docxtemplater.js"></script>
    <script src="https://unpkg.com/pizzip@3.1.4/dist/pizzip.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.js"></script>
    <script src="https://unpkg.com/pizzip@3.1.4/dist/pizzip-utils.js"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms/create.js?time=' . time()) }}"></script>
    <script src="{{ asset('assets/js/forms/form-render.min.js') }}"></script>
    <script src="{{ asset('assets/js/forms/form-builder.min.js') }}"></script>
    <script src="{{ asset('assets/js/forms/control_plugins/mathematic.js') }}"></script>
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        {{ Breadcrumbs::render('add-form') }}
    </nav>
    <div class="card collapse show " id="savedForm">
        <div class="card-header page-header">
            <h5>Create a new Form</h5>
        </div>
        <div class="card-body row browser-default-validation d-flex align-items-end">

            <div class="col-md-5 col-sm-12 mb-4">
                <label for="title" class="form-label">Form Title</label>
                <input type="title" class="form-control" id="title" value = "{{ $form?? [] ? $form->name : '' }}"
                    placeholder="Vacation" required="">
            </div>

            <div class="col-md-5 col-sm-12 mb-4" data-select2-id="45">
                <label for="categories" class="form-label">Form Type</label>
                <select class="js-example-basic-multiple" data-categories='{!! $form?? [] ? json_encode($form->categories->pluck('id')) : '[]' !!}' id="categories"
                    multiple="multiple">
                </select>
            </div>

            <div class="col-md-2 col-sm-12 mb-4">
                <a href="/forms/add/category" class="btn btn-primary waves-effect waves-light">New Category</a>
            </div>

            <div class="col-md-7 col-sm-12 mb-4" id="uploadedFileContainer">
                <label for="formFile" class="form-label">Form File</label>
                <div id="uploadedFileMessage">{{ $form?? [] ? $form->file : 'No document uploaded' }}</div>
            </div>
        </div>
        <div id="fb-editor" data-formJSON='{!! $form?? [] ? json_encode($form->content) : '' !!}' class="px-4 pb-5 pt-0">

        </div>
    </div>

    <div class="card collapse" id="workflowSection">
        <div class="bg-lighter rounded p-3 position-relative mb-3">
            <div class="row">
                <div class="col-8">
                    <h4 class="mb-0 me-3 formTitle"></h4>
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <button class="btn btn-primary me-1 waves-effect waves-light" id="editSavedForm" type="button"
                        data-bs-toggle="collapse" data-bs-target="#savedForm" aria-expanded="true"
                        aria-controls="savedForm">
                        Edit
                    </button>
                </div>
            </div>

        </div>

        <h5 class="card-header">WorkFlow</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-6 col-sm-12 mb-4">
                    <label for="TagifyGroupsList" class="form-label">Groups List</label>
                    <input name='tags' id="TagifyGroupsList" class='form-control'>
                </div>
                <div class="col-6 col-sm-12 mb-4">
                    <label for="TagifyUserList" class="form-label">Users List</label>
                    <input name='tags' id="TagifyUserList" class='form-control'>
                </div>
                <div class="col-6 col-sm-12">
                    <button type="submit" class="btn btn-primary waves-effect waves-light" id="WorkflowUsers">Start the
                        Workflow</button>
                </div>
            </div>
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
