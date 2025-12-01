@extends('admin.layouts.app')

@push('styles')
  <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

  <style>
    .dropzone {
      border: 2px dashed #ccc;
      border-radius: 4px;
      padding: 20px;
      text-align: center;
      background: #f8f9fa;
      margin-bottom: 20px;
    }

    .dropzone.dz-drag-hover {
      border-color: #2196F3;
      background: #e3f2fd;
    }

    .image-preview-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 15px;
      margin-top: 20px;
    }

    .image-preview-item {
      position: relative;
      padding: 5px;
      border: 1px solid #ddd;
      border-radius: 4px;
      cursor: move;
    }

    .image-preview-item img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 4px;
    }

    .image-preview-item .remove-image {
      position: absolute;
      top: -10px;
      right: -10px;
      background: red;
      color: white;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      text-align: center;
      line-height: 24px;
      cursor: pointer;
    }

    .image-preview-loader {
      position: relative;
      width: 100%;
      height: 150px;
      background: #f8f9fa;
      border: 1px solid #ddd;
      border-radius: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: pulse 1.5s infinite;
    }

    .image-preview-loader::after {
      content: "Uploading...";
      color: #666;
    }

    @keyframes pulse {
      0% { opacity: 0.6; }
      50% { opacity: 1; }
      100% { opacity: 0.6; }
    }
  </style>
@endpush

