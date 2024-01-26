@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Users')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/custome-form-style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" /> --}}


@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/forms/create.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/forms/view.css') }}">

@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

    {{-- <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script> --}}
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms/view.js?time =' . time()) }}"></script>
    <script src="{{ asset('assets/js/forms/form-render.min.js') }}"></script>
    <script src="{{ asset('assets/js/forms/control_plugins/mathematic.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
@endsection

@section('content')

    <nav aria-label="breadcrumb">
        {{ Breadcrumbs::render('view-form', $formId) }}
    </nav>
    <div class="card" id="savedForm">
        <div class="card-header">
            <h5>View Form</h5>
        </div>
        <div class="card-body row browser-default-validation d-flex align-items-end">

            <div class="col-md-5 col-sm-12 mb-4">
                <h2 for="title" class="form-titlex">{{ $form->name }}</h2>
            </div>

            <div class="col-md-5 col-sm-12 mb-4" data-select2-id="45">
                <h2 for="categories" class="form-label">Form Type</h2>
                @foreach ($form->categories as $category)
                    <span class="badge rounded-pill bg-label-primary">{{ $category->name }}</span>
                @endforeach
            </div>
        </div>
        <div id="fb-render" data-form="{{ $form->content }}"
            class="row row-bordered px-4 pb-5 pt-0">
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Form Workflows list</h5>
        </div>
        <div class="table-responsive text-nowrap mb-3">
            <table class="datatables-workflows table">
                <thead class="table-light" id="forms-head">
                    <tr>
                        <th>ID</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Affiliations</th>
                        <th>Status</th>
                        <th>auction</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="modal fade overflow-auto" id="view-workflow-progress" tabindex="-1" role="dialog"
        aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="modal-body">
                            <div class="card-header pb-3 d-flex justify-content-between">
                                <h5 class="card-title m-0">Forms Progress</h5>
                                <button id="activate-sortable" class="btn btn-primary">Change Order</button>
                            </div>

                            <div class="card-body">
                                <ul class="timeline pb-0 mb-0 ">

                                </ul>
                            </div>
                            <button id="save-order-changes" class="btn btn-primary d-none ">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
