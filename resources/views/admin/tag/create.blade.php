@extends('admin.layouts.app')

@section('contents')
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Create Tag</h3>
        <div class="card-actions">
          <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Back</a>
        </div>
      </div>
      <div class="card-body">
        <form id="roleForm" action="{{ route('admin.tags.store') }}" method="POST">
          @csrf

          <!--Role-->
          <div class="row">
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label required">Name</label>
                <input type="text" class="form-control"
                       name="name" placeholder=""
                       value=""
                >
                <x-input-error :messages="$errors->get('name')" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="mb-3">
                <label for="status" class="form-check form-switch form-switch-3">
                  <input class="form-check-input" type="checkbox" checked="" name="status" id="status">
                  <span class="form-check-label">Active</span>
                </label>
              </div>
            </div>
          </div>

        </form>
      </div>

      <div class="card-footer text-end">
        <button class="btn btn-primary mt-3" onclick="$('#roleForm').submit()">Create</button>
      </div>
    </div>
  </div>
@endsection

