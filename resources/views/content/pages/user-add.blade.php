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
<div class="card px-5 p-3">
     <div class="col d-flex flex-column">
    	 <div class="row d-flex flex-row">
    	 	<div class="col-md-6 col-lg-6 col-sm-12">
                  <label for="username" class="form-label">Name</label>
                  <input type="text" class="form-control" id="username" placeholder="John Doe">
		    </div>
    	 	<div class="col-md-6 col-lg-6 col-sm-12">
                  <label for="exampleFormControlInput1" class="form-label">Email address</label>
                  <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
		    </div>
	     </div>
     </div>
</div>
@endsection
