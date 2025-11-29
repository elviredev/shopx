<div class="accordion-item">
  <div class="accordion-header">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#variant-{{ $variant->id }}" aria-expanded="false">
      {{ $variant->name }}
      <div class="accordion-button-toggle">
        <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
          <path d="M6 9l6 6l6 -6"></path>
        </svg>
      </div>
    </button>
  </div>
  <div id="variant-{{ $variant->id }}" class="accordion-collapse collapse" data-bs-parent="#accordion-default" style="">
    <div class="accordion-body">
      <form action="" class="variant-form">
        @csrf
        <div class="row">
          <input type="hidden" name="variant_id" value="{{ $variant->id }}">

          <div class="col-md-12">
            <label for="" class="form-label">Sku</label>
            <input type="text" class="form-control" name="variant_sku" value="{{ $variant->sku }}" >
          </div>

          <div class="col-md-6 mt-3">
            <label for="" class="form-label">Price</label>
            <input type="text" class="form-control" name="variant_price" value="{{ $variant->price }}" >
          </div>

          <div class="col-md-6 mt-3">
            <label for="" class="form-label">Special Price</label>
            <input type="text" class="form-control" name="variant_special_price" value="{{ $variant->special_price }}" >
          </div>

          <div class="col-md-12 mt-3">
            <label class="form-check">
              <input class="form-check-input variant-manage-stock" type="checkbox" value="1" @checked($variant->manage_stock == 1) name="variant_manage_stock">
              <span class="form-check-label">Manage Stock</span>
            </label>

            <div class="variant-quantity" style="{{ $variant->manage_stock == 1 ? '' : 'display:none' }}">
              <label for="" class="form-label">Quantity</label>
              <input type="text" class="form-control" name="variant_qty" value="{{ $variant->qty }}" >
            </div>
          </div>

          <div class="col-md-12">
            <div class="card my-3">
              <div class="card-body">
                <label class="form-label">Stock Status</label>

                <div class="d-flex gap-2">
                  <label class="form-check">
                    <input type="radio" class="form-check-input" name="variant_stock_status" @checked($variant->in_stock == 1) value="in_stock" checked >
                    <span class="form-check-label">In Stock</span>
                  </label>

                  <label class="form-check">
                    <input type="radio" class="form-check-input" name="variant_stock_status" @checked($variant->in_stock == 0) value="out_of_stock" >
                    <span class="form-check-label">Out Stock</span>
                  </label>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-12 mt-3">
            <div class="d-flex gap-2">
              <label class="form-check form-switch form-switch-3">
                <input class="form-check-input" type="checkbox" @checked($variant->is_default == 1) value="1" name="variant_is_default">
                <span class="form-check-label">Is Default</span>
              </label>

              <label class="form-check form-switch form-switch-3">
                <input class="form-check-input" type="checkbox" @checked($variant->is_active == 1) value="1" name="variant_is_active">
                <span class="form-check-label">Is Active</span>
              </label>
            </div>
          </div>

        </div>

        <div class="mt-2">
          <button type="submit" class="btn btn-sm btn-success px-3 py-1 variant-save-btn">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>