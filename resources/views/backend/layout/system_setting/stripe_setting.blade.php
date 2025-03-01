@extends('backend.app')

@section('title', 'Stripe Settings')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Stripe Settings</h4>
                        <p class="card-description">Configure your Stripe credentials.</p>
                        <div class="mt-4">

                            <form class="forms-sample" action="{{ route('admin.stripe-settings.update') }}" method="POST">
                                @csrf
                                <!-- Stripe Secret -->
                                <div class="form-group">
                                    <label for="secret">Stripe Secret</label>
                                    <input type="text" name="secret" id="secret"
                                        class="form-control @error('secret') is-invalid @enderror"
                                        value="{{ old('secret', env('STRIPE_SECRET', '')) }}">
                                    @error('secret')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Stripe Key -->
                                <div class="form-group">
                                    <label for="key">Stripe Key</label>
                                    <input type="text" name="key" id="key"
                                        class="form-control @error('key') is-invalid @enderror"
                                        value="{{ old('key', env('STRIPE_KEY', '')) }}">
                                    @error('key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="webhook_secret">Stripe Webhook Secret</label>
                                    <input type="text" name="webhook_secret" id="webhook_secret"
                                        class="form-control @error('webhook_secret') is-invalid @enderror"
                                        value="{{ old('webhook_secret', env('STRIPE_WEBHOOK_SECRET', '')) }}">
                                    @error('webhook_secret')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
