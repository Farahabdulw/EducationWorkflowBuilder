@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'New Requests')

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
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/requests/newRequests.js') }}"></script>
@endsection

@section('content')
    {{ Breadcrumbs::render('new-requests') }}

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

        <table class="datatables-new-requests table">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Categories</th>
                    <th style="width:10%">Auction</th>
                </tr>
            </thead>
        </table>
    </div>

@endsection
