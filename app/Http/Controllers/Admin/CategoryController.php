<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

  /**
   * Retrieves nested categories and returns them as a JSON response.
   */
  public function getNestedCategories()
  {
    $categories = Category::getNested();
    return response()->json($categories);
  }

}
