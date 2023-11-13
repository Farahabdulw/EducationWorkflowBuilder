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
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}" />
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>


@endsection

@section('page-script')
    <script src="{{ asset('assets/js/user-add.js') }}"></script>
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
                    <input type="text" id="fname" aria-label="First name" class="form-control" placeholder="John"
                        required>
                </div>
            </div>

            <div class="col-md-5 col-sm-12 mb-4" data-select2-id="45">
                <label for="affiliation" class="form-label">Group Affiliation*</label>
                <select class="js-example-basic-multiple" id="affiliation" multiple="multiple">
                    <optgroup label="Committees">
                        <option value="1">Academic Committee</option>
                        <option value="2">Sports Committee</option>
                        <option value="3">Cultural Committee</option>
                    </optgroup>
                    <optgroup label="Departments">
                        <option value="1">Computer Science</option>
                        <option value="2">Physics</option>
                        <option value="3">History</option>
                    </optgroup>
                    <optgroup label="Offices">
                        <option value="1">Administration</option>
                        <option value="2">Student Affairs</option>
                        <option value="3">Employee's Affairs</option>
                    </optgroup>
                    <optgroup label="Centers">
                        <option value="1">Research Center</option>
                        <option value="2">Language Center</option>
                        <option value="3">Health Center</option>
                    </optgroup>
                    <optgroup label="Colleges">
                        <option value="1">Engineering College</option>
                        <option value="2">Business College</option>
                        <option value="3">Arts College</option>
                    </optgroup>
                </select>
            </div>

            <table class="w-100 permissions-table" data-user=${user_id}>
                <thead>
                    <tr>
                        <th>Permissions</th>
                        <th>Users</th>
                        <th>Committees</th>
                        <th>Colleges</th>
                        <th>Departments</th>
                        <th>Centers</th>
                        <th>Forms</th>
                    </tr>
                </thead>
                <tbody>
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
                                <input type="checkbox" class="switch-input" data-type="committees-view">
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
                                <input type="checkbox" class="switch-input" data-type="colleges-view">
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
                                <input type="checkbox" class="switch-input" data-type="departments-view">
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
                                <input type="checkbox" class="switch-input" data-type="centers-view">
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
                                <input type="checkbox" class="switch-input" data-type="committees-add">
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
                                <input type="checkbox" class="switch-input" data-type="colleges-add">
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
                                <input type="checkbox" class="switch-input" data-type="departments-add">
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
                                <input type="checkbox" class="switch-input" data-type="centers-add">
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
                                <input type="checkbox" class="switch-input" data-type="committees-edit">
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
                                <input type="checkbox" class="switch-input" data-type="colleges-edit">
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
                                <input type="checkbox" class="switch-input" data-type="departments-edit">
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
                                <input type="checkbox" class="switch-input" data-type="centers-edit">
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
                                <input type="checkbox" class="switch-input" data-type="committees-delete">
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
                                <input type="checkbox" class="switch-input" data-type="colleges-delete">
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
                                <input type="checkbox" class="switch-input" data-type="departments-delete">
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
                                <input type="checkbox" class="switch-input" data-type="centers-delete">
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
                                </span>
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
