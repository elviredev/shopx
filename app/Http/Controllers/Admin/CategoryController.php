<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller implements HasMiddleware
{
  /**
   * @desc Middleware pour vérifier l'autorisation d'accès aux méthodes du controller
   * si user n'a pas la permission, il ne pourra pas accèder aux routes et vues du controller
   * @return Middleware[]
   */
  static function Middleware(): array
  {
    return [
      new Middleware('permission:Category Management')
    ];
  }

  /**
   * @desc afficher page des catégories
   * @return View
   */
  public function index(): View
  {
    return view('admin.category.index');
  }

  /**
   * @desc enregistrer une catégorie en bdd
   * @param Request $request
   * @return JsonResponse
   */
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
   * @desc Mettre à jour une catégorie en bdd
   * @param Request $request
   * @param int $id
   * @return JsonResponse
   */
  public function update(Request $request, int $id)
  {
    // récupérer la category
    $category = Category::findOrFail($id);

    // validate data
    $data = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'slug' => ['required', 'string', 'max:255', 'unique:categories,slug,' .$category->id],
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

    $data['is_active'] = $data['is_active'] ?? false;

    // mettre à jour la category
    $category->update($data);

    return response()->json([
      'success' => true,
      'message' => 'Category updated successfully',
      'category' => $category
    ]);
  }

  /**
   * @desc Mettre à jour la position des catégories en bdd
   * @param Request $request
   * @return JsonResponse
   */
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

  /**
   * @desc Fonction récursive qui parcourt et met à jour chaque catégorie dans la structure arborescente
   * @param $nodes
   * @param $parentId
   * @return void
   */
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
   * @desc Récupère la catégorie en fonction de son id et la retourne en tant que response JSON
   * @param int $id
   * @return JsonResponse
   */
  public function show(int $id)
  {
    $category = Category::findOrFail($id);
    return response()->json($category);
  }


  public function destroy(int $id)
  {
    $category = Category::findOrFail($id);
    // vérifier si la catégorie possède des enfants
    if ($category->children()->count() > 0) {
      return response()->json([
        'error' => true,
        'message' => 'Cannot delete category with children.'
      ], 422);
    }

    $category->delete();

    return response()->json([
      'success' => true,
      'message' => 'Category deleted successfully.'
    ]);
  }

  /**
   * Récupère les catégories et sous-catégories et les retourne en tant que response JSON
   */
  public function getNestedCategories()
  {
    $categories = Category::getNested();
    return response()->json($categories);
  }

}
