@extends('admin.layouts.app')

@push('styles')
  <style>
    .dd-item.custom-cat-item {
      border: none;
      padding: 0;
      margin-bottom: 0;
      background: none;
      border-radius: 0;
    }

    .dd-item-row.custom-cat-row {
      user-select: text;
      background: none;
      gap: 4px;
      border: 1px solid #e9ecef;
      min-height: 38px;
      display: flex;
      align-items: center;
      padding-left: 0.75rem;
      /* px-2 */
      padding-right: 0.75rem;
      padding-top: 0.25rem;
      /* py-1 */
      padding-bottom: 0.25rem;
    }

    .dd-handle.custom-cat-handle {
      cursor: move;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 0.5rem;
      /* me-2 */
    }

    .cat-folder-icon {
      font-size: 16px;
      color: #6c757d;
    }

    .cat-label.custom-cat-label {
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 2px;
      flex: 1 1 auto;
    }
  </style>
@endpush

@section('contents')
  <div class="container-fluid mt-4">
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-header dflex justify-content-between align-items-center">
            <span>Categories</span>
            <button class="btn btn-primary">New</button>
          </div>
          <div class="card-body">
            <!-- Category tree -->
            <div id="category-tree" class="dd">

            </div>

            <!-- Loading spinner -->
            <div id="tree-loading" class="text-center my-2 d-none">
              <div class="spinner-border"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <span>Create Category</span>
          </div>
          <div class="card-body">
            <form id="category-form" action="">

              <div class="form-group mb-2">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required>
              </div>

              <div class="form-group mb-2">
                <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                <input type="text" name="slug" id="slug" class="form-control" required>
              </div>

              <div class="form-group mb-2">
                <label for="parent_id" class="form-label">Parent Category <span class="text-danger">*</span></label>
                <select name="parent_id" id="parent_id" class="form-select">
                </select>
              </div>

              <div class="form-group mb-2">
                <label for="is_active" class="form-check form-switch form-switch-3">
                  <input class="form-check-input" type="checkbox" checked="" name="is_active" id="is_active">
                  <span class="form-check-label">Active</span>
                </label>
              </div>

              <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit" id="btn-save">Save</button>
                <button class="btn btn-danger" type="button" id="btn-delete">Delete</button>
                <button class="btn btn-secondary" type="button" id="btn-cancel">Cancel</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(function() {

      function loadTree() {
        $('#tree-loading').show();

        $.get("{{ route('admin.categories.nested') }}", function(data) {
          $('#category-tree').empty();
          let html = '<div class="dd" id="nestable-tree">' + renderTree(data) + '</div>';
          $('#category-tree').html(html);

          // drag & drop
          $('#nestable-tree').nestable({
              maxDepth: 3
            })
            .off('change')
            .on('change', function(e) {
              if(!$(e.target).hasClass('no-drag')) {
                console.log(e);
                updateOrder();
              }
            });
            $('#tree-loading').hide();
        })
      }

      function renderTree(categories) {
        if(!categories.length) return;
        let html = '<ol class="dd-list" style="margin-bottom: 0;">';

        categories.forEach(function(cat) {
          html +=
            `<li class="dd-item custom-cat-item" data-id="${cat.id}">
              <div class="dd-item-row custom-cat-row">
                <div class="dd-handle custom-cat-handle" title="Drag to reorder">
                  <i class="ti ti-grip-horizontal"></i>
                </div>
                <i class="ti ti-folder cat-folder-icon"></i>
                <div class="cat-label custom-cat-label" data-id="${cat.id}">
                  <span>${cat.name}</span>
                  ${cat.is_active ? '<span class="text-success ms-2" style="font-size: 10px">&#9679</span>' : '<span class="text-danger ms-2" style="font-size: 10px">&#9679</span>'}
                </div>
              </div>`
          if(cat.children_nested && cat.children_nested.length) {
            html += renderTree(cat.children_nested);
          }
          html += '</li>';
        })

        html += '</ol>';

        return html;
      }

      function updateOrder() {
        let tree = $('#nestable-tree').nestable('serialize');
        $.post({
          url: "{{ route('admin.categories.update-order') }}",
          data: {
            tree: tree,
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            if(response.success) {
              notyf.success(response.message);
            }
          },
          error: function(xhr, status, error) {

          }
        })
      }

      $('#category-form').submit(function(e) {
        e.preventDefault();

        let method = 'POST';
        let url = '{{ route('admin.categories.store') }}';
        let data = {
          name: $('#name').val(),
          slug: $('#slug').val(),
          parent_id: $('#parent_id').val(),
          is_active: $('#is_active').is(':checked') ? 1 : 0,
          _token: '{{ csrf_token() }}'
        }

        $.ajax({
          url: url,
          method: method,
          data: data,
          success: function(response) {
            console.log(response);
            clearForm();
            notyf.success(response.message);
          },
          error: function(xhr, status, error) {
            let errors = xhr.responseJSON.errors;
            $.each(errors, function(key, value) {
              notyf.error(errors[key][0]);
            })
          }
        })
      })

      // load parent dropdown
      function loadParentDropdown(selectedId, excludeId) {
        $.get('{{ route('admin.categories.nested') }}', function (data) {
          let options = '<option value="">None (Root)</option>';

          function addOptions(cats, prefix, depth) {
            cats.forEach(function(cat) {
              if (cat.id == excludeId) return;
              options += `<option value="${cat.id}" ${selectedId  == cat.id ? 'selected' : ''}>
                            ${prefix}${cat.name}
                          </option>`;

              if(cat.children_nested && cat.children_nested.length) {
                addOptions(cat.children_nested, prefix + '--', depth + 1);
              }
            })
          }

          addOptions(data, '', 0);

          $('#parent_id').html(options);
        })
      }

      // clear form
      function clearForm() {
        $('#name').val('');
        $('#slug').val('');
        $('#parent_id').val('');
        $('#is_active').prop('checked', true);
        loadParentDropdown(null, null);
      }

      // Initial load
      clearForm();
      loadTree();
    })
  </script>
@endpush
