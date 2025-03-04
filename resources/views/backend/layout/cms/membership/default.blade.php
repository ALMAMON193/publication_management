@extends('backend.app')

@section('title', 'Mebership Default Article content')

<style>
    .form-lable {
        font-weight: bold;
    }
</style>


@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Membership Default Article content</h4>
                        <form id="my-form" class="forms-sample"
                            action="{{ route('admin.cms.default.membership.article.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            {{-- title why chose list start --}}
                            <div class="form-group mb-3">
                                <label class="form-lable">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" placeholder="Title Here..."
                                    value="{{ old('title') ?? ($article->title ?? '') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- title why chose list end  --}}
                            <div class="form-group mb-3">
                                <label class="form-lable">Content</label>
                                <textarea type="text" class="form-control @error('content') is-invalid @enderror" id="content" name="content"
                                    placeholder="Content Here...">{{ old('content') ?? ($article->content ?? '') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
    <!-- CKEditor Script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: [
                    'heading', '|', 'bold', 'italic', 'link', 'bulletedList',
                    'numberedList', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'
                ],
                mediaEmbed: {
                    previewsInData: true, // Ensures the preview is shown inside CKEditor
                    removeProviders: ['dailymotion', 'facebook', 'instagram', 'twitter', 'spotify',
                        'googleMaps'] // Keep YouTube/Vimeo only
                }
            })
            .then(editor => {
                console.log('Editor initialized successfully', editor);
            })
            .catch(error => {
                console.error('There was an error initializing the editor:', error);
            });
    </script>
@endpush
