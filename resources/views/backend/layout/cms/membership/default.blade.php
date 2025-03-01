@extends('backend.app')

@section('title', 'Default Membership Article')

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
                                <textarea class="form-control @error('content') is-invalid @enderror" id="description" name="content"
                                    placeholder="Content Here...">{{ old('content') ?? ($article->content ?? '') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Content End --}}

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
    <script>
        $('#description').summernote({
            placeholder: 'Enter description...',
            tabsize: 2,
            height: 100
        });
    </script>
@endpush
