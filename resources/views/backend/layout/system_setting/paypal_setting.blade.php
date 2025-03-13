@extends('backend.app')

@section('title', 'PayPal Settings')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">PayPal Settings</h4>
                        <p class="card-description">Configure your PayPal credentials.</p>
                        <div class="mt-4">

                            <form class="forms-sample" action="{{ route('admin.paypal-settings.update') }}" method="POST">
                                @csrf
                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-label">PayPal Sandbox Client ID</label>

                                        <input type="text"
                                            class="form-control @error('paypal_client_id') is-invalid @enderror"
                                            name="paypal_client_id" placeholder="Enter PayPal Sandbox Client ID"
                                            value="{{ env('PAYPAL_SANDBOX_CLIENT_ID') }}">
                                        @error('paypal_client_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-label">PayPal Sandbox Client Secret</label>

                                        <input type="text"
                                            class="form-control @error('paypal_secret') is-invalid @enderror"
                                            name="paypal_secret" placeholder="Enter PayPal Sandbox Client Secret"
                                            value="{{ env('PAYPAL_SANDBOX_CLIENT_SECRET') }}">
                                        @error('paypal_secret')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-label">PayPal Mode</label>
                                        <select class="form-control @error('paypal_mode') is-invalid @enderror"
                                            name="paypal_mode">
                                            <option value="sandbox" {{ env('PAYPAL_MODE') == 'sandbox' ? 'selected' : '' }}>
                                                Sandbox</option>
                                            <option value="live" {{ env('PAYPAL_MODE') == 'live' ? 'selected' : '' }}>Live
                                            </option>
                                        </select>
                                        @error('paypal_mode')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
