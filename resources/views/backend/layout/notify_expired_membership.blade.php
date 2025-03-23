@extends('backend.app')

@section('title', 'Notify membership Expired')

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
                        <div style="display: flex;justify-content: space-between;align-items: center;">
                            <h4 class="card-title">Notify Membership Expired List</h4>
                        </div>
                        <div class="table-responsive mt-4 p-4">
                            <table class="table table-hover" id="data-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Membership Name</th>
                                    <th>User Email</th>
                                    <th>Remaining Days</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{-- Datatable --}}
    <script src="{{ asset('backend/vendors/datatable/js/datatables.min.js') }}"></script>
    {{-- sweet alart --}}
    <script src="{{ asset('backend/vendors/sweetalert/sweetalert2@11.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            var searchable = [];
            var selectable = [];
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });

            if (!$.fn.DataTable.isDataTable('#data-table')) {
                let dTable = $('#data-table').DataTable({
                    order: [],
                    lengthMenu: [
                        [10, 25, 50, 100, 200, 500, -1],
                        ["10", "25", "50", "100", "200", "500", "All"]
                    ],
                    pageLength: 10,
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    language: {
                        processing: `<div class="text-center">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>`,
                        lengthMenu: '_MENU_',
                        search: '',
                        searchPlaceholder: 'Search..'
                    },
                    scroller: {
                        loadingIndicator: false
                    },
                    pagingType: "full_numbers",
                    dom: "<'row justify-content-between table-topbar'<'col-md-2 col-sm-4 px-0'l><'col-md-2 col-sm-4 px-0'f>>tipr",
                    ajax: {
                        url: "{{ route('admin.notify.expired-membership') }}",
                        type: "get",
                    },
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'membership_name',
                            name: 'membership_name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'user_email',
                            name: 'user_email',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'remaining_days',
                            name: 'remaining_days',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });
            }
        });

        //clicked the button expired membership
        $(document).on('click', '.notify-btn', function () {
            var membershipId = $(this).data('id');
            // Show confirmation dialog before proceeding
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to notify this user about their membership?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, notify!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with AJAX request
                    $.ajax({
                        url: '/notify-membership/' + membershipId,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: (response) => {
                            console.log(response);

                            // Reload DataTable on success
                            $('#data-table').DataTable().ajax.reload();

                            // Show success message
                            if (response.success === true) {
                                Swal.fire({
                                    title: 'Notified!',
                                    text: 'The user has been notified successfully.',
                                    icon: 'success'
                                });
                            } else if (response.errors) {
                                // Show error message if there are validation errors
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.errors[0],
                                    icon: 'error'
                                });
                            } else {
                                // Show generic error message
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message || 'Something went wrong.',
                                    icon: 'error'
                                });
                            }
                        },
                        error: (error) => {
                            console.log(error);

                            // Show error message for AJAX failure
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to notify the user. Please try again.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
