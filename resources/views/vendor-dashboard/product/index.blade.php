@extends('vendor-dashboard.layouts.app')

@section('contents')
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">All Products</h3>
        <div class="card-actions">
          <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              Create Product
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('vendor.products.create', ['type' => 'physical']) }}">Physical</a></li>
              <li><a class="dropdown-item" href="{{ route('vendor.products.create', ['type' => 'digital']) }}">Digital</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-vcenter card-table">
            <thead>
            <tr>
              <th>N°</th>
              <th>Image</th>
              <th>Product</th>
              <th>Price</th>
              <th>Stock Status</th>
              <th>Quantity</th>
              <th>Created At</th>
              <th>Approved</th>
              <th>Status</th>
              <th>Store</th>
              <th class="w-1"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  <img src="{{ asset($product->primaryImage?->path) }}" style="width: 50px" alt=""></td>
                <td>
                  <div>
                    @if($product->product_type == 'physical')
                    <a href="{{ route('vendor.products.edit', $product->id) }}">
                      {{ $product->name }}
                    </a>
                    @else
                      <a href="{{ route('vendor.digital-products.edit', $product->id) }}">
                        {{ $product->name }}
                      </a>
                    @endif
                  </div>
                  <small class="text-muted text-sm text-capitalize">{{ $product->product_type }}</small>
                </td>
                <td>
                  @if($product->primaryVariant)
                    @if($product->primaryVariant?->special_price > 0)
                      <div>{{ $product->primaryVariant?->special_price }}</div>
                      <div class="text-danger text-sm" style="text-decoration: line-through">
                        {{ $product->primaryVariant?->price }}
                      </div>
                    @else
                      <div>{{ $product->primaryVariant?->price }}</div>
                    @endif
                  @else
                    @if($product->special_price > 0)
                    <div>{{ $product->special_price }}</div>
                    <div class="text-danger text-sm" style="text-decoration: line-through">
                      {{ $product->price }}
                    </div>
                    @else
                      <div>{{ $product->price }}</div>
                    @endif
                  @endif
                </td>

                <td>
                  @if($product->primaryVariant)
                    @if($product->primaryVariant?->in_stock == 1)
                      <small class="text-success">In Stock</small>
                    @else
                      <small class="text-danger">Out of Stock</small>
                    @endif
                  @else
                    @if($product->in_stock == 1)
                      <small class="text-success">In Stock</small>
                    @else
                      <small class="text-danger">Out of Stock</small>
                    @endif
                  @endif
                </td>

                <td>
                  @if($product->primaryVariant)
                    @if($product->primaryVariant->manage_stock == 1)
                      {{ $product->primaryVariant->qty }}
                      @else
                        ∞
                    @endif
                  @else
                    @if($product->manage_stock == 'yes')
                      {{ $product->qty }}
                    @else
                      ∞
                    @endif
                  @endif
                </td>

                <td>
                  {{ date('Y-m-d', strtotime($product->created_at)) }}
                </td>

                <td>
                  @if($product->approved_status == 'pending')
                    <span class="badge bg-warning-lt">Pending</span>
                  @elseif($product->approved_status == 'approved')
                    <span class="badge bg-success-lt">Approved</span>
                  @elseif($product->approved_status == 'rejected')
                    <span class="badge bg-danger-lt">Rejected</span>
                  @endif
                </td>

                <td>
                  @if($product->status == 'active')
                    <span class="badge bg-success-lt">Active</span>
                  @elseif($product->status == 'inactive')
                    <span class="badge bg-secondary-lt">Inactive</span>
                  @elseif($product->status == 'pending')
                    <span class="badge bg-warning-lt">Pending</span>
                  @elseif($product->status == 'draft')
                    <span class="badge bg-secondary-lt">Draft</span>
                  @endif
                </td>

                <td>{{ $product->store->name }}</td>

                <td>
                  <div class="d-flex gap-2">
                    @if($product->product_type == 'physical')
                    <a class="btn btn-sm btn-primary p-1" href="{{ route('vendor.products.edit', $product->id) }}"><i class="ti ti-edit"></i></a>
                    @else
                      <a class="btn btn-sm btn-primary p-1" href="{{ route('vendor.digital-products.edit', $product->id) }}"><i class="ti ti-edit"></i></a>
                    @endif
                    <a class="btn btn-sm btn-danger p-1 delete-item" href="{{ route('vendor.products.destroy', $product) }}"><i class="ti ti-trash"></i></a>
                  </div>
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
