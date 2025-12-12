<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
  protected $guarded = [];

  /**
   * A partir de la variante, pouvoir récupérer le produit
   * @return BelongsTo
   */
  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * @desc A partir de la variante, pouvoir récupérer ses attributs
   * @return BelongsToMany
   */
  public function attributes(): BelongsToMany
  {
    return $this->belongsToMany(Attribute::class, 'product_variant_attribute_value')->withPivot('attribute_value_id');
  }

  /**
   * A partir de la variante, pouvoir récupérer les valeurs de ses attributs
   * @return BelongsToMany
   */
  public function attributeValues(): BelongsToMany
  {
    return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_value')->withPivot('attribute_id');
  }
}
