<?php
use App\Models\DonationPayment;
use App\Models\User;
$totalDonation = DonationPayment::where('status', 'successful')->sum('amount');
$user = User::all()->count();

?>

@extends('backend.app')

@section('title', 'Dashboard')

@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row">
                <div class="col-lg-8 mb-4 order-0">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-7">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Hello, {{ Auth::user()->first_name ?? '' }}
                                        {{ Auth::user()->last_name ?? '' }}! 🎉</h5>
                                    <p class="mb-4">
                                        Hope you are having a nice day!
                                    </p>

                                </div>
                            </div>
                            <div class="col-sm-5 text-center text-sm-left">
                                <div class="card-body pb-0 px-0 px-md-4">
                                    <img src="{{ asset('backend/assets/img/illustrations/man-with-laptop-light.png') }}"
                                        height="140" alt="View Badge User"
                                        data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                        data-app-light-img="illustrations/man-with-laptop-light.png" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 order-1">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between">
                                        <div class="avatar flex-shrink-0">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="fw-semibold d-block mb-1">Total User</span>
                                    <h3 class="card-title mb-2">{{ $user ?? '0' }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between">
                                        <div class="avatar flex-shrink-0">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-dollar-sign">
                                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3 3 0 0 1 0 6H6">
                                                </path>
                                            </svg>
                                        </div>

                                    </div>
                                    <span class="fw-semibold d-block mb-1">Total Donation Amount</span>
                                    <h3 class="card-title mb-2">{{ number_format($totalDonation ?? '0', 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="row">
        <!-- Card 1 -->
        <div class="col-lg-4 col-md-6 col-12 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <img src="{{ asset('frontend/images/group.png') }}" alt="user" class="rounded" />
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Total Group</span>
              <h3 class="card-title mb-2">0</h3>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="col-lg-4 col-md-6 col-12 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <img src="{{ asset('frontend/images/blog.png') }}" alt="Credit Card" class="rounded" />
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Total Group Posts</span>
              <h3 class="card-title mb-2">0</h3>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="col-lg-4 col-md-6 col-12 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <img src="{{ asset('frontend/images/forum.png') }}" alt="user" class="rounded" />
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Total Forum Posts</span>
              <h3 class="card-title mb-2">0</h3>
            </div>
          </div>
        </div>
      </div> --}}

        </div>
        <!-- / Content -->

        <div class="content-backdrop fade"></div>
    </div>
@endsection

@push('script')
@endpush
