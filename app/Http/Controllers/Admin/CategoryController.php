<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
  public function index(): View
  {
    return view('admin.category.index');
  }

  public function store(Request $request)
  {
    // validate data
    $data = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'slug' => ['required', 'string', 'max:255', 'unique:categories,slug'],
      'parent_id' => ['nullable', 'exists:categories,id'],
      'is_active' => ['boolean'],
    ]);

    // prevent circular reference and max depth
    if($data['parent_id'] ?? null) {
      $parent = Category::find($data['parent_id']);
      $depth = 1;

      while($parent && $parent->parent_id) {
        $depth++;
        $parent = $parent->parent;
        if($depth >= 3) break;
      }

      if($depth >= 3) {
        throw ValidationException::withMessages([
          'parent_id' => 'Maximum depth reached.'
        ]);
      }
    }

    // récupérer position et ordonner les catégories dans un même niveau
    $data['position'] = Category::where('parent_id', $data['parent_id'] ?? null)->max('position') + 1;

    // créer category
    $category = Category::create($data);

    return response()->json([
      'success' => true,
      'message' => 'Category created successfully',
      'category' => $category
    ]);
  }

  public function updateOrder(Request $request)
  {
    $tree = $request->tree;

    try {
      DB::transaction(function () use ($tree) {
        $this->updateTree($tree, null);
      });

      return response()->json([
        'success' => true,
        'message' => 'Categories reordered successfully'
      ]);
    } catch(\Throwable $th) {
      Log::error('Category reorder error: ' . $th->getMessage());
      return response()->json([
        'success' => false,
        'message' => $th->getMessage()
      ], 500);
    }
  }

  public function updateTree($nodes, $parentId)
  {
    foreach ($nodes as $position => $node) {
      $category = Category::find($node['id']);
      $category->update([
        'parent_id' => $parentId,
        'position' => $position
      ]);

      if (isset($node['children']) && is_array($node['children'])) {
        $this->updateTree($node['children'], $category->id);
      }
    }
  }

  /**
   * Retrieves nested categories and returns them as a JSON response.
   */
  public function getNestedCategories()
  {
    $categories = Category::getNested();
    return response()->json($categories);
  }

}
