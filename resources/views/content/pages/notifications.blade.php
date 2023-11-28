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
    <link rel="stylesheet" href="{{ asset('assets/css/users.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

@endsection

@section('page-script')
    <script src="{{ asset('assets/js/users.js') }}"></script>
    <script src="{{ asset('assets/js/userGroups/index.js') }}"></script>
@endsection

@section('content')
    {{ Breadcrumbs::render('notifications') }}

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="head-label text-center">
                <h5 class="card-title mb-0">Notifications</h5>
            </div>
        </div>
        <div class="notifications-list">
            <ul class="list-group list-group-flush">
                {{-- <li class="list-group-item list-group-item-action dropdown-notifications-item">
                    <div class="d-flex">

                        <div class="flex-grow-1">
                            <h6 class="mb-1">Congratulation Lettie 🎉</h6>
                            <p class="mb-0">Won the monthly best seller gold badge</p>
                            <small class="text-muted">1h ago</small>
                        </div>
                        <div class="flex-shrink-0 dropdown-notifications-actions">
                            <a href="javascript:void(0)" class="dropdown-notifications-read">
                                <span
                                    class="badge badge-dot">
                                </span>
                            </a>
                        </div>
                    </div>
                </li> --}}
                @forelse (auth()->user()->notifications as $notification)
                    <li class="list-group-item list-group-item-action dropdown-notifications-item py-1">
                        <div class="d-flex p-2">
                            <button class="notification-anchor btn text-start" data-href="{{ $notification->data['url'] }}"
                                data-notfiid="{{ $notification->id }}">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $notification->data['header'] }}</h6>
                                    <p class="mb-0">{{ $notification->data['body'] }}</p>
                                    <small class="text-muted ms-1">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                @if (!$notification->read())
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        <a href="javascript:void(0)" class="dropdown-notifications-read">
                                            <span class="badge badge-dot"></span>
                                        </a>
                                        <a href="javascript:void(0)" class="dropdown-notifications-archive">
                                            <span class="ti ti-x"></span>
                                        </a>
                                    </div>
                                @endif
                            </button>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-muted py-1">No notifications</li>
                @endforelse



            </ul>
        </div>
    </div>

@endsection
