@extends('backend.app')

@section('title', 'Free Membership Content')

@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        .ck-editor__editable[role="textbox"] {
            min-height: 150px;
        }

        .dropify-wrapper .dropify-message p {
            font-size: 35px !important;
        }

        #qb-toolbar-container {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-body">
                        <h4>Free Membership details</h4>
                        <div class="row">
                            {{ $errors }}
                            <div class="row">
                                <form method="POST" action="{{ route('admin.free-membership.store') }}">
                                    @csrf

                                    <!-- User Selection -->
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">Select User</label>
                                        <select class="form-control" name="user_id" id="user_id">
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Membership Selection -->
                                    <div class="mb-3">
                                        <label for="membership_id" class="form-label">Select Membership</label>
                                        <select class="form-control" name="membership_id" id="membership_id"
                                            onchange="updatePriceAndDates()">
                                            <option value="">Select Membership</option>
                                            @foreach ($memberships as $membership)
                                                <option value="{{ $membership->id }}" data-price="{{ $membership->price }}"
                                                    data-duration="{{ $membership->duration }}"
                                                    data-duration-type="{{ $membership->duration_type }}">
                                                    {{ $membership->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Amount (Auto filled based on membership) -->
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="text" class="form-control" name="amount" id="amount" readonly>
                                    </div>

                                    <!-- Hidden Start Date -->
                                    <input type="hidden" name="start_date" id="start_date">

                                    <!-- Hidden End Date -->
                                    <input type="hidden" name="end_date" id="end_date">

                                    <!-- Status -->
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Membership Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="pending">Pending</option>
                                            <option value="active">Active</option>
                                            <option value="expired">Expired</option>
                                        </select>
                                    </div>

                                    <!-- Payment Status -->
                                    <div class="mb-3">
                                        <label for="payment_status" class="form-label">Payment Status</label>
                                        <select class="form-control" name="payment_status" id="payment_status">
                                            <option value="pending">Pending</option>
                                            <option value="successful">Successful</option>
                                            <option value="failed">Failed</option>

                                        </select>
                                    </div>
                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>

                                <script>
                                    function updatePriceAndDates() {
                                        var membershipSelect = document.getElementById('membership_id');
                                        var selectedOption = membershipSelect.options[membershipSelect.selectedIndex];
                                        var price = selectedOption.getAttribute('data-price');
                                        var duration = selectedOption.getAttribute('data-duration');
                                        var durationType = selectedOption.getAttribute('data-duration-type');

                                        // Update the amount field
                                        document.getElementById('amount').value = price;

                                        // Set the start and end dates using JavaScript
                                        var startDate = new Date();
                                        var endDate = new Date();

                                        if (durationType === 'weeks') {
                                            endDate.setDate(startDate.getDate() + (duration * 7)); // Add weeks
                                        } else if (durationType === 'months') {
                                            endDate.setMonth(startDate.getMonth() + duration); // Add months
                                        } else if (durationType === 'years') {
                                            endDate.setFullYear(startDate.getFullYear() + duration); // Add years
                                        }

                                        // Set the hidden start and end date fields
                                        document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
                                        document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('script')
    <!-- JavaScript to Update Amount and Dates -->
@endpush
