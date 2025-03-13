@extends('backend.app')

@section('title', 'Core Publications')

@push('style')
@endpush

@section('content')

    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create Core Publications</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="{{ route('admin.core_publication.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name" class="form-label">Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="title" id="title" placeholder="Enter Title here...."
                                            value="{{ old('title') }}">
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
                                            <!-- PDF Preview will be shown here -->
                                        </div>
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
    <script>
        document.getElementById('document').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type === 'application/pdf') {
                const fileReader = new FileReader();
                fileReader.onload = function() {
                    const pdfPreview = document.getElementById('pdf-preview');
                    pdfPreview.innerHTML =
                        `<iframe src="${fileReader.result}" width="100%" height="500px"></iframe>`;
                };
                fileReader.readAsDataURL(file);
            }
        });
    </script>
@endpush
