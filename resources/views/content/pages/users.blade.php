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
     <table class="datatables-users table">
     	
        <thead class="table-light" id="users-head">
            <tr>
            	<th class="shrink"></th>
                <th>USERNAME</th>
                <th>Age</th>
<!--                 <th>COMMITTEES</th> -->
<!--                 <th>COLLEGES</th> -->
<!--                 <th>DEPARMENTS</th> -->
<!--                 <th>CENTERS</th> -->
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>
@endsection
