@extends('admin.layouts.app')

@push('styles')
  {{-- CDN Dropzone Upload Fichier --}}
  <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
  {{-- CDN simonwep Color Picker --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css"/>

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
            {{-- <div class="card-header">--}}
            {{-- <h3 class="card-title">Create Role</h3>--}}
            {{-- <div class="card-actions">--}}
            {{-- <a href="{{ route('admin.role.index') }}" class="btn btn-secondary">Back</a>--}}
            {{-- </div>--}}
            {{-- </div>--}}
            <div class="card-body">

              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-label required">Name</label>
                  <input type="text" class="form-control" name="name" value="{{ $product->name }}">
                  <x-input-error :messages="$errors->get('name')" />
                </div>
              </div>

              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-label required">Slug</label>
                  <input type="text" class="form-control" name="slug" value="{{ $product->slug }}">
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
              <h3 class="card-title">Product Attributes</h3>
            </div>
            <div class="card-body">
              <div class="col-md-12">

                <div class="accordion" id="accordion-default">
                  @foreach($attributesWithValues as $attribute)
                    @include('admin.product.partials.attribute', [
                      'attribute' => $attribute,
                      'product' => $product
                    ])
                  @endforeach
                </div>

                <button type="button" class="btn btn-primary mt-3" id="add-attribute-btn">Add Attribute</button>
              </div>
            </div>
          </div>

          <div class="card mt-3" id="product-images">
            <div class="card-header">
              <h3 class="card-title">Product Variants</h3>
            </div>
            <div class="card-body">
              <div class="col-md-12">
                <div class="accordion" id="accordion-variant">
                  @foreach($variants as $variant)
                    @include('admin.product.partials.variant', ['variant' => $variant])
                  @endforeach
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
                    <option @selected($product->status == 'pending') value="pending">Pending</option>
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

  {{-- Partie Attributes --}}
  <script>
    $(function() {
      /** Color Picker */
      const pickerInstances = {}
      let uniqueCounter = 0

      // générer un unique ID pour nos couleurs
      function generateUniqueColorId(prefix = 'picker-') {
        return prefix + uniqueCounter++ + '-' + Date.now()
      }

      // Pick init
      function createPicker(pickerId, defaultColor, inputSelector) {
        // si une instance de Picker existe, on la supprime
        if (pickerInstances[pickerId]) {
          pickerInstances[pickerId].destroyAndRemove()
        }

        // Pickr crée un color picker lié à une div précise
        const picker = Pickr.create({
          el: `#${pickerId}`,
          theme: 'classic',
          default: defaultColor,
          components: {
            preview: true,
            opacity: true,
            hue: true,
            interaction: {
              hex: true,
              rgba: true,
              input: true,
              clear: true,
              save: true
            }
          }
        })

        // a chaque changement
        picker.on('change', (color) => {
          // récupére la couleur sélectionnée
          const selectedColor = color.toHEXA().toString()
          // change la couleur du preview (la div)
          $(`#${pickerId}`).css('background-color', selectedColor)
          // met à jour l'input associé
          $(inputSelector).val(selectedColor)
        })

        // stocke l'instance de Picker dans un objet global
        pickerInstances[pickerId] = picker
      }

      // supprime l'instance de Picker
      function destroyPicker(pickerId) {
        if (pickerInstances[pickerId]) {
          pickerInstances[pickerId].destroyAndRemove()
          delete pickerInstances[pickerId]
        }
      }

      // initier les pickers sur les divs color-preview
      function initColorPickersInContainer($container) {
        $container.find('.color-preview').each(function () {
          const $this = $(this)
          const pickerId = $this.attr('id')
          const currentColor = $this.css('background-color') || '#000000'
          createPicker(pickerId, currentColor, `input[data-picker-id="${pickerId}"]`)
        })
      }

      /** Add Accordion Item for Attributes */
      let count = 0

      // Ajouter un nouvel attribut (accordion item)
      $('#add-attribute-btn').on('click', function () {
        count++
        const collapseId = 'collapse' + count
        const headerId = 'header' + count

        const accordionItem = `
          <div class="accordion-item" data-index="${count}">
            <div class="accordion-header" id="${headerId}">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false">
                New Attribute #${count}
                <div class="accordion-button-toggle">
                  <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-down -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                    <path d="M6 9l6 6l6 -6"></path>
                  </svg>
                </div>
              </button>
              <span class="delete-btn btn btn-danger btn-sm p-1 me-2"><i class="ti ti-trash"></i></span>
            </div>
            <div id="${collapseId}" class="accordion-collapse collapse" data-bs-parent="#accordion-default" style="">
              <div class="accordion-body">
                <form action="#" method="POST" class="">
                  @csrf
                  <div class="row">
                    <div class="col-md-6">
                      <label for="" class="form-label">Name</label>
                      <input type="text" class="form-control" name="attribute_name">
                    </div>
                    <div class="col-md-6">
                      <label for="" class="form-label">Type</label>
                      <select name="attribute_type" id="" class="form-select main-type">
                        <option value="text">Text</option>
                        <option value="color">Color</option>
                      </select>
                    </div>
                  </div>

                  <table class="table table-bordered section-table mt-3" style="display: none;">
                    <thead>
                    <tr>
                      <th>Label</th>
                      <th class="value-header">Value</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>

                  <div class="mt-2">
                  <button type="button" class="btn btn-sm btn-secondary p-1 add-row-btn">Add Row</button>
                  <button type="button" class="btn btn-sm btn-success p-1 save-btn">Save</button>
                </div>
                </form>
              </div>
            </div>
          </div>
        `
        // ajouter l'item au DOM
        $('#accordion-default').append(accordionItem)
      })

      // ajouter une ligne
      $(document).on('click', '.add-row-btn', function () {
        const accordionBody = $(this).closest('.accordion-body')
        const type = accordionBody.find('.main-type').val()
        const table = accordionBody.find('.section-table')
        const tbody = table.find('tbody')
        table.show()

        const pickerId = generateUniqueColorId()
        let rowHtml = ''

        if (type === 'color') {
          rowHtml = `
            <tr>
              <td>
                <input type="text" class="form-control label-input" name="label[]" value="" placeholder="Label">
              </td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div id="${pickerId}" class="color-preview"></div>
                  <input type="hidden" class="color-value" data-picker-id="${pickerId}" name="color_value[]">
                  <span class="remove-row-btn btn btn-danger btn-sm p-1 ms-2"><i class="ti ti-trash"></i></span>
                </div>
              </td>
            </tr>
          `
        } else {
          rowHtml = `
            <tr>
              <td colspan="2">
                <div class="d-flex justify-content-between align-items-center">
                  <input type="text" class="form-control label-input" name="label[]" value="" placeholder="Label">
                  <span class="remove-row-btn btn btn-danger btn-sm p-1 ms-2"><i class="ti ti-trash"></i></span>
                </div>
              </td>
            </tr>
          `
        }
        // ajouté la ligne au DOM
        tbody.append(rowHtml)

        // init color picker
        if (type === 'color') {
          createPicker(pickerId, '#000000', `input[data-picker-id="${pickerId}"]`)
        }
      })

      // remove attribute values
      $(document).on('click', '.remove-row-btn', function () {
        const $row = $(this).closest('tr')
        const $colorPreview = $row.find('.color-preview')

        if ($colorPreview.length) {
          destroyPicker($colorPreview.attr('id'))
        }

        const $table = $(this).closest('.section-table')

        // supprimer la ligne du DOM
        $row.remove()

        const $tbody = $table.find('tbody')
        if ($tbody.find('tr').length === 0) {
          $table.hide()
        }
      })

      // change type => remove rows and manage pickers
      $(document).on('change', '.main-type', function () {
        const $accordionBody = $(this).closest('.accordion-body')
        const type = $(this).val()
        const $table = $accordionBody.find('.section-table')
        const $tbody = $table.find('tbody')

        // collect row values and destroy any existing pickers
        const labels = []

        $tbody.find('tr').each(function () {
          // supprime toute instance de color picker existante
          const colorPreview = $(this).find('.color-preview')
          if (colorPreview.length) {
            destroyPicker(colorPreview.attr('id'))
          }
          // récupérer les labels (lignes) qu'on insére dans le tableau
          const labelValue = $(this).find('.label-input').val()
          labels.push(labelValue || '')
        })

        $tbody.empty()

        // générer de nouvelles lignes pour le nouveau type
        labels.forEach(label => {
          const pickerId = generateUniqueColorId()
          let rowHtml = ''

          if (type === 'color') {
            rowHtml = `
            <tr>
              <td>
                <input type="text" class="form-control label-input" name="label[]" value="${label}" placeholder="Label">
              </td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div id="${pickerId}" class="color-preview"></div>
                  <input type="hidden" class="color-value" data-picker-id="${pickerId}" name="color_value[]">
                  <span class="remove-row-btn btn btn-danger btn-sm p-1 ms-2"><i class="ti ti-trash"></i></span>
                  </input>
                  <span class="remove-row-btn btn btn-danger btn-sm p-1 ms-2"><i class="ti ti-trash"></i></span>
                </div>
              </td>
            </tr>
          `
          } else {
            rowHtml = `
            <tr>
              <td colspan="2">
                <div class="d-flex justify-content-between align-items-center">
                  <input type="text" class="form-control label-input" name="label[]" value="${label}" placeholder="Label">
                  <span class="remove-row-btn btn btn-danger btn-sm p-1 ms-2"><i class="ti ti-trash"></i></span>
                </div>
              </td>
            </tr>
          `
          }

          // ajouté la ligne au DOM
          $tbody.append(rowHtml)

          // init color picker
          if (type === 'color') {
            createPicker(pickerId, '#000000', `input[data-picker-id="${pickerId}"]`)
          }

        })

        if(labels.length > 0) {
          $table.show()
        } else {
          $table.hide()
        }

      })

      // remove attribute
      $(document).on('click', '.delete-btn', function () {
        const $accordionItem = $(this).closest('.accordion-item')
        // supprimer les instances de color picker existantes
        $accordionItem.find('.color-preview').each(function () {
          destroyPicker($(this).attr('id'))
        })

        // récupérer les identifiants (depuis attribute.blade)
        const productId = $(this).data('product-id')
        const attributeId = $(this).data('attribute-id')

        if(!attributeId) {
          $accordionItem.remove()
          return
        }

        // sweetalert2 pour confirmer la suppression
        Swal.fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        }).then((result) => {
          if (result.isConfirmed) {
            // requête ajax pour supprimer l'attribut
            $.ajax({
              url: "{{ route('admin.products.attributes.destroy', [':id', ':attribute_id']) }}"
                .replace(':id', productId).replace(':attribute_id', attributeId),
              method: 'DELETE',
              data: {
                _token: "{{ csrf_token() }}"
              },
              success: function (response) {
                $('#accordion-default').html(response.html)
                $('#accordion-variant').html(response.variantHtml)
                response.html ? $('.disabled-placeholder').show() : $('.disabled-placeholder').hide()
                notyf.success(response.message)
              },
              error: function (xhr, status, error) {
                notyf.error(error)
              }
            })
          }
        });

      })

      // save attribute values
      $(document).on('click', '.save-btn', function (e) {
        e.preventDefault()

        const form = $(this).closest('form')
        const data = form.serialize()

        $.ajax({
          url: "{{ route('admin.products.attributes.store', ':id') }}". replace(':id', '{{ $product->id }}'),
          method: 'POST',
          data: data,
          success: function (response) {
            $('#accordion-default').html(response.html)
            $('#accordion-variant').html(response.variantHtml)
            response.html ? $('.disabled-placeholder').show() : $('.disabled-placeholder').hide()

            initColorPickersInContainer($('#accordion-default'))
            notyf.success(response.message)
          },
          error: function (xhr, status, error) {
          }
        })
      })

      // initialyze color pickers in accordions on load
      $(document).ready(function () {
        initColorPickersInContainer($('#accordion-default'))
      })

      // checkbox Manage Stock (variant)
      $(document).on('change', '.variant-manage-stock', function () {
        const isChecked = $(this).is(':checked')
        $(this).closest('.col-md-12').find('.variant-quantity').toggle(isChecked)
      })

      // soumission du formulaire de variant
      $(document).on('click','.variant-save-btn', function (e) {
        e.preventDefault()

        const form = $(this).closest('.variant-form')
        const data = form.serialize()

        $.ajax({
          url: "{{ route('admin.products.variants.update', ':productid') }}".replace(':productid', '{{ $product->id }}'),
          method: 'POST',
          data: data,
          success: function (response) {
            notyf.success(response.message)
          },
          error: function (xhr, status, error) {
            const errors = xhr.responseJSON.errors
            $.each(errors, function (key, value) {
              notyf.error(errors[key][0])
            })
          }
        })
      })



    })
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
          url: "{{ route('admin.products.update', ':id' ) }}" . replace(':id', '{{ $product->id }}'),
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
      url: "{{ route('admin.products.images.upload', ':id') }}" .replace(':id', '{{ $product->id }}'),
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
        url: "{{ route('admin.products.images.destroy', ':id') }}" .replace(':id', imageId),
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
        url: "{{ route('admin.products.images.reorder') }}",
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

  </script>
@endpush
