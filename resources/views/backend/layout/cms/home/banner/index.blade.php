@extends('backend.app')

@section('title', 'Update Home Banner Content')

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
                        <h4 class="card-title">Update Home Banner Content</h4>
                        <form id="my-form" class="forms-sample" action="{{ route('admin.cms.home.banner.update') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- Title start --}}
                            <div class="form-group mb-3">
                                <label class="form-lable">Title</label>
                                <textarea type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                                    placeholder="title Here...">{{ old('title') ?? ($banner->title ?? '') }}</textarea>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>
                            {{-- Title End --}}

                            {{-- Content Start --}}
                            <div class="form-group mb-3">
                                <label class="form-lable">Content</label>
                                <textarea type="text" class="form-control @error('content') is-invalid @enderror" id="content" name="content"
                                    placeholder="Content Here...">{{ old('content') ?? ($banner->content ?? '') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Content End --}}
                            {{-- Background Image Start --}}
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Background Image</label>
                                    <input type="file"
                                        data-default-file="{{ !empty($banner->background) && file_exists(public_path($banner->background)) ? url($banner->background) : url('backend/images/image-not.png') }}"
                                        class="dropify form-control @error('background') is-invalid @enderror"
                                        name="background" id="background">
                                    @error('background')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- Background Image End --}}
                            {{--  Image Start --}}
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Image</label>
                                    <input type="file"
                                        data-default-file="{{ !empty($banner->image) && file_exists(public_path($banner->image)) ? url($banner->image) : url('backend/images/image-not.png') }}"
                                        class="dropify form-control @error('image') is-invalid @enderror" name="image"
                                        id="image">
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            {{--  Image End --}}
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary" onclick="resetForm()">Cancel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor5/41.3.1/ckeditor.min.js"></script>

    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#content'))
            .then(editor => {
                console.log('Editor was initialized', editor);
            })
            .catch(error => {
                console.error(error.stack);
            });

        $('.dropify').dropify();
    </script>
    <script>
        ClassicEditor
            .create(document.querySelector('#title'))
            .then(editor => {
                console.log('Editor was initialized', editor);
            })
            .catch(error => {
                console.error(error.stack);
            });
    </script>
@endpush
