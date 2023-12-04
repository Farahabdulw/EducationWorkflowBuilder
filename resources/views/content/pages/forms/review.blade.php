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
    <link rel="stylesheet" href="{{ asset('assets/css/forms/review.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}" />
    </script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms/form-render.min.js') }}"></script>
    <script src="{{ asset('assets/js/forms/form-builder.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/forms/review.js') }}"></script>
    <script src="{{ asset('assets/js/forms/control_plugins/mathematic.js') }}"></script>

@endsection

@section('content')
    <input type="text" name="form" id="form_id" hidden value="{{ $form_id }}">
    <div class="row invoice-preview">
        <!-- Invoice -->
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card mb-4">
                <div class="card-body">
                    <div
                        class=" d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column m-sm-3 m-0">
                        <div class="mb-xl-0 mb-4">
                            <div class="d-flex svg-illustration mb-2 gap-2 align-items-center">
                                <span class="form-title fw-bold fs-4">

                                </span>
                            </div>
                            <span>Form Owner:</span><p class="mb-0 form-creator"></p>
                        </div>
                        <div>
                            <h4 class="fw-medium mb-2 form-number"></h4>
                            <div class="mb-0 pt-1">
                                <span>Date Issues:</span>
                                <span class="fw-medium form-issue-date">April 25, 2021</span>
                            </div>
                            {{-- <div class="pt-1">
                                <span>Date Due:</span>
                                <span class="fw-medium">May 25, 2021</span>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                @if ($returnReason)
                    <div class="card-body">
                        <h4>Returning Reason: </h4>
                        <p>{{ $returnReason }}</p>
                    </div>
                @endif
                <hr class="my-0">
                <div class="card-body">
                    <div id="fb-editor"></div>
                </div>
            </div>
        </div>
        <!-- / -->

        <!--  Actions -->
        @if (!$status)
            <div class="col-xl-3 col-md-4 col-12 invoice-actions pb-3">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-primary d-grid w-100 mb-2 waves-effect waves-light" id="approve">
                            <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                    class="ti ti-circle-check ti-xs me-2"></i>Approve</span>
                        </button>
                        <button class="btn btn-danger d-grid w-100 mb-2 waves-effect waves-light" id="reject">
                            <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                    class="ti ti-ban ti-xs me-2"></i>Reject</span>
                        </button>
                        @if (!$forwarded)
                            <button class="btn btn-label-secondary d-grid w-100 mb-2 waves-effect waves-light"
                                id="forward">
                                <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                        class="ti ti-send ti-xs me-2"></i>Forward</span>
                            </button>
                            @if (!$isFirstStep)
                                <button class="btn btn-label-secondary d-grid w-100 mb-2 waves-effect waves-light"
                                    id="return">
                                    <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                            class="ti ti-arrow-back-up ti-xs me-2"></i>Return</span>
                                </button>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        @endif
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title m-0">Forms Progress</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline pb-0 mb-0">
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Invoice Actions -->

    </div>
@endsection
