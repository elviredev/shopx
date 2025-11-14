<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\Tag;
use App\Services\AlertService;
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

  public function store(ProductStoreRequest $request)
  {
    $product = new Product();
    $product->name = $request->name;
    $product->slug = $request->slug;
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->sku = $request->sku;
    $product->price = $request->price;
    $product->special_price = $request->special_price;
    $product->special_price_start = $request->from_date;
    $product->special_price_end = $request->to_date;
    $product->qty = $request->quantity;
    $product->manage_stock = $request->has('manage_stock') ? 'yes' : 'no';
    $product->in_stock = $request->stock_status == 'in_stock' ? 1 : 0;
    $product->status = $request->status;
    $product->store_id = $request->store;
    $product->brand_id = $request->brand;
    $product->is_featured = $request->has('is_featured') ? 1 : 0;
    $product->is_hot = $request->has('is_hot') ? 1 : 0;
    $product->is_new = $request->has('is_new') ? 1 : 0;
    $product->save();

    /** Attach categories to the product */
    $product->categories()->sync($request->categories);

    /** Attach tags to the product */
    $product->tags()->sync($request->tags);

    return response()->json([
      'status' => 'success',
      'message' => 'Product created successfully.'
    ]);
  }
}
