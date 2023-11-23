@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Users')

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
    <link rel="stylesheet" href="{{ asset('assets/css/user-add.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>


@endsection

@section('page-script')
    <script src="{{ asset('assets/js/userGroups/create.js') }}"></script>
    @if (request()->is('edit/users/groups*'))
        <script defer src="{{ asset('assets/js/userGroups/edit.js') }}"></script>
    @endif
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Create a new users group</h5>
        </div>
        {{-- Hidden input to store the Permissions in to send to the back-end  --}}

        <form id="add-form" class="card-body col d-flex flex-column gap-3 browser-default-validation">
            <input type="json" hidden id="permissions">
            <div class="d-flex flex-row gap-2 col-md-7 col-lg-7 col-sm-12">
                <div class="input-group">
                    <span class="input-group-text">Group name</span>
                    <input type="text" id="name" aria-label="First name" class="form-control" placeholder=""
                        required>
                </div>
            </div>

            <div class="col-md-7 col-sm-12 " data-select2-id="45">
                <label for="affiliation" class="form-label">Group Affiliation</label>
                <select class="js-example-basic-multiple" id="affiliation" multiple="multiple">

                </select>
            </div>
            <div class="col-md-7 col-sm-12 mb-4" data-select2-id="45">
                <label for="users" class="form-label">Groups Users</label>
                <select class="js-example-basic-multiple" id="users" multiple="multiple">

                </select>
            </div>

            <table class="w-100 permissions-table">
                {{-- <tr>
                        <th>Permissions</th>
                        <th>Users</th>
                        <th>Forms</th>
                        <th>Committees</th>
                        <th>Colleges</th>
                        <th>Departments</th>
                        <th>Centers</th>
                    </tr> --}}

                {{-- <tbody>
                    <tr>
                        <th scope="row">View</th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="users-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="forms-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="committees-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled disabled data-type="colleges-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="departments-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="centers-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Add</th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="users-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="forms-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="committees-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled disabled data-type="colleges-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="departments-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="centers-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">edit</th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="users-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="forms-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="committees-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="colleges-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="departments-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="centers-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">delete</th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="users-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span> </label>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="forms-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="committees-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="colleges-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="departments-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="centers-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                    </tr>
                </tbody> --}}
                <thead>
                    <tr>
                        <th scope="row">Permissions</th>
                        <th scope="row">View</th>
                        <th scope="row">Add</th>
                        <th scope="row">Edit</th>
                        <th scope="row">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Users</th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="users-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="users-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="users-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="users-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span> </label>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Forms</th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="forms-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="forms-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="forms-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="forms-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span> </label>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Groups</th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="groups-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="groups-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="groups-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-type="groups-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span> </label>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Committees</th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" disabled class="switch-input" data-type="committees-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="committees-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="committees-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="committees-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span> </label>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Offices</th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="offices-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="offices-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="offices-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="offices-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span> </label>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Colleges</th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="colleges-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="colleges-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="colleges-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="colleges-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span> </label>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Departments</th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="departments-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="departments-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="departments-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="departments-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span> </label>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Centers</th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="centers-view">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="centers-add">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="centers-edit">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" disabled data-type="centers-delete">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span> </label>
                            </label>
                        </td>
                    </tr>

                </tbody>
            </table>

            <div class="d-flex flex-row gap-2 col-md-7 col-lg-7 col-sm-12">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Create a users group</button>
            </div>
        </form>

    </div>
@endsection
