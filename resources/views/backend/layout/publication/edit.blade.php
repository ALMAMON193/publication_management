@extends('backend.app')

@section('title', 'Edit Publications')

@push('style')
    <style>
        .form-lable {
            font-weight: bold;
        }

        .ck-editor__editable_inline {
            min-height: 200px;
        }

        .dropify-wrapper {
            background-color: #f5f8fa;
            border: 2px dashed #007bff;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .dropify-wrapper:hover {
            border-color: #0056b3;
            background-color: #e6f7ff;
        }

        .dropify-wrapper .dropify-message {
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .dropify-wrapper .dropify-message p {
            font-size: 18px;
            margin: 10px 0;
        }

        .dropify-wrapper .dropify-preview .dropify-render img {
            max-width: 100%;
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
                        <h4 class="card-title">Edit Publications</h4>
                        <div class="card mb-4">
                            <form action="{{ route('admin.publication.update', $publication->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="category" class="form-label">Category <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('category_id') is-invalid @enderror"
                                        name="category_id" id="category">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $category->id == old('category_id', $publication->category_id) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="title" class="form-label">Title <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        name="title" id="title" value="{{ old('title', $publication->title) }}"
                                        placeholder="Enter Title here....">
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                                        placeholder="Enter Description here....">{{ old('description', $publication->description) }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="dropify form-control @error('image') is-invalid @enderror"
                                        name="image" id="image"
                                        data-default-file="{{ $publication->image ? asset($publication->image) : '' }}">
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="document" class="form-label">Document <span class="text-danger">*</span>
                                    </label>
                                    <input type="file"
                                        class="dropify form-control @error('document') is-invalid @enderror" name="document"
                                        accept="application/pdf" id="document"
                                        data-default-file="{{ $publication->document ? asset($publication->document) : '' }}"
                                        onchange="previewDocument(event)">
                                    @error('document')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div style="display: {{ $publication->document ? 'block' : 'none' }}; margin-top: 10px;">
                                    <object data="{{ asset($publication->document) }}" type="application/pdf"
                                        width="100%" height="600">
                                        <p>Your web browser doesn't have a PDF plugin. Instead you can <a
                                                href="{{ asset($publication->document) }}">click here to download the
                                                PDF file.</a></p>
                                    </object>
                                </div>

                                <div class="form-group">
                                    <label for="video_url" class="form-label">Video</label>
                                    <input type="file" class="form-control @error('video_url') is-invalid @enderror"
                                        name="video_url" id="video_url" accept="video/*" onchange="previewVideo(event)">
                                    @error('video_url')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <video id="videoPreview" controls
                                    style="display: {{ $publication->video_url ? 'block' : 'none' }}; margin-top: 10px; height: 300px; width: 100%;">
                                    @if ($publication->video_url)
                                        <source src="{{ asset($publication->video_url) }}" type="video/mp4">
                                    @endif
                                    Your browser does not support the video tag.
                                </video>

                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary me-2">Update</button>
                                    <button type="reset" class="btn btn-outline-secondary" onclick="resetForm()">Cancel
                                    </button>
                                </div>
                            </form>
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
                .create(document.querySelector('#description'), {
                    height: '500px'
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
    <script>
        function previewDocument(event) {
            const file = event.target.files[0];
            const documentPreview = document.querySelector('#documentPreview');

            if (file && file.type.startsWith('application/pdf')) {
                const fileURL = URL.createObjectURL(file);
                documentPreview.src = fileURL;
                documentPreview.style.display = 'block';
            } else {
                documentPreview.style.display = 'none';
            }
        }

        function previewVideo(event) {
            const file = event.target.files[0];
            const videoPreview = document.getElementById('videoPreview');

            if (file && file.type.startsWith('video/')) {
                const fileURL = URL.createObjectURL(file);
                videoPreview.src = fileURL;
                videoPreview.style.display = 'block';
            } else {
                videoPreview.style.display = 'none';
            }
        }
    </script>
@endpush
