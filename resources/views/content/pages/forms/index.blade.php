@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Users')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/forms/forms.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/docxtemplater/3.42.0/docxtemplater.js"></script>
    <script src="https://unpkg.com/pizzip@3.1.4/dist/pizzip.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.js"></script>
    <script src="https://unpkg.com/pizzip@3.1.4/dist/pizzip-utils.js"></script>
@endsection

@section('page-script')

    <script src="{{ asset('assets/js/forms/forms.js') }}"></script>
@endsection

@section('content')
    {{ Breadcrumbs::render('forms') }}

    <div class="card">
        <div class="card-header">
            <h5>Forms list</h5>
        </div>
        <div class=" text-nowrap mb-3">
            <table class="datatables-forms table">

                <thead class="table-light" id="forms-head">
                    <tr>
                        <th class="shrink"></th>
                        <th>TITILE</th>
                        <th>TYPE</th>
                        <th>DATE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
            </table>
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
    <!-- delete Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="mb-2">Confirm User Delete</h3>
                            </div>
                            <form id="deleteUserForm" class="col-6 col-sm-12">
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
