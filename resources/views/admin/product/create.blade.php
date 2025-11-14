@extends('admin.layouts.app')

@section('contents')
<div class="container-xl">
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
              <textarea name="content" id="editor" ></textarea>
              <x-input-error :messages="$errors->get('content')" />
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
                <input type="text" class="form-control" name="" value="">
                <x-input-error :messages="$errors->get('store')" />
              </div>
            </div>

            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="text" class="form-control" name="" value="">
                <x-input-error :messages="$errors->get('store')" />
              </div>
            </div>

            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label">Special Price</label>
                <input type="text" class="form-control" name="" value="">
                <x-input-error :messages="$errors->get('store')" />
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">From date</label>
                <input type="text" class="form-control" name="" value="">
                <x-input-error :messages="$errors->get('store')" />
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">To Date</label>
                <input type="text" class="form-control" name="" value="">
                <x-input-error :messages="$errors->get('store')" />
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-check">
                    <input type="checkbox" class="form-check-input">
                    <span class="form-check-label">Manage Stock</span>
                  </label>
                </div>
              </div>

              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-label">Quantity</label>
                  <input type="text" class="form-control" name="" value="">
                  <x-input-error :messages="$errors->get('store')" />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Stock Status</h3>
                </div>
                <div class="card-body">
                  <div class="col-md-12">
                    <div class="mb-3">
                      <label class="form-check">
                        <input type="radio" name="radios" class="form-check-input" checked="">
                        <span class="form-check-label">In Stock</span>
                      </label>
                      <label class="form-check">
                        <input type="radio" name="radios" class="form-check-input" checked="">
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
                <option value="published">Published</option>
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
                <input class="form-check-input" type="checkbox">
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
          <div class="card-body">
            <div class="col-md-12">
              <div class="mb-3">
{{--                <select name="category" class="form-select" id="">--}}
{{--                  <option value="">Select a category</option>--}}
{{--                </select>--}}
{{--                <x-input-error :messages="$errors->get('category')" />--}}

                <!-- Listes Imbriquées -->
                <ul class="list-unstyled">
                  <!-- Categorie -->
                  @foreach($categories as $category)
                    <li>
                    <label for="" class="form-check category-wrapper">
                      <input type="checkbox" class="form-check-input category-check">
                      <span class="form-check-label">{{ $category->name }}</span>
                    </label>

                    <!-- Sous-Categorie -->
                    @if($category->children_nested && $category->children_nested->count() > 0)
                    <ul class="list-unstyled ms-4 mt-2">
                      @foreach($category->children_nested as $child)
                      <li>
                        <label for="" class="form-check category-wrapper">
                          <input type="checkbox" class="form-check-input category-check">
                          <span class="form-check-label">{{ $child->name }}</span>
                        </label>

                        <!--- Sous-Sous-Categorie -->
                        @if($child->children_nested && $child->children_nested->count() > 0)
                        <ul class="list-unstyled ms-4 mt-2">
                          @foreach($child->children_nested as $subChild)
                          <li>
                            <label for="" class="form-check category-wrapper">
                              <input type="checkbox" class="form-check-input category-check">
                              <span class="form-check-label">{{ $subChild->name }}</span>
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
                  <input class="form-check-input" type="checkbox">
                  <span class="form-check-label">Hot</span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" type="checkbox">
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
                <select name="tag" class="form-select select2" id="" multiple="multiple">
                  @foreach($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                  @endforeach
                </select>
                <x-input-error :messages="$errors->get('tag')" />
              </div>
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-body">
            <div class="col-md-12">
              <div class="mb-3 row">
                <button class="btn btn-primary mt-3" onclick="$('#roleForm').submit()">Create</button>
              </div>
            </div>
          </div>
        </div>

      </div>
  </div>
</div>
@endsection

@push('scripts')
  <script>
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
  </script>
@endpush