@section('contents')
<div class="container-xl">

  <form action="" class="product-form">
    @csrf

    <div class="row">
      <div class="col-md-8">
        <div class="card mb-3">
  {{--          <div class="card-header">--}}
  {{--            <h3 class="card-title">Create Role</h3>--}}
  {{--            <div class="card-actions">--}}
  {{--              <a href="{{ route('admin.role.index') }}" class="btn btn-secondary">Back</a>--}}
  {{--            </div>--}}
  {{--          </div>--}}
          <div class="card-body">

            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label required">Name</label>
                <input type="text" class="form-control" name="name">
                <x-input-error :messages="$errors->get('name')" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label required">Slug</label>
                <input type="text" class="form-control" name="slug">
                <x-input-error :messages="$errors->get('slug')" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label required">Short description</label>
                <textarea name="short_description" id="short-editor" ></textarea>
                <x-input-error :messages="$errors->get('short_description')" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label required">Content</label>
                <textarea name="description" id="editor" ></textarea>
                <x-input-error :messages="$errors->get('description')" />
              </div>
            </div>

          </div>
        </div>

        <!--- Overview -->
        <div class="card">
          <div class="card-header">Overview</div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <div class="mb-3">
                  <label class="form-label">SKU</label>
                  <input type="text" class="form-control" name="sku" value="">
                  <x-input-error :messages="$errors->get('sku')" />
                </div>
              </div>

              <div class="col-md-4">
                <div class="mb-3">
                  <label class="form-label">Price</label>
                  <input type="text" class="form-control" name="price" value="">
                  <x-input-error :messages="$errors->get('price')" />
                </div>
              </div>

              <div class="col-md-4">
                <div class="mb-3">
                  <label class="form-label">Special Price</label>
                  <input type="text" class="form-control" name="special_price" value="">
                  <x-input-error :messages="$errors->get('special_price')" />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">From date</label>
                  <input type="text" class="form-control datepicker" name="from_date" value="" >
                  <x-input-error :messages="$errors->get('from_date')" />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">To Date</label>
                  <input type="text" class="form-control datepicker" name="to_date" value="">
                  <x-input-error :messages="$errors->get('to_date')" />
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-check">
                      <input type="checkbox" class="form-check-input manage-stock-check" name="manage_stock">
                      <span class="form-check-label">Manage Stock</span>
                    </label>
                  </div>
                </div>

                <div class="col-md-12 manage-stock d-none">
                  <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="text" class="form-control" name="quantity" value="">
                    <x-input-error :messages="$errors->get('quantity')" />
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="card mb-3">
                  <div class="card-header">
                    <h3 class="card-title">Stock Status</h3>
                  </div>
                  <div class="card-body">
                    <div class="col-md-12">
                      <div class="mb-3">
                        <label class="form-check">
                          <input type="radio" name="stock_status" class="form-check-input" checked="" value="in_stock">
                          <span class="form-check-label">In Stock</span>
                        </label>
                        <label class="form-check">
                          <input type="radio" name="stock_status" class="form-check-input" checked="" value="out_of_stock">
                          <span class="form-check-label">Out Stock</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

            </div>
          </div>
        </div>

      </div>

      <!--- Options -->
      <div class="col-md-4">
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">Status</h3>
        </div>
        <div class="card-body">
          <div class="col-md-12">
            <div class="mb-3">
              <select name="status" class="form-select" id="">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="draft">Draft</option>
                <option value="pending">Pending</option>
              </select>
              <x-input-error :messages="$errors->get('status')" />
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">Store</h3>
        </div>
        <div class="card-body">
          <div class="col-md-12">
            <div class="mb-3">
              <select name="store" class="form-select select2" id="">
                <option value="">Select a store</option>
                @foreach($stores as $store)
                  <option value="{{ $store->id }}">{{ $store->name }}</option>
                @endforeach
              </select>
              <x-input-error :messages="$errors->get('store')" />
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">Is Featured</h3>
        </div>
        <div class="card-body">
          <div class="col-md-12">
            <div class="mb-3">
              <label class="form-check form-switch form-switch-3">
                <input class="form-check-input" type="checkbox" name="is_featured">
                <span class="form-check-label">Enable</span>
              </label>
              <x-input-error :messages="$errors->get('is_featured')" />
            </div>
          </div>
        </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">
            <h3 class="card-title">Categories</h3>
          </div>
          <div class="card-body" style="height: 400px; overflow-y: scroll;">
            <div class="col-md-12">
              <div class="mb-3">
                <div class="mb-3">
                  <input type="text" class="form-control" id="category-search" placeholder="Search category">
                </div>

                <!-- Listes Imbriquées -->
                <ul class="list-unstyled" id="category-tree">
                  <!-- Categorie -->
                  @foreach($categories as $category)
                    <li>
                    <label for="" class="form-check category-wrapper">
                      <input type="checkbox" class="form-check-input category-check" name="categories[]" value="{{ $category->id }}">
                      <span class="form-check-label category-label">{{ $category->name }}</span>
                    </label>

                    <!-- Sous-Categorie -->
                    @if($category->children_nested && $category->children_nested->count() > 0)
                    <ul class="list-unstyled ms-4 mt-2">
                      @foreach($category->children_nested as $child)
                      <li>
                        <label for="" class="form-check category-wrapper">
                          <input type="checkbox" class="form-check-input category-check" name="categories[]" value="{{ $child->id }}">
                          <span class="form-check-label category-label">{{ $child->name }}</span>
                        </label>

                        <!--- Sous-Sous-Categorie -->
                        @if($child->children_nested && $child->children_nested->count() > 0)
                        <ul class="list-unstyled ms-4 mt-2">
                          @foreach($child->children_nested as $subChild)
                          <li>
                            <label for="" class="form-check category-wrapper">
                              <input type="checkbox" class="form-check-input category-check" name="categories[]" value="{{ $subChild->id }}">
                              <span class="form-check-label category-label">{{ $subChild->name }}</span>
                            </label>
                          </li>
                          @endforeach
                        </ul>
                        @endif
                      </li>
                      @endforeach
                    </ul>
                    @endif
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">
            <h3 class="card-title">Brand</h3>
          </div>
          <div class="card-body">
            <div class="col-md-12">
              <div class="mb-3">
                <select name="brand" class="form-select select2" id="">
                  <option value="">Select a brand</option>
                  @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                  @endforeach
                </select>
                <x-input-error :messages="$errors->get('brand')" />
              </div>
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">
            <h3 class="card-title">Label</h3>
          </div>
          <div class="card-body">
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-check">
                  <input class="form-check-input" type="checkbox" name="is_hot">
                  <span class="form-check-label">Hot</span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" type="checkbox" name="is_new">
                  <span class="form-check-label">New</span>
                </label>
                <x-input-error :messages="$errors->get('brand')" />
              </div>
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">
            <h3 class="card-title">Tags</h3>
          </div>
          <div class="card-body">
            <div class="col-md-12">
              <div class="mb-3">
                <select name="tags[]" class="form-select select2" id="" multiple="multiple">
                  @foreach($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                  @endforeach
                </select>
                <x-input-error :messages="$errors->get('tags')" />
              </div>
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-body">
            <div class="col-md-12">
              <div class="mb-3 row">
                <button type="submit" class="btn btn-primary mt-3">Create</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </form>

</div>
@endsection

@push('scripts')
{{-- CDN Dropzone --}}
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
{{-- CDN SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>

<script>
  // handle checkbox change event
  $(document).on('change', '.category-check', function() {
    // return true if checked, false if unchecked
    const isChecked = $(this).is(':checked');

    // get the parent "li" of the checkbox
    $(this).closest('li').find('input.category-check').each(function() {
      // check or uncheck the child checkbox based on the parent checkbox's state
      this.checked = isChecked;
      this.indeterminate = false;
    })

    function updateParents($input) {
      // get the parent "li" of the checkbox
      const $li = $input.closest('li').parent().closest('li');

      if ($li.length) {
        // get all child checkboxes within the parent "li"
        const $siblings = $li.find('> ul > li input.category-check');
        // nb de checkboxes cochées dans le parent "li"
        const $checkedCount = $siblings.filter(':checked').length;
        // select les autres parents dans le li principal
        const $parent = $li.find('> label > input.category-check');

        if ($checkedCount === 0) {
          // si aucun enfant n'est coché, le parent sera décoché
          $parent.prop('checked', false).prop('indeterminate', false);
        } else if($checkedCount === $siblings.length) {
          // si tous les enfants cochés, le parent sera coché
          $parent.prop('checked', true).prop('indeterminate', false);
        } else {
          $parent.prop('checked', false).prop('indeterminate', true);
        }

        updateParents($parent);
      }
    }

    updateParents($(this));
  })

  // Fonction de recherche d'une catégorie
  $('#category-search').on('input', function() {
    const query = $(this).val().toLowerCase();

    $('#category-tree li').each(function() {
      const label = $(this).find('> label > .category-label').text().toLowerCase();
      if (label.includes(query)) {
        $(this).removeClass('d-none');
        // show all ancestors of the matched category
        $(this).parents('li').removeClass('d-none');
      } else {
        $(this).addClass('d-none');
      }
    })

    // if query is empty, show all categories
    if (query === '') {
      $('#category-tree li').removeClass('d-none');
    }

  })

  // Manage stock checkbox - Quantity input is hidden when manage stock is unchecked
  $('.manage-stock-check').on('change', function() {
    if ($(this).is(':checked')) {
      $('.manage-stock').removeClass('d-none');
    } else {
      $('.manage-stock').addClass('d-none');
    }
  })

  // Submit Form
  $(function() {
    $('.product-form').on('submit', function (e) {
      e.preventDefault();

      let form = $(this);
      let data = new FormData(form[0]);

      // Ajax Request
      $.ajax({
        method: "POST",
        url: "{{ route('admin.products.store', ['type' => ':type']) }}".replace(':type', '{{ request()->type }}'),
        data: data,
        contentType: false,
        processData: false,
        success: function (response) {
          if(response.status === 'success') {
            window.location.href = response.redirect_url
          }
        },
        error: function (xhr, status, error) {
          console.log(xhr);
          let errors = xhr.responseJSON.errors;
          $.each(errors, function (key, value) {
            notyf.error(errors[key][0]);
          })
        }
      })

    })
  })

</script>
@endpush
