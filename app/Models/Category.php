<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LaravelIdea\Helper\App\Models\_IH_Category_C;

class Category extends Model
{
  protected $fillable = ['name', 'slug', 'parent_id', 'position', 'is_active'];

  /**
   * @desc retourne la catégorie parent d'une sous-catégorie enfant
   */
  public function parent(): BelongsTo
  {
    return $this->belongsTo(Category::class, 'parent_id');
  }

  /**
   * @desc retourne les sous-categories enfant d'une catégorie parent
   */
  public function children(): HasMany
  {
    return $this->hasMany(Category::class, 'parent_id');
  }

  /**
   * @desc obtenir une catégorie imbriquée en fonction de la catégorie parente
   * @param $parentId
   * @param $depth
   * @param $maxDepth
   * @return Category[]|array|_IH_Category_C
   */
  static function getNested($parentId = null, $depth = 0, $maxDepth = 3)
  {
    if ($depth >= $maxDepth) return [];
    $categories = self::where('parent_id', $parentId)->orderBy('position')->get();

    foreach ($categories as $cat) {
      $cat->children_nested = self::getNested($cat->id, $depth + 1, $maxDepth);
    }

    return $categories;
  }
}
