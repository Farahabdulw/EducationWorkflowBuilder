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
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/user-add.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}" />

@endsection

@section('page-script')
    <script src="{{ asset('assets/js/user-add.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Create a new user</h5>
        </div>
        {{-- Hidden input to store the Permissions in to send to the back-end  --}}

        <form id="add-form" class="card-body col d-flex flex-column gap-3 browser-default-validation">
            <input type="json" hidden id="permissions">
            <div class="d-flex flex-row gap-2 col-md-7 col-lg-7 col-sm-12">
                <div class="input-group">
                    <span class="input-group-text">First and last name*</span>
                    <input type="text" id="fname" aria-label="First name" class="form-control" placeholder="John"
                        required >
                    <input type="text" id="lname" aria-label="Last name" class="form-control" placeholder="Doe"
                        required >
                </div>
            </div>
            <div class="col-md-7 col-lg-7 col-sm-12">
                <label for="email" class="form-label">Email address*</label>
                <input type="email" class="form-control" id="email"
                    placeholder="name@example.com" required>
            </div>
            <div class="col-md-7 col-lg-7 col-sm-12">
                <label class="form-label" for="birthdate">Birth Date</label>
                <input type="date" id="birthdate" class="form-control dob-picker flatpickr-input active"
                    placeholder="YYYY-MM-DD" value="2000-01-01">
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
                    </tr>
                </tbody>
            </table>
            <div class="d-flex flex-row gap-2 col-md-7 col-lg-7 col-sm-12">
                <div class="col">
                    <label for="password1">Password*</label>
                    <div class="input-group input-group-merge">
                        <input type="password" class="form-control" id="password1"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password1" required >
                        <span class="input-group-text cursor-pointer" id="password1"><i class="ti ti-eye-off"></i></span>
                    </div>
                </div>

                <div class="col">
                    <label for="password2">Confirmation password*</label>
                    <div class="input-group input-group-merge">
                        <input type="password" class="form-control" id="password2"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password2" required>
                        <span class="input-group-text cursor-pointer" id="password2"><i class="ti ti-eye-off"></i></span>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-row gap-2 col-md-7 col-lg-7 col-sm-12">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Create user</button>
            </div>
        </form>

    </div>
@endsection
