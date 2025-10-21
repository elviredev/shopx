@extends('admin.layouts.app')

@section('contents')
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Create Role</h3>
        <div class="card-actions">
          <a href="{{ route('admin.role.index') }}" class="btn btn-secondary">Back</a>
        </div>
      </div>
      <div class="card-body">
        <form id="roleForm" action="{{ route('admin.role.store') }}" method="POST">
          @csrf

          <!--Role-->
          <div class="row">
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label required">Role Name</label>
                <input type="text" class="form-control"
                       name="role" placeholder=""
                       value=""
                >
                <x-input-error :messages="$errors->get('role')" />
              </div>
            </div>
          </div>
          <!--Permissions-->
          <div class="row">
            @foreach($permissions as $groupName => $permission)
              <div class="col-md-4 mb-3">
                <h3>{{ $groupName }}</h3>
                @foreach($permission as $item)
                  <label for="" class="form-check">
                    <input type="checkbox" class="form-check-input" value="{{ $item->name }}" name="permissions[]">
                    <span class="form-check-label">{{ $item->name }}</span>
                  </label>
                @endforeach
              </div>
            @endforeach
          </div>

        </form>
      </div>

      <div class="card-footer text-end">
        <button class="btn btn-primary mt-3" onclick="$('#roleForm').submit()">Create</button>
      </div>
      </div>
    </div>
@endsection

