@php
$configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'Users')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/cards-advance.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/users.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/users.js')}}"></script>
@endsection

@section('content')
<div class="card">
	<div class="card-header">
		<h5>Users list</h5>
	</div>
    <div class="table-responsive text-nowrap mb-3">
         <table class="datatables-users table">
         	
            <thead class="table-light" id="users-head">
                <tr>
                	<th class="shrink"></th>
                    <th>FULL NAME</th>
                    <th>EMAIL</th>
                    <th>Age</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="edit-user" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Edit User Information</h3>
                            <p class="text-muted">Updating user details will receive a privacy audit.</p>
                        </div>
                        <form id="editUserForm" class="row g-3 fv-plugins-bootstrap5 fv-plugins-framework" onsubmit="return false" novalidate="novalidate">
                            <input type="text" id="modalEditUserId" hidden name="id">
                            <div class="col-12 col-md-6 fv-plugins-icon-container">
                                <label class="form-label" for="modalEditUserFirstName">First Name</label>
                                <input type="text" id="modalEditUserFirstName" name="modalEditUserFirstName" class="form-control" placeholder="John">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="col-12 col-md-6 fv-plugins-icon-container">
                                <label class="form-label" for="modalEditUserLastName">Last Name</label>
                                <input type="text" id="modalEditUserLastName" name="modalEditUserLastName" class="form-control" placeholder="Doe">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserEmail">Email</label>
                                <input type="text" id="modalEditUserEmail" name="modalEditUserEmail" class="form-control" placeholder="example@domain.com">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditbirthdate">Birth Date</label>
                                <input type="date" id="modalEditbirthdate" class="form-control dob-picker flatpickr-input active"
                                    placeholder="YYYY-MM-DD">
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">Submit</button>
                                <button type="reset" class="btn btn-label-secondary waves-effect" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            </div>
                            <input type="hidden">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true" >
    <div class="modal-dialog">
     <div class="modal-content">
      <div class="modal-header">
       <h5 class="modal-title" id="exampleModalLabel">Confirmation Delete</h5>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
       <span aria-hidden="true">&times;</span>
       </button>
      </div>
      <div class="modal-body">
       <p>Are you sure you want to delete?</p>
     </div>
     <div class="modal-footer">
      <button type="button" class="btn btn-secondary" id="close-modal">No</button>
       <button type="button" class="btn btn-danger">Yes</button>
      </div>
     </div>
    </div>
   </div>
@endsection
