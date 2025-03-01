@extends('backend.app')

@section('title', 'Preseding Council')

@push('style')
    <style>
        .ck-editor__editable_inline {
            min-height: 200px;
        }

        .dropify-wrapper {
            background-color: #f5f8fa;
            /* Light background */
            border: 2px dashed #007bff;
            /* Custom border */
            border-radius: 15px;
            /* Rounded corners */
            transition: all 0.3s ease;
        }

        .dropify-wrapper:hover {
            border-color: #0056b3;
            /* Darker border on hover */
            background-color: #e6f7ff;
            /* Change background on hover */
        }

        .dropify-wrapper .dropify-message {
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .dropify-wrapper .dropify-message p {
            font-size: 18px;
            /* Larger font */
            margin: 10px 0;
        }

        .dropify-wrapper .dropify-preview .dropify-render img {
            max-width: 100%;
            /* Responsive image */
            border-radius: 10px;
        }
    </style>
@endpush

@section('content')

    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Preseding Council</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="{{ route('admin.presiding_councils.update', $data->id) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" id="name" placeholder="Enter name here"
                                            value="{{ $data->name ?? '' }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="designation" class="form-label">Designation</label>
                                        <input type="text"
                                            class="form-control @error('designation') is-invalid @enderror"
                                            name="designation" id="designation" placeholder="Enter designation here"
                                            value="{{ $data->designation ?? '' }}">
                                        @error('designation')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file"
                                            class="dropify form-control @error('image') is-invalid @enderror" name="image"
                                            id="image"
                                            data-default-file="{{ $data->image ? asset($data->image) : '' }}">
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="bio" class="form-label">Bio</label>
                                        <textarea class="form-control @error('bio') is-invalid @enderror" name="bio" id="content">{{ $data->bio ?? '' }}</textarea>
                                        @error('bio')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="designation_type" class="form-label">Designation Type</label>
                                        <select class="form-control @error('designation_type') is-invalid @enderror"
                                            name="designation_type" id="designation_type" required>
                                            <option value="">Select Designation Type</option>
                                            <option value="Councilman"
                                                {{ (old('designation_type') == 'Councilman' ? 'selected' : ($data->designation_type ?? '') == 'Councilman') ? 'selected' : '' }}>
                                                Councilman
                                            </option>
                                            <option value="Vice Chairperson"
                                                {{ (old('designation_type') == 'Vice Chairperson' ? 'selected' : ($data->designation_type ?? '') == 'Vice Chairperson') ? 'selected' : '' }}>
                                                Vice Chairperson
                                            </option>
                                            <option value="Chairman"
                                                {{ (old('designation_type') == 'Chairman' ? 'selected' : ($data->designation_type ?? '') == 'Chairman') ? 'selected' : '' }}>
                                                Chairman
                                            </option>
                                        </select>
                                        @error('designation_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary me-2">Update</button>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ClassicEditor
                .create(document.querySelector('#content'), {
                    height: '500px'
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
@endpush
