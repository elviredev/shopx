@extends('admin.settings.index')

@section('settings_contents')
  <div class="card-body">
    <h2 class="mb-4">General Settings</h2>

    <form action="{{ route('admin.settings.general') }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row g-3">
          <div class="col-md-12">
            <div class="form-label">Site Name</div>
            <input type="text" class="form-control" value="{{ config('settings.site_name') }}" name="site_name">
            <x-input-error :messages="$errors->get('site_name')" class="mt-2" />
          </div>
          <div class="col-md-6">
            <div class="form-label">Site Email</div>
            <input type="text" class="form-control" value="{{ config('settings.site_email') }}" name="site_email">
            <x-input-error :messages="$errors->get('site_email')" class="mt-2" />
          </div>
          <div class="col-md-6">
            <div class="form-label">Site Phone</div>
            <input type="text" class="form-control" value="{{ config('settings.site_phone') }}" name="site_phone">
            <x-input-error :messages="$errors->get('site_phone')" class="mt-2" />
          </div>
      </div>

      <div class="btn-list justify-content-end mt-5">
        <button type="submit" class="btn btn-primary btn-2"> Submit </button>
      </div>
    </form>

  </div>

@endsection
