@extends('backend.app')

@section('title', 'Contact view')

@push('style')
    <style>
        .custom-confirm-button {
            background-color: #ff5108 !important;
            /* Green */
            color: white !important;
        }

        .custom-cancel-button {
            background-color: #f72213 !important;
            /* Red */
            color: white !important;
        }

        /* Optional: Change button on hover */
        .custom-confirm-button:hover {
            background-color: #ff4e02;
            /* Darker green */
        }

        .custom-cancel-button:hover {
            background-color: #f51808;
            ;
            /* Darker red */
        }
    </style>
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/datatables.min.css') }}">
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Contact view</h4>
                        </div>
                        <hr>
                        <div class="col-12">
                            <p class="card-title"><strong>First Name:</strong> {{ $data->fname }}</p>
                            <p class="card-title"><strong>Last Name:</strong> {{ $data->lname }}</p>
                            <p class="card-title"><strong>Email:</strong> {{ $data->email }}</p>
                            <p class="card-text"><strong>Subject:</strong> {{ $data->subject }}</p>
                            <p class="card-text"><strong>Message:</strong> {{ $data->message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
