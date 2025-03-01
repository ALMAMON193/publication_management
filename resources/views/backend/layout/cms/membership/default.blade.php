@extends('backend.app')

@section('title', 'Default Membership Article')

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
                        <h4 class="card-title">Update Default membership Articles</h4>
                        <form id="my-form" class="forms-sample"
                            action="{{ route('admin.cms.default.membership.article.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            {{-- Title start --}}
                            <div class="form-group mb-3">
                                <label class="form-lable">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" placeholder="Title Here..."
                                    value="{{ old('title') ?? ($article->title ?? '') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Title End --}}

                            {{-- Content Start --}}
                            <div class="form-group mb-3">
                                <label class="form-lable">Content</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content"
                                    placeholder="Content Here...">{{ old('content') ?? ($article->content ?? '') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Content End --}}

                            {{-- Video Start --}}
                            <div class="form-group mb-3">
                                <label class="form-lable">Video</label>
                                <input type="file" class="form-control @error('video') is-invalid @enderror"
                                    id="video" name="video" placeholder="Video Here..." onchange="previewVideo(event)"
                                    accept="video/*">
                                @error('video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- Video Preview -->
                                <video id="videoPreview" controls
                                    style="display: none; margin-top: 10px; height: 300px; width: 100%">
                                    <source src="{{ old('video') ?? ($article->video_url ?? '') }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            {{-- Video End --}}

                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary" onclick="resetForm()">Cancel</button>
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
            $('#content').summernote({
                height: 150, // Set the height of the editor
                tabsize: 2, // Set the tab size
            });

            // Check if the video URL exists in old input or article object
            const videoSrc =
            "{{ old('video') ?? ($article->video_url ?? '') }}"; // This should point to the video URL if it exists
            const videoPreview = document.getElementById('videoPreview');

            if (videoSrc) {
                // Set the video source and show the preview
                videoPreview.src = videoSrc;
                videoPreview.style.display = 'block';
            }
        });

        function previewVideo(event) {
            const videoPreview = document.getElementById('videoPreview');
            const file = event.target.files[0];

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
