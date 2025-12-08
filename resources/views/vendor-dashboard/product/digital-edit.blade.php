@extends('vendor-dashboard.layouts.app')

@push('styles')
  {{-- CDN Dropzone Upload Fichier --}}
  <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
  {{-- CDN simonwep Color Picker --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css"/>

  <style>
  /* Dropzone Images Upload */
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

  /* Dropzone Files Upload */
  .dz-preview {
    position: relative;
    padding: 12px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    background: #f8f8f8;
    border-radius: 6px;
    text-align: left;
    font-family: sans-serif;
  }

  .dz-filename {
    font-weight: 600;
    font-size: 14px;
  }

  .dz-progress {
    height: 6px;
    background: #e4e4e4;
    margin-top: 6px;
    border-radius: 4px;
    overflow: hidden;
  }

  .dz-upload {
    background: #28a745;
    height: 100%;
    width: 0;
    transition: width 0.3s ease;
  }

  .dz-percentage {
    font-size: 12px;
    margin-top: 4px;
    color: #555;
  }

  .dz-remove {
    position: absolute;
    top: 6px;
    right: 10px;
    font-size: 18px;
    color: #dc3545;
    cursor: pointer;
  }

  .dz-remove:hover {
    color: #a71d2a;
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
            {{-- <div class="card-header">--}}
            {{-- <h3 class="card-title">Create Role</h3>--}}
            {{-- <div class="card-actions">--}}
            {{-- <a href="{{ route('vendor.role.index') }}" class="btn btn-secondary">Back</a>--}}
            {{-- </div>--}}
            {{-- </div>--}}
            <div class="card-body">

              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-label required">Name</label>
                  <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}">
                  <x-input-error :messages="$errors->get('name')" />
                </div>
              </div>

              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-label required">Slug</label>
                  <input type="text" class="form-control" id="slug" name="slug" value="{{ $product->slug }}">
                  <x-input-error :messages="$errors->get('slug')" />
                </div>
              </div>

              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-label required">Short description</label>
                  <div id="short-editor-wrapper" style="display:none;">
                    <textarea name="short_description" id="short-editor"></textarea>
                  </div>
                  {{-- <textarea name="short_description" id="short-editor" >{!! $product->short_description !!}</textarea>--}}
                  <x-input-error :messages="$errors->get('short_description')" />
                </div>
              </div>

              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-label required">Content</label>
                  <div id="editor-wrapper" style="display:none;">
                    <textarea name="description" id="editor"></textarea>
                  </div>
                  {{-- <textarea name="description" id="editor" >{!! $product->description !!}</textarea>--}}
                  <x-input-error :messages="$errors->get('description')" />
                </div>
              </div>

            </div>
          </div>

          <!--- Overview -->
          <div class="card">
            <div class="disabled-placeholder" style="{{ count($product->attributes) ? '' : 'display: none' }}"></div>
            <div class="card-header">Overview</div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="sku" value="{{ $product->sku }}">
                    <x-input-error :messages="$errors->get('sku')" />
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="text" class="form-control" name="price" value="{{ $product->price }}">
                    <x-input-error :messages="$errors->get('price')" />
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label">Special Price</label>
                    <input type="text" class="form-control" name="special_price" value="{{ $product->special_price }}">
                    <x-input-error :messages="$errors->get('special_price')" />
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">From date</label>
                    <input type="text" class="form-control datepicker" name="from_date" value="{{ $product->special_price_start }}" >
                    <x-input-error :messages="$errors->get('from_date')" />
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">To Date</label>
                    <input type="text" class="form-control datepicker" name="to_date" value="{{ $product->special_price_end }}">
                    <x-input-error :messages="$errors->get('to_date')" />
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="mb-3">
                      <label class="form-check">
                        <input type="checkbox" class="form-check-input manage-stock-check" name="manage_stock" @checked($product->manage_stock == 'yes')>
                        <span class="form-check-label">Manage Stock</span>
                      </label>
                    </div>
                  </div>

                  <div class="col-md-12 manage-stock {{ $product->manage_stock == 'yes' ? '' : 'd-none' }}">
                    <div class="mb-3">
                      <label class="form-label">Quantity</label>
                      <input type="text" class="form-control" name="quantity" value="{{ $product->qty }}">
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
                            <input type="radio" name="stock_status" class="form-check-input" @checked($product->in_stock == 1)  value="in_stock">
                            <span class="form-check-label">In Stock</span>
                          </label>
                          <label class="form-check">
                            <input type="radio" name="stock_status" class="form-check-input" @checked($product->in_stock == 0)  value="out_of_stock">
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

          <div class="card mt-3" id="product-images">
            <div class="card-header">
              <h3 class="card-title">Product Image</h3>
            </div>
            <div class="card-body">
              <div class="col-md-12">
                <div class="mb-3">
                  <div class="dropzone" id="imageUploader"></div>
                  <div class="image-preview-container" id="imagePreviewContainer">
                    @foreach($product?->images ?? [] as $image)
                      <div class="image-preview-item" data-image-id="{{ $image->id }}">
                        <img src="{{ asset($image->path) }}" alt="image">
                        <span class="remove-image" data-image-id="{{ $image->id }}">&times;</span>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card mt-3" id="product-images">
            <div class="card-header">
              <h3 class="card-title">Product Files</h3>
            </div>
            <div class="card-body">
              <div class="col-md-12">
                <div class="mb-3">
                  <div class="dropzone" id="fileUploader"></div>
                  <div class="file-preview-container" id="filePreviewContainer">
                    @foreach($product->files ?? [] as $file)
                      <div class="dz-preview dz-file-preview">
                        <div class="dz-filename"><span data-dz-name>{{ $file->filename }}</span></div>
                        <div class="dz-progress">
                          <div class="dz-upload" data-dz-uploadprogress style="width: 100%;"></div>
                        </div>
                        <div class="dz-percentage">uploaded</div>
                        <div class="dz-remove" data-file-id="{{ $file->id }}" data-dz-remove>&times;</div>
                      </div>
                    @endforeach
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
                    <option @selected($product->status == 'active') value="active">Active</option>
                    <option @selected($product->status == 'inactive') value="inactive">Inactive</option>
                    <option @selected($product->status == 'draft') value="draft">Draft</option>
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
                      <option @selected($product->store_id == $store->id) value="{{ $store->id }}">{{ $store->name }}</option>
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
                    <input class="form-check-input" type="checkbox" @checked($product->is_featured == 1) name="is_featured">
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
                          <input
                            type="checkbox" class="form-check-input category-check"
                             name="categories[]"
                             value="{{ $category->id }}"
                             @checked(in_array($category->id, $productCategoryIds ))
                          >
                          <span class="form-check-label category-label">{{ $category->name }}</span>
                        </label>

                        <!-- Sous-Categorie -->
                        @if($category->children_nested && $category->children_nested->count() > 0)
                          <ul class="list-unstyled ms-4 mt-2">
                            @foreach($category->children_nested as $child)
                              <li>
                                <label for="" class="form-check category-wrapper">
                                  <input
                                    type="checkbox"
                                    class="form-check-input category-check"
                                    name="categories[]"
                                    value="{{ $child->id }}"
                                    @checked(in_array($child->id, $productCategoryIds ))
                                  >
                                  <span class="form-check-label category-label">{{ $child->name }}</span>
                                </label>

                                <!--- Sous-Sous-Categorie -->
                                @if($child->children_nested && $child->children_nested->count() > 0)
                                  <ul class="list-unstyled ms-4 mt-2">
                                    @foreach($child->children_nested as $subChild)
                                      <li>
                                        <label for="" class="form-check category-wrapper">
                                          <input
                                            type="checkbox"
                                            class="form-check-input category-check"
                                            name="categories[]"
                                            value="{{ $subChild->id }}"
                                            @checked(in_array($subChild->id, $productCategoryIds ))
                                          >
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
                      <option value="{{ $brand->id }}" @selected($product->brand_id == $brand->id) >
                        {{ $brand->name }}
                      </option>
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
                    <input class="form-check-input" type="checkbox" name="is_hot" @checked($product->is_hot)>
                    <span class="form-check-label">Hot</span>
                  </label>
                  <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_new" @checked($product->is_new)>
                    <span class="form-check-label">New</span>
                  </label>
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
                      <option value="{{ $tag->id }}" @selected(in_array($tag->id, $productTagsIds))>
                        {{ $tag->name }}
                      </option>
                    @endforeach
                  </select>
                  <x-input-error :messages="$errors->get('tags')" />
                </div>
              </div>
            </div>
          </div>

          <div class="card mb-3" style="position: sticky; top: 0;">
            <div class="card-body">
              <div class="col-md-12">
                <div class="mb-3 row">
                  <button type="submit" class="btn btn-primary mt-3">Update</button>
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
  {{-- CDN Dropzone Upload Fichier  --}}
  <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
  {{-- CDN SortableJS Drag & Drop --}}
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
  {{-- CDN simonwep Color Picker --}}
  <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr"></script>

  {{-- Partie Editor TinyMCE --}}
  <script>
    window.productContent = {
      description: @json($product->description ?? ''),
      short_description: @json($product->short_description ?? '')
    };
  </script>

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
          url: "{{ route('vendor.products.update', ':id' ) }}" . replace(':id', '{{ $product->id }}'),
          data: data,
          contentType: false,
          processData: false,
          success: function (response) {
            window.location.href = response.redirect_url
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

    // dropzone upload image
    Dropzone.autoDiscover = false;
    const imageUploader = new Dropzone('#imageUploader', {
      url: "{{ route('vendor.products.images.upload', ':id') }}" .replace(':id', '{{ $product->id }}'),
      paramName: "image",
      maxFilesize: 10,
      acceptedFiles: "image/*",
      addRemoveLinks: false,
      autoProcessQueue: true,
      uploadMultiple: false,
      previewsContainer: false,
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      init: function() {
        this.on("addedfile", function(file) {
          const placeholderId = 'upload-' + Date.now()
          addUploadPlaceholder(placeholderId)
          file.placeholderId = placeholderId
        })

        this.on("success", function(file, response) {
          $(`#${file.placeholderId}`).remove()
          addImagePreview(response.path, response.id)
          this.removeFile(file)
        })
      }
    })

    // dropzone upload chunking files
    const fileUploader = new Dropzone('#fileUploader', {
      url: "{{ route('vendor.digital-products.file-upload') }}",
      paramName: "file",
      maxFilesize: 1024, // 1GB max file size
      chunking: true,
      forceChunking: true,
      chunkSize: 1024 * 1024, // 1MB par chunk
      parallelUploads: 1,
      acceptedFiles: ".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.7z,.mp4,.mov,.webm,.mp3,.wav,.ogg",
      addRemoveLinks: false,
      autoProcessQueue: true,
      uploadMultiple: false,
      previewsContainer: `#filePreviewContainer`,
      previewTemplate:
        `<div class="dz-preview dz-file-preview">
          <div class="dz-filename"><span data-dz-name></span></div>
          <div class="dz-progress"><div class="dz-upload" data-dz-uploadprogress></div></div>
          <div class="dz-percentage"><span class="progress-text">0</span>% uploaded</div>
          <div class="dz-remove" data-dz-remove>&times;</div>
        </div>`,
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      init: function() {
        this.on("uploadprogress", function(file, progress) {
          file.previewElement.querySelector(".progress-text").textContent = progress.toFixed(0);
        })

        this.on("sending", function(file, xhr, formData) {
          formData.append('name', file.upload.filename)
          formData.append('product_id', '{{ $product->id }}')
        })

        this.on("success", function(file, response) {
          window.location.reload();
        })

        this.on("error", function(file, response) {
          console.error(response)
          if (response.status === 'error') {
            notyf.error(response.message)
          }
        })
      }
    })

    // delete file from bdd et storage
    $(document).on('click', '.dz-remove', function() {
      const id = $(this).attr('data-file-id')
      $.ajax({
        method: "DELETE",
        url: "{{ route('vendor.digital-products.file.destroy', [':productId', ':id']) }}".replace(':id', id).replace(':productId', '{{ $product->id }}'),
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (response) {
          window.location.reload();
        },
        error: function (xhr, status, error) {
          console.log(xhr);
        }
      })
    })

    // add upload preview to DOM
    function addUploadPlaceholder(placeholderId) {
      const placeholderHtml = `
      <div id="${placeholderId}" class="image-preview-item">
        <div class="image-preview-loader"></div>
      </div
    `
      $('#imagePreviewContainer').append(placeholderHtml)
    }

    // add image preview to DOM
    function addImagePreview(path, id) {
      const placeholderHtml = `
      <div class="image-preview-item" data-image-id="${id}">
        <img src="${path}" alt="image">
        <span class="remove-image" data-image-id="${id}">&times;</span>
      </div
    `
      $('#imagePreviewContainer').append(placeholderHtml)
    }

    // remove image
    $(document).on('click', '.remove-image', function() {
      const imageId = $(this).attr('data-image-id')
      // stocker l'element sur lequel on clique
      const $element = this

      $.ajax({
        method: "DELETE",
        url: "{{ route('vendor.products.images.destroy', ':id') }}" .replace(':id', imageId),
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (response) {
          notyf.success(response.message)
          // supprimer le parent le plus proche
          $($element).closest('.image-preview-item').remove()
        },
        error: function (xhr, status, error) {
          // afficher l'erreur côté frontend
          notyf.error(error)
        }
      })
    })

    // init sortable
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    new Sortable(imagePreviewContainer, {
      animation: 150,
      onEnd: function () {
        // console.log('dragged!!!')
        updateImageOrder()
      }
    })

    function updateImageOrder() {
      // tableau des ids des images
      const imageIds = []

      $('.image-preview-item').each(function(index) {
        imageIds.push({
          id: $(this).data('image-id'),
          order: index
        })
      })

      $.ajax({
        url: "{{ route('vendor.products.images.reorder') }}",
        method: "POST",
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: {
          images: imageIds
        },
        success: function (response) {
          console.log(response)
        },
        error: function (xhr, status, error) {
          console.log(xhr)
        }
      })
    }

    // slug auto-generate
    $('#name').on('input', function() {
      $('#slug').val(slugify($(this).val()));
    })

    function slugify(text) {
      return text.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^a-z0-9\-]/g, '')
        .replace(/\-+/g, '-')
        .replace(/^\-+|\-+$/g, '')
    }

  </script>
@endpush
