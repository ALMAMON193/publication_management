@extends('backend.app')

@section('title', 'Create Publications')

@push('style')
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
                        <h4 class="card-title">Create Publications</h4>
                        <form action="{{ route('admin.publication.store') }}" method="post" enctype="multipart/form-data"
                            id="yourFormId">
                            @csrf
                            {{-- Category --}}
                            <div class="form-group">
                                <label for="category_id" class="form-label">Category <span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('category_id') is-invalid @enderror" name="category_id"
                                    id="category_id">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Title -->
                            <div class="form-group">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    name="title" id="title" placeholder="Enter Title here...."
                                    value="{{ old('title') }}">
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                                    placeholder="Enter Description here....">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Image Upload with Dropify -->
                            <div class="form-group">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="dropify form-control @error('image') is-invalid @enderror"
                                    name="image" id="image" data-default-file="{{ old('image') }}">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Document Upload with Dropify and Preview -->
                            <div class="form-group">
                                <label for="document" class="form-label">Document</label>
                                <input type="file" class="form-control dropify @error('document') is-invalid @enderror"
                                    name="document" id="document" accept="application/pdf" data-height="150"
                                    onchange="previewDocument(event)" />
                                @error('document')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div id="pdfPreview" style="display: none; margin-top: 10px;">
                                    <iframe id="pdfFrame" width="100%" height="400px"></iframe>
                                </div>
                            </div>
                            <!-- Video Upload with Preview -->
                            <div class="form-group">
                                <label for="video_url" class="form-label">Video</label>
                                <input type="file" class="form-control @error('video_url') is-invalid @enderror"
                                    name="video_url" id="video_url" accept="video/*" onchange="previewVideo(event)">
                                @error('video_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <video id="videoPreview" controls
                                    style="display: none; margin-top: 10px; height: 300px;width: 100%"></video>
                            </div>
                            <!-- Submit Buttons -->
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
    </div>

@endsection

@push('script')
    <script>
        $('#description').summernote({
            placeholder: 'Enter description...',
            tabsize: 2,
            height: 100
        });
        // Document Preview
        function previewDocument(event) {
            const pdfPreview = document.getElementById('pdfPreview');
            const pdfFrame = document.getElementById('pdfFrame');
            const file = event.target.files[0];
            if (file && file.type === 'application/pdf') {
                const fileURL = URL.createObjectURL(file);
                pdfFrame.src = fileURL;
                pdfPreview.style.display = 'block';
            } else {
                pdfPreview.style.display = 'none';
            }
        }

        // Video Preview
        function previewVideo(event) {
            const videoPreview = document.getElementById('videoPreview');
            const file = event.target.files[0];
            if (file) {
                videoPreview.src = URL.createObjectURL(file);
                videoPreview.style.display = 'block';
            } else {
                videoPreview.style.display = 'none';
            }
        }
    </script>
@endpush
