@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Requests')

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
    <link rel="stylesheet" href="{{ asset('assets/css/forms/view.css') }}">

@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/requests/index.js?time=' . time()) }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

@endsection

@section('content')
    {{ Breadcrumbs::render('requests') }}

    <div class="card">
        <div class="row p-4">
            <div class="col-12">
                <div class="row g-3">
                    <div class="col-12 col-sm-6 col-lg-4">
                        <label class="form-label">Committees:</label>
                        <select id="committees" class="select2-hidden-accessible select2" name="state">
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <label class="form-label">Offices:</label>
                        <select id="offices" class="select2-hidden-accessible select2" name="state">
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <label class="form-label">Colleges:</label>
                        <select id="colleges" class="select2-hidden-accessible select2" name="state">
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <label class="form-label">Departments:</label>
                        <select id="departments" class="select2-hidden-accessible select2" name="state">
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <label class="form-label">Centers:</label>
                        <select id="centers" class="select2-hidden-accessible select2" name="state">
                        </select>
                    </div>
                </div>
            </div>
        </div>


        <table class="datatables-requests table">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Form Title</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Affiliations</th>
                    <th>Status</th>
                    <th>auction</th>
                </tr>
            </thead>
        </table>
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
