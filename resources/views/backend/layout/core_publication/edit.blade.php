@extends('backend.app')

@section('title', 'Core Publications')

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
                        <h4 class="card-title">Edit Core Publications</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="{{ route('admin.core_publication.update', $publication->id) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')


                                    <div class="form-group">
                                        <label for="name" class="form-label">Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="title" id="title" placeholder="Enter Title here...."
                                            value="{{ old('title', $publication->title) }}">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="document" class="form-label">Document <span
                                                class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('document') is-invalid @enderror"
                                            name="document" id="document" data-default-file="{{ old('document') }}"
                                            accept="application/pdf">
                                        @error('document')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <div id="pdf-preview" style="margin-top: 10px;">
                                            @if ($publication->document)
                                                <embed src="{{ asset($publication->document) }}" width="100%"
                                                    height="500px" />
                                            @endif
                                        </div>
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
    <script>
        document.getElementById('document').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type === 'application/pdf') {
                const fileReader = new FileReader();
                fileReader.onload = function() {
                    const pdfPreview = document.getElementById('pdf-preview');
                    pdfPreview.innerHTML = `<embed src="${fileReader.result}" width="100%" height="500px" />`;
                };
                fileReader.readAsDataURL(file);
            }
        });
    </script>
@endpush
