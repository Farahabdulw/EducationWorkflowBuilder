@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Offices')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

@endsection

@section('page-script')
    <script src="{{ asset('assets/js/offices/index.js') }}"></script>
@endsection

@section('content')
    {{ Breadcrumbs::render('offices') }}

    <div class="card">
        <div class="table-responsive">
            <table class="datatables-offices table">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>Office NAME</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="modal fade" id="edit-office" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="mb-2">Edit Office Information</h3>
                            </div>
                            <form id="editOfficeForm" class="row g-3 fv-plugins-bootstrap5 fv-plugins-framework"
                                novalidate="novalidate">
                                <input type="text" id="modalEditOfficeId" hidden name="id">

                                <div class="col-12 fv-plugins-icon-container">
                                    <label class="form-label" for="modalEditOfficeName">Office Name</label>
                                    <input type="text" id="modalEditOfficeName" name="modalEditOfficeName"
                                        class="form-control" placeholder="John">
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    </div>
                                </div>

                                <div class="col-12 fv-plugins-icon-container">
                                    <label class="form-label" for="modalEditOfficeDescription">Description</label>
                                    <textarea class="form-control" id="modalEditOfficeDescription" required></textarea>
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit"
                                        class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">Submit</button>
                                    <button type="reset" class="btn btn-label-secondary waves-effect"
                                        data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
