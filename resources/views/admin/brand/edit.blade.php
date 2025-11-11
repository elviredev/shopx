@extends('admin.layouts.app')

@section('contents')
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Update Brand</h3>
        <div class="card-actions">
          <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Back</a>
        </div>
      </div>
      <div class="card-body">
        <form id="roleForm" action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-md-3">
              <div class="mb-3">
                <label for="" class="form-label mb-2">Brand Logo</label>
                <x-input-image
                    class="ms-0" imagePreviewId="image-preview"
                    imageUploadId="image-upload" imageLabelId="image-label"
                    name="brand_logo" :image="asset($brand->image)"
                />
                <x-input-error :messages="$errors->get('brand_logo')" />
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label required">Name</label>
                <input type="text" class="form-control" name="name" value="{{ $brand->name }}" >
                <x-input-error :messages="$errors->get('name')" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="mb-3">
                <label for="status" class="form-check form-switch form-switch-3">
                  <input class="form-check-input" type="checkbox" @checked($brand->is_active) name="status" id="status">
                  <span class="form-check-label">Active</span>
                </label>
              </div>
            </div>
          </div>

        </form>
      </div>

      <div class="card-footer text-end">
        <button class="btn btn-primary mt-3" onclick="$('#roleForm').submit()">Update</button>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script type="text/javascript">
    $(document).ready(function() {
      $.uploadPreview({
        input_field: "#image-upload",   // Default: .image-upload
        preview_box: "#image-preview",  // Default: .image-preview
        label_field: "#image-label",    // Default: .image-label
        label_default: "Choose File",   // Default: Choose File
        label_selected: "Change File",  // Default: Change File
        no_label: false                 // Default: false
      });
    });
  </script>
@endpush