@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Users')


@section('page-style')
    <!-- Page -->
    <style>
        .sortable-clicked {
            border: 2px solid #685dd8;
        }

        .sortable-hover {
            animation: hoverEffect 3s;
        }

        @keyframes hoverEffect {
            from {
                box-shadow: 0 0 10px #685dd8;
            }

            to {
                box-shadow: none;
            }
        }

        .rendered-form>div {
            border: 1px solid darkgray;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        label:not([for^="checkbox"]) {
            display: block;
            padding-bottom: .5rem;
        }

        input[type="checkbox"]+label {
            display: inline-block;
            padding-left: .5rem;
        }


        input:not([type="checkbox"]):not([type="radio"]) {
            display: block;
            padding: .8rem .5rem;
            margin: .4rem 0;
            width: 100%;
            padding: 1rem;
            border: 1px solid rgb(65, 65, 65);
        }

        input[type="radio"]~label ,input[type="radio"] {
            display: inline !important;
            padding-left: .5rem;
        }

        li::marker {
            color: rgb(77, 20, 86);
        }
    </style>
@endsection

@section('vendor-script')

@endsection

@section('page-script')
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <script src="{{ asset('assets/js/forms/view.js?time =' . time()) }}"></script>
    <script src="{{ asset('assets/js/forms/form-render.min.js') }}"></script>
    <script src="{{ asset('assets/js/forms/control_plugins/mathematic.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
@endsection

@section('content')

    <nav aria-label="breadcrumb">
        {{ Breadcrumbs::render('view-form', $formId) }}
    </nav>
    @if ($redirectToPdf == true)
        <input type="hidden" id="formId" value="{{ $formId }}">
    @endif
    <div class="card" id="savedForm">
        <div class="card-body row browser-default-validation d-flex ">
            <div class="card-header">
                <h5>View Form</h5>
            </div>

            <div class="col-md-5 col-sm-12 mb-4">
                <h2 for="title" class="form-titlex">{{ $form->name }}</h2>
            </div>

            <div class="col-md-5 col-sm-12 mb-4">
                <h2 for="categories" class="form-label">Form Type</h2>
                @foreach ($form->categories as $category)
                    <span class="badge rounded-pill bg-label-primary">{{ $category->name }}</span>
                @endforeach
            </div>
        </div>
        <div id="fb-render" data-form="{{ $form->content }}"
            class="{{ $redirectToPdf ? 'redirect' : '' }} row row-bordered d-flex justify-content-center px-4 pb-5 pt-0">
        </div>
    </div>

@endsection
