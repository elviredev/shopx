@extends('admin.layouts.app')

@section('contents')
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Products Brands</h3>
        <div class="card-actions">
          <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">Create Brand</a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-vcenter card-table">
            <thead>
            <tr>
              <th>N°</th>
              <th>Logo</th>
              <th>Name</th>
              <th>Status</th>
              <th class="w-1"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($brands as $brand)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  <img src="{{ asset($brand->image) }}" alt="{{ $brand->name }}" class="avatar avatar-sm">
                </td>
                <td>{{ $brand->name }}</td>
                <td>
                  @if($brand->is_active == 1)
                    <span class="badge bg-primary-lt">Active</span>
                  @else
                    <span class="badge bg-danger-lt">Inactive</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex gap-2 fs-2">
                    <a class="text-primary link-underline link-underline-opacity-0-hover" href="{{ route('admin.brands.edit', $brand) }}"><i class="ti ti-edit"></i></a>
                    <a class="text-danger link-underline link-underline-opacity-0-hover delete-item" href="{{ route('admin.brands.destroy', $brand) }}"><i class="ti ti-trash"></i></a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center">No Data Available</td>
              </tr>
            @endforelse
            </tbody>
          </table>
        </div>

        <div class="card-footer">
          {{ $brands->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection

