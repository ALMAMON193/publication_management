@extends('backend.app')

@section('title', 'Category')

@push('style')
@endpush

@section('content')

    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create Category</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="{{ route('admin.category.store') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name" class="form-label">Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" id="name" placeholder="Enter Name here...."
                                            value="{{ old('name') }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                                        <button type="reset" class="btn btn-outline-secondary"
                                            onclick="resetForm()">Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- /Account -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    
@endpush
