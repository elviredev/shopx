<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Store;
use App\Models\Tag;
use App\Traits\FileUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
  use FileUploadTrait;

  /** =================== Product CRUD ==================== */

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
      'redirect_url' => route('admin.products.edit', $product->id) .'#product-images',
      'status' => 'success',
      'message' => 'Product created successfully.'
    ]);
  }

  public function edit(int $id)
  {
    $product = Product::findOrFail($id);
    $productCategoryIds = $product->categories->pluck('id')->toArray();
    $productTagsIds = $product->tags->pluck('id')->toArray();
    $stores = Store::select(['id', 'name'])->get();
    $brands = Brand::select(['id', 'name'])->where('is_active', 1)->get();
    $tags = Tag::where('is_active', 1)->get();
    $categories = Category::getNested();

    $attributesWithValues = $product?->attributesWithValues ?? [];
    // dd($attributesWithValues);

    return view('admin.product.edit', compact('stores', 'brands', 'tags', 'categories', 'product', 'productCategoryIds', 'productTagsIds', 'attributesWithValues'));
  }

  /**
   * @desc Update product
   * @param ProductUpdateRequest $request
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(ProductUpdateRequest $request, int $id)
  {
    $product = Product::findOrFail($id);
    $product->name = $request->name;
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
      'message' => 'Product updated successfully.'
    ]);
  }

  /** =================== Product Image ==================== */
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

  /** =================== Product Attributes ==================== */

  /**
   * @desc Store product attributes
   * Gère la création d’un nouvel attribut (ex : Taille, Couleur, Matière...) et délègue
   * ensuite la création des valeurs associées.
   */
  public function storeAttributes(Request $request, Product $product)
  {
    $request->validate([
      'attribute_name' => ['required', 'string', 'max:255'],
      'attribute_type' => ['required', 'string', 'in:text,color']
    ]);

    DB::beginTransaction();

    try {
      if ($request->filled('attribute_id')) {
        $this->updateExistingAttribute($request, $product);
      } else {
        $this->createNewAttribute($request, $product);
      }

      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
      return response()->json(['error' => $th->getMessage()], 500);
    }

    return $this->buildSuccessResponse($product);
  }

  /**
   * @desc Create new attribute
   * @param Request $request
   * @param Product $product
   * @return void
   */
  public function createNewAttribute(Request $request, Product $product)
  {
    $attribute = new Attribute();
    $attribute->name = $request->attribute_name;
    $attribute->type = $request->attribute_type;
    $attribute->save();

    $this->addAttributesValue($request, $attribute, $product);
  }

  /**
   * @desc Update existing attribute
   * @param Request $request
   * @param Product $product
   * @return void
   */
  public function updateExistingAttribute(Request $request, Product $product)
  {
    // Find attribute
    $attribute = Attribute::findOrFail($request->attribute_id);
    $attribute->name = $request->attribute_name;
    $attribute->type = $request->attribute_type;
    $attribute->save();

    //remove existing relations and values for this attribute
    $this->clearAttributeData($attribute, $product);

    // add new values for this attribute
    $this->addAttributesValue($request, $attribute, $product);
  }

  /**
   * @desc Delete relations and values for an attribute
   * @param Attribute $attribute
   * @param Product $product
   * @return void
   */
  public function clearAttributeData(Attribute $attribute, Product $product)
  {
    // Delete relationship
    DB::table('product_attribute_values')
      ->where('product_id', $product->id)
      ->where('attribute_id', $attribute->id)
      ->delete();

    // Delete attribute values
    AttributeValue::where('attribute_id', $attribute->id)->delete();
  }

  /**
   * @desc Gère la création des valeurs associées à l’attribut et leur liaison
   * avec le produit via une table pivot
   * @param Request $request
   * @param Attribute $attribute
   * @param Product $product
   * @return void
   */
  public function addAttributesValue(Request $request, Attribute $attribute, Product $product)
  {
    // récupérer les labels du champs de formulaire
    $labels = $request->label ?? [];

    foreach ($labels as $index => $label) {
      // si pas de label, continuer
      if (empty($label)) continue;

      // créer une nouvelle valeur d'attribut et la stocker dans la table "attribute_values"
      $attributeValue = new AttributeValue();
      $attributeValue->attribute_id = $attribute->id;
      $attributeValue->value = $label;
      $attributeValue->color = $request->color_value[$index] ?? null;
      $attributeValue->save();

      // assigner à un produit
      DB::table('product_attribute_values')->insert([
        'product_id' => $product->id,
        'attribute_id' => $attribute->id,
        'attribute_value_id' => $attributeValue->id
      ]);

    }
  }

  /**
   * @desc Build and return a success response with updated attributes for the product
   * @param Product $product
   * @return \Illuminate\Http\JsonResponse
   * @throws \Throwable
   */
  public function buildSuccessResponse(Product $product)
  {
    // actualiser toutes les relations existante du produit
    $product->refresh();

    // récupérer les valeurs des attributs pour le produit passé en paramètre
    $attributes = $product->attributesWithValues;

    $html = '';

    foreach ($attributes as $attribute) {
      // passer les attributs à la vue
      $html .= view('admin.product.partials.attribute', compact('attribute', 'product'))->render();
    }

    return response()->json([
      'message' => 'Attributes generated successfully.',
      'html' => $html
    ]);
  }

  public function destroyAttribute(int $productId, int $attributeId)
  {
    try {
      $product = Product::findOrFail($productId);
      $attribute = Attribute::findOrFail($attributeId);

      $this->clearAttributeData($attribute, $product);

      $product->refresh();

      // récupérer les valeurs des attributs pour le produit passé en paramètre
      $attributes = $product->attributesWithValues;

      $html = '';

      foreach ($attributes as $attribute) {
        // passer les attributs à la vue
        $html .= view('admin.product.partials.attribute', compact('attribute', 'product'))->render();
      }

      return response()->json([
        'message' => 'Attributes deleted successfully.',
        'html' => $html
      ]);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage()], 500);
    }
  }
}




















