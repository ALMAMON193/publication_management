@extends('backend.app')

@section('title', 'Edit Membership')

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
                        <h4 class="card-title">Edit Membership</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="{{ route('admin.membership.update', $membership->id) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="document" class="form-label">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" id="name"
                                            value="{{ old('name') ?? ($membership->name ?? '') }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!--description-->
                                    <div class="form-group">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="3">{{ old('description') ?? ($membership->description ?? '') }}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- price-->
                                    <div class="form-group">
                                        <label for="price" class="form-label">Price <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('price') is-invalid @enderror"
                                            name="price" id="price"
                                            value="{{ old('price') ?? ($membership->price ?? '') }}">
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!--Duration Type-->
                                    <div class="form-group">
                                        <label for="duration_type" class="form-label">Duration Type <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('duration_type') is-invalid @enderror"
                                            id="duration_type" name="duration_type" onchange="calculateDurationInDays()">
                                            <option value="">Select duration type</option>
                                            <option value="weeks" @if (old('duration_type', $membership->duration_type) == 'weeks') selected @endif>Weeks
                                            </option>
                                            <option value="months" @if (old('duration_type', $membership->duration_type) == 'months') selected @endif>Months
                                            </option>
                                            <option value="years" @if (old('duration_type', $membership->duration_type) == 'years') selected @endif>Years
                                            </option>
                                        </select>
                                        @error('duration_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!--duration-->
                                    {{-- <div class="form-group">
                                        <label for="duration" class="form-label">Duration (in days) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('duration') is-invalid @enderror"
                                            name="duration" id="duration"
                                            value="{{ old('duration', $membership->duration ?? 0) }}"
                                            placeholder="Enter duration in days...">
                                        @error('duration')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <script>
                                        function calculateDurationInDays() {
                                            let duration = document.getElementById('duration');
                                            let durationType = document.getElementById('duration_type').value;
                                            switch (durationType) {
                                                case 'weeks':
                                                    duration.value = 7;
                                                    break;
                                                case 'months':
                                                    duration.value = 30;
                                                    break;
                                                case 'years':
                                                    duration.value = 365;
                                                    break;
                                                default:
                                                    duration.value = 0;
                                                    break;
                                            }
                                        }
                                    </script> --}}

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
        $('#description').summernote({
            placeholder: 'Enter description...',
            tabsize: 2,
            height: 100
        });
    </script>
@endpush
