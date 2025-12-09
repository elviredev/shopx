<div class="col-6 col-xxl-3 col-lg-4 col-md-6 col-sm-6">
  <div class="product-cart-wrap mb-30">
    <div class="product-img-action-wrap">
      <div class="product-img product-img-zoom">
        <a href="{{ route('products.show', $product->slug) }}">
          @foreach ($product->images as $key => $image)
            <img class="{{ $key == 0 ? 'default-img' : 'hover-img' }}" src="{{ asset($image->path) }}" alt="" />
          @endforeach
        </a>
      </div>
      <div class="product-action-1">
        <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i
          class="fi-rs-heart"></i></a>
        <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i
          class="fi-rs-shuffle"></i></a>
        <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal"
        data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
      </div>
      <div class="product-badges product-badges-position product-badges-mrg">
        @if ($product->is_hot)
          <span class="hot">Hot</span>
        @endif
          @if ($product->is_new)
            <span class="new ms-1">New</span>
          @endif
      </div>
    </div>
    <div class="product-content-wrap">
      <div class="product-category">
{{--        <a href="shop-grid-right.html">{{ $product->category->name }}</a>--}}
      </div>
      <h2><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h2>
      <div class="product-rate-cover">
        <div class="product-rate d-inline-block">
          <div class="product-rating" style="width: 90%"></div>
        </div>
        <span class="font-small ml-5 text-muted"> (4.0)</span>
      </div>
      <div>
        <span class="font-small text-muted">By
          <a href="vendor-details-1.html">{{ $product->store->name }}</a>
        </span>
      </div>
      <div class="product-card-bottom">
        <div class="product-price">
          @if($product->primaryVariant)
            @if($product->primaryVariant?->special_price > 0)
              <span>${{ $product->primaryVariant?->special_price }}</span>
              <span class="old-price">${{ $product->primaryVariant?->price }}</span>
            @else
              <span>${{ $product->primaryVariant?->price }}</span>
            @endif
          @else
            @if($product->special_price > 0)
              <span>${{ $product->special_price }}</span>
              <span class="old-price">${{ $product->price }}</span>
            @else
              <span>${{ $product->price }}</span>
            @endif
          @endif
        </div>
        <div class="add-cart">
          <a class="add" href="shop-cart.html"><i
            class="fi-rs-shopping-cart mr-5"></i>Add </a>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end product card-->