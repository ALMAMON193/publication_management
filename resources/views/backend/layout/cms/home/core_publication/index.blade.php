@extends('backend.app')

@section('title', 'Update Core Publication')

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
                        <h4 class="card-title">Update Core Publication</h4>
                        <form id="my-form" class="forms-sample"
                            action="{{ route('admin.cms.home.core.publication.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                {{-- Title start --}}
                                <div class="form-group mb-3">
                                    <label class="form-lable">Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" placeholder="Title Here..."
                                        value="{{ old('title') ?? ($publication->title ?? '') }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- Title End --}}

                                {{-- Background Image Start --}}
                                <div class="form-group mb-3">
                                    <label class="form-label">Image First</label>
                                    <input type="file"
                                        data-default-file="{{ !empty($publication->image) && file_exists(public_path($publication->image)) ? url($publication->image) : url('backend/images/image-not.png') }}"
                                        class="dropify form-control @error('image') is-invalid @enderror" name="image"
                                        id="image">
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- Background Image End --}}

                                {{-- Background Image Start --}}
                                <div class="form-group mb-3">
                                    <label class="form-label">Image Second</label>
                                    <input type="file"
                                        data-default-file="{{ !empty($publication->background) && file_exists(public_path($publication->background)) ? url($publication->background) : url('backend/images/image-not.png') }}"
                                        class="dropify form-control @error('background') is-invalid @enderror"
                                        name="background" id="background">
                                    @error('background')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- Background Image End --}}
                            </div>
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
@endpush
