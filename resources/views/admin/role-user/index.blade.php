@extends('admin.layouts.app')

@section('contents')
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">All Role Users</h3>
        <div class="card-actions">
          <a href="{{ route('admin.role-user.create') }}" class="btn btn-primary">Create User</a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-vcenter card-table">
            <thead>
            <tr>
              <th>N°</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th class="w-1"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($admins as $admin)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td>
                  @foreach($admin->getRoleNames() as $role)
                    <span class="badge bg-primary-lt">{{ $role }}</span>
                  @endforeach
                </td>
                <td>
                  @if(!$admin->hasRole('Super Admin'))
                    <div class="d-flex gap-2">
                      <a class="btn btn-sm btn-primary" href="{{ route('admin.role-user.edit', $admin) }}">Edit</a>
                      <a class="btn btn-sm btn-danger delete-item" href="{{ route('admin.role-user.destroy', $admin) }}">Delete</a>
                    </div>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center">No Roles</td>
              </tr>
            @endforelse
            </tbody>
          </table>
        </div>

        <div class="card-footer">
        </div>
      </div>
    </div>
  </div>
@endsection



