<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
      'id',
      'seller_id',
      'logo',
      'banner',
      'name',
      'phone',
      'email',
      'address',
      'short_description',
      'long_description',
    ];
}
