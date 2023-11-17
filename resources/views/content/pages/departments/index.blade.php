@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Departments')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/departments/index.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <table class="datatables-departments table">
            <thead class="table-light">
                <tr>
                    <th></th>
                    <th>DEPARTMENT NAME</th>
                    <th>COLLEGE NAME</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="edit-department" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="mb-2">Edit Department Information</h3>
                            </div>
                            <form id="editDepartmentForm" class="row g-3 fv-plugins-bootstrap5 fv-plugins-framework"
                                onsubmit="return false" novalidate="novalidate">
                                <input type="text" id="modalEditDepartmentId" hidden name="id">

                                <div class="col-12 col-md-6 fv-plugins-icon-container">
                                    <label class="form-label" for="modalEditDepartmentName">Department Name</label>
                                    <input type="text" id="modalEditDepartmentName" name="modalEditDepartmentName"
                                        class="form-control" placeholder="John">
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 fv-plugins-icon-container">
                                    <label for="modalEditDepartmentCollegeName" class="form-label">College Name</label>
                                    <select class="form-select" id="modalEditDepartmentCollegeName"
                                        aria-label="Default select example">
                                    </select>
                                </div>

                                <div class="col-12 fv-plugins-icon-container">
                                    <label for="modalEditDepartmentHeadDpName" class="form-label">Department's Head</label>
                                    <select class="form-select" id="modalEditDepartmentHeadDpName"
                                        aria-label="Default select example">
                                    </select>
                                </div>


                                <div class="col-12 fv-plugins-icon-container">
                                    <label class="form-label" for="modalEditDepartmentDescription">Description</label>
                                    <textarea class="form-control" id="modalEditDepartmentDescription" required></textarea>
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

                                <input type="hidden">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="mb-2">Confirm Department Delete</h3>
                            </div>
                            <form id="deleteDepartmentForm" class="col-6 col-sm-12">
                                <div class="col-12 text-center">
                                    <button type="submit"
                                        class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">Confirm</button>
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
