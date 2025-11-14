<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Store;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function index(): View
  {
    return view('admin.product.index');
  }

  public function create(): View
  {
    $stores = Store::select(['id', 'name'])->get();
    $brands = Brand::select(['id', 'name'])->where('is_active', 1)->get();
    $tags = Tag::where('is_active', 1)->get();
    $categories = Category::getNested();

    return view('admin.product.create', compact('stores', 'brands', 'tags', 'categories'));
  }
}
