@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Colleges')

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
    <script src="{{ asset('assets/js/colleges/colleges.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <table class="datatables-colleges table">
            <thead class="table-light">
                <tr>
                    <th></th>
                    <th>COLLEGE NAME</th>
                    <th>COMMITTE NAME</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="edit-college" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="mb-2">Edit College Information</h3>
                            </div>
                            <form id="editCollegeForm" class="row g-3 fv-plugins-bootstrap5 fv-plugins-framework"
                                onsubmit="return false" novalidate="novalidate">
                                <input type="text" id="modalEditCollegeId" hidden name="id">

                                <div class="col-12 col-md-6 fv-plugins-icon-container">
                                    <label class="form-label" for="modalEditCollegeName">College Name</label>
                                    <input type="text" id="modalEditCollegeName" name="modalEditCollegeName"
                                        class="form-control" placeholder="John">
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 fv-plugins-icon-container">
                                    <label for="modalEditCollegeCommiteeName" class="form-label">Commitee Name</label>
                                    <select class="form-select" id="modalEditCollegeCommiteeName"
                                        aria-label="Default select example">
                                    </select>
                                </div>


                                <div class="col-12 fv-plugins-icon-container">
                                    <label class="form-label" for="modalEditCollegeDescription">Description</label>
                                    <textarea class="form-control" id="modalEditCollegeDescription" required></textarea>
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
                                <h3 class="mb-2">Confirm College Delete</h3>
                            </div>
                            <form id="deleteCollegeForm" class="col-6 col-sm-12">
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
