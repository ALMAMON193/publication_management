@extends('backend.app')

@section('title', 'Create Membership')

@push('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .form-lable {
            font-weight: bold;
        }
    </style>
@endpush

@section('content')

    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create Membership</h4>
                        <form action="{{ route('admin.membership.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="document" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" id="name" value="{{ old('name') }}" placeholder="Enter name...">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!--description-->
                            <div class="form-group">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!--price-->
                            <div class="form-group">
                                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    name="price" id="price" value="{{ old('price') }}" placeholder="Enter price...">
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!--duration-->
                            <div class="form-group">
                                <label for="duration" class="form-label">Duration (in days) <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror"
                                    name="duration" id="duration" value="{{ old('duration') }}"
                                    placeholder="Enter duration in days...">
                                @error('duration')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Please enter the duration in days (e.g., 7 for one
                                    week).</small>
                            </div>
                            <!--Duration Type-->
                            <div class="form-group">
                                <label for="duration_type" class="form-label">Duration Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('duration_type') is-invalid @enderror" id="duration_type"
                                    name="duration_type">
                                    <option value="">Select duration type</option>
                                    <option value="weeks" @if (old('duration_type') == 'weeks') selected @endif>Weeks</option>
                                    <option value="months" @if (old('duration_type') == 'months') selected @endif>Months</option>
                                    <option value="years" @if (old('duration_type') == 'years') selected @endif>Years</option>
                                </select>
                                @error('duration_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <button type="reset" class="btn btn-outline-secondary" onclick="resetForm()">Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Summernote on the content textarea
            $('#description').summernote({
                placeholder: 'Enter description...',
                height: 150,
                tabsize: 2,
            });
        });
    </script>
@endpush
