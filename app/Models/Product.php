<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @des permet d'éviter le soulignement rouge de phpstorm sur la relation "attributes"
 * @property $attributes
 */

class Product extends Model
{
  use SoftDeletes;

  protected $guarded = [];
  public function categories(): BelongsToMany
  {
    return $this->belongsToMany(Category::class);
  }

  public function tags(): BelongsToMany
  {
    return $this->belongsToMany(Tag::class);
  }

  public function primaryImage(): HasOne
  {
    return $this->hasOne(ProductImage::class)->orderBy('order');
  }

  public function images(): HasMany
  {
    return $this->hasMany(ProductImage::class)->orderBy('order');
  }

  /**
   * @desc un produit peut avoir plusieurs attributs (ex: Couleur, Taille, Matière…)
   * @return BelongsToMany
   */
  public function attributes(): BelongsToMany
  {
    return $this->belongsToMany(Attribute::class, 'product_attribute_values')
      ->withPivot('attribute_value_id');
  }

  /**
   * @desc un produit peut avoir plusieurs valeurs d’attributs (ex: Rouge, XL)
   * @return BelongsToMany
   */
  public function attributeValues(): BelongsToMany
  {
    return $this->belongsToMany(AttributeValue::class, 'product_attribute_values')
      ->withPivot('attribute_id');
  }


//  public function attributesWithValues(): BelongsToMany
//  {
//    return $this->belongsToMany(Attribute::class, 'product_attribute_values')
//      ->distinct()
//      ->orderBy('id', 'asc')
//      ->with(['values' => function ($query) {
//        $query->whereIn('id', function ($subquery) {
//         $subquery->select('attribute_value_id')
//            ->from('product_attribute_values')
//            ->where('product_id', $this->id)
//            ->orderBy('id', 'asc');
//        });
//      }]);
//  }

  /**
   * @desc Permet de récupérer chaque attribut du produit avec uniquement
   * les valeurs associées à ce produit.
   * @return BelongsToMany
   */
  public function attributesWithValues()
  {
    $valueIds = $this->attributeValues->pluck('id'); // chargé 1 seule fois

    return $this->belongsToMany(Attribute::class, 'product_attribute_values')
      ->distinct()
      ->orderBy('attributes.id', 'asc')
      ->with(['values' => function ($q) use ($valueIds) {
        $q->whereIn('id', $valueIds);
      }]);
  }

  public function variants(): HasMany
  {
    return $this->hasMany(ProductVariant::class);
  }

  public function primaryVariant():HasOne
  {
    return $this->hasOne(ProductVariant::class)->where('is_default', 1);
  }

  public function store(): BelongsTo
  {
    return $this->belongsTo(Store::class);
  }

  public function files(): HasMany
  {
    return $this->hasMany(ProductFile::class);
  }
}
