<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Store;
use App\Models\Tag;
use App\Services\AlertService;
use App\Traits\FileUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  use FileUploadTrait;

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
      'id' => $product->id,
      'status' => 'success',
      'message' => 'Product created successfully.'
    ]);
  }

  public function edit(int $id)
  {
    $product = Product::findOrFail($id);
    $stores = Store::select(['id', 'name'])->get();
    $brands = Brand::select(['id', 'name'])->where('is_active', 1)->get();
    $tags = Tag::where('is_active', 1)->get();
    $categories = Category::getNested();

    return view('admin.product.edit', compact('stores', 'brands', 'tags', 'categories', 'product'));
  }

  public function uploadImages(Request $request, Product $product)
  {
    $request->validate([
      'image' => [ 'required', 'image', 'max:2048']
    ]);

    $imagePath = $this->uploadFile($request->file('image'));

    $productImage = new ProductImage();
    $productImage->product_id = $product->id;
    $productImage->path = $imagePath;
    $productImage->order = ProductImage::where('product_id', $product->id)->max('order') + $product->id;
    $productImage->save();

    return response()->json([
      'status' => 'success',
      'id' => $productImage->id,
      'path' => asset($imagePath),
      'message' => 'Image uploaded successfully.'
    ]);
  }

  public function destroyImage(int $id)
  {
    $image = ProductImage::findOrFail($id);
    $this->deleteFile($image->path);
    $image->delete();

    return response()->json([
      'status' => 'success',
      'message' => 'Image deleted successfully.'
    ]);
  }

  public function reorderImages(Request $request)
  {
    foreach ($request->images as $image) {
      // Mettre à jour l'ordre de l'image
      ProductImage::where('id', $image['id'])->update(['order' => $image['order']]);
    }
  }
}
