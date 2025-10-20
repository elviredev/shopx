@extends('vendor-dashboard.layouts.app')

@section('contents')
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Update Store Profile</h3>
      </div>
      <div class="card-body">
        <!--Formulaire de modification des infos du profil-->
        <form action="{{ route('vendor.store-profile.update', 1) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="" class="form-label">Logo</label>
                <x-input-image
                  class="ms-0" name="logo"
                  imageUploadId="image-upload"
                  imagePreviewId="image-preview"
                  imageLabelId="image-label"
                  :image="asset($store?->logo)"
                />
                <x-input-error :messages="$errors->get('logo')" />
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3">
                <label for="" class="form-label">Banner</label>
                <x-input-image
                  class="ms-0" name="banner"
                  imageUploadId="image-upload-two"
                  imagePreviewId="image-preview-two"
                  imageLabelId="image-label-two"
                  :image="asset($store?->banner)"
                />
                <x-input-error :messages="$errors->get('banner')" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label required">Name</label>
                <input type="text" class="form-control" name="name" value="{{ $store?->name }}">
                <x-input-error :messages="$errors->get('name')" />
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" name="phone" value="{{ $store?->phone }}">
                <x-input-error :messages="$errors->get('phone')" />
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" name="email" value="{{ $store?->email }}">
                <x-input-error :messages="$errors->get('email')" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label required">Address</label>
                <input type="text" class="form-control" name="address" value="{{ $store?->address }}">
                <x-input-error :messages="$errors->get('address')" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label required">Short Description</label>
                <textarea class="form-control" name="short_description">{{ $store?->short_description }}</textarea>
                <x-input-error :messages="$errors->get('short_description')" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label">Long Description</label>
                <textarea class="form-control" id="editor" name="long_description" >{{ $store?->long_description }}</textarea>
                <x-input-error :messages="$errors->get('long_description')" />
              </div>
            </div>

          </div>

          <button type="submit" class="btn btn-primary ms-2">Update</button>
        </form>
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

      $.uploadPreview({
        input_field: "#image-upload-two",
        preview_box: "#image-preview-two",
        label_field: "#image-label-two",
        label_default: "Choose File",
        label_selected: "Change File",
        no_label: false
      });
    });
  </script>
@endpush
