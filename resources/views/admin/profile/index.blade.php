@extends('admin.layouts.app')

@section('contents')
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Update Profile</h3>
      </div>
      <div class="card-body">
        <!--Formulaire de modification des infos du profil-->
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-md-3">
              <div class="mb-3">
                <x-input-image imagePreviewId="image-preview" imageUploadId="image-upload" imageLabelId="image-label" name="avatar" :image="asset(auth('admin')->user()->avatar)" />
                <x-input-error :messages="$errors->get('avatar')" />
              </div>
            </div>

            <div class="col-md-9">
              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-label required">Name</label>
                  <input type="text" class="form-control"
                         name="name" placeholder=""
                         value="{{ auth('admin')->user()->name }}"
                  >
                  <x-input-error :messages="$errors->get('name')" />
                </div>
              </div>

              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-label required">Email</label>
                  <input type="email" class="form-control"
                         name="email" placeholder=""
                         value="{{ auth('admin')->user()->email }}"
                  >
                  <x-input-error :messages="$errors->get('email')" />
                </div>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary ms-2">Update Account</button>
        </form>
      </div>
    </div>

    <div class="card mt-5">
      <div class="card-header">
        <h3 class="card-title">Update Password</h3>
      </div>
      <div class="card-body">
        <!--Formulaire de modification du MDP-->
        <form method="POST" action="{{ route('admin.password.update') }}" >
          @csrf
          @method('PUT')

          <div class="row mt-30">
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label required">Current Password</label>
                <input type="password" class="form-control" name="current_password">
                <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
              </div>
            </div>

            <div class="col-md-12">
            <div class="mb-3">
              <label class="form-label required">New Password</label>
              <input type="password" class="form-control" name="password">
              <x-input-error :messages="$errors->get('password')" />
            </div>
            </div>

            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label required">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation">
                <x-input-error :messages="$errors->get('password_confirmation')" />
              </div>
            </div>

            <div class="col-md-12">
              <button type="submit" class="btn btn-primary">
                Update Password
              </button>
            </div>
          </div>
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
    });
  </script>
@endpush
