@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Review Form')

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
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms/form-render.min.js') }}"></script>
    <script src="{{ asset('assets/js/forms/control_plugins/mathematic.js') }}"></script>
    <script defer src="{{ asset('assets/js/forms/submit.js?time' . time()) }}"></script>

@endsection

@section('content')
    {{ Breadcrumbs::render('submit-form') }}
    <div class="card">
        <div class="card-header page-header">
            <h5>Submit Form</h5>
        </div>
        <div class="card-body row browser-default-validation d-flex align-items-end selects">
            <div class="col-md-5 col-sm-12 mb-4" data-select2-id="45">
                <label for="categories" class="form-label">Select a Center</label>
                <select class="select2" id="centers">
                    @forelse ($centers as $center)
                        <option value="{{ $center['id'] }}">{{ $center['name'] }}</option>
                    @empty
                        <option value="None">You dont have any centers</option>
                    @endforelse
                </select>
            </div>
        </div>

    </div>
    <div class="card collapse mt-3" id="formContent">
        <div class="card-header page-header">
            <h5 class="formName"></h5>
        </div>
        <div class="card-body row browser-default-validation d-flex align-items-end form-div">
            <div id="fb-editor"></div>
            <div class="col-6 col-sm-12">
                <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitForm">Submit
                    Form</button>
            </div>
        </div>
    </div>
@endsection
