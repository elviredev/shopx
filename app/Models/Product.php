<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
  public function categories(): BelongsToMany
  {
    return $this->belongsToMany(Category::class);
  }

  public function tags(): BelongsToMany
  {
    return $this->belongsToMany(Tag::class);
  }

  public function images(): HasMany
  {
    return $this->hasMany(ProductImage::class)->orderBy('order');
  }
}
