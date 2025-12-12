<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ProductStoreRequest;
use App\Http\Requests\Frontend\ProductUpdateRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFile;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\Tag;
use App\Services\AlertService;
use App\Traits\FileUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorProductController extends Controller
{
  use FileUploadTrait;

  /** =================== Product CRUD ==================== */

  public function index(): View
  {
    $products = Product::with(['store'])->where('store_id', user()->store->id)->latest()->paginate(30);
    return view('vendor-dashboard.product.index', compact('products'));
  }

  public function create(): View
  {
    $stores = Store::select(['id', 'name'])->get();
    $brands = Brand::select(['id', 'name'])->where('is_active', 1)->get();
    $tags = Tag::where('is_active', 1)->get();
    $categories = Category::getNested();

    return view('vendor-dashboard.product.create', compact('stores', 'brands', 'tags', 'categories'));
  }

  public function store(ProductStoreRequest $request, string $type)
  {
    if (!in_array($type, ['physical', 'digital'])) abort(404);

    $product = new Product();
    $product->name = $request->name;
    $product->slug = $request->slug;
    $product->product_type = $type;
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
    $product->approved_status = 'pending';
    $product->store_id = user()->store->id;
    $product->brand_id = $request->brand;
    $product->is_featured = $request->has('is_featured') ? 1 : 0;
    $product->is_hot = $request->has('is_hot') ? 1 : 0;
    $product->is_new = $request->has('is_new') ? 1 : 0;
    $product->save();

    /** Attach categories to the product */
    $product->categories()->sync($request->categories);

    /** Attach tags to the product */
    $product->tags()->sync($request->tags);

    if ($type == 'physical') {
      return response()->json([
        'id' => $product->id,
        'redirect_url' => route('vendor.products.edit', $product->id) .'#product-images',
        'status' => 'success',
        'message' => 'Product created successfully.',
      ]);
    } else {
      return response()->json([
        'id' => $product->id,
        'redirect_url' => route('vendor.digital-products.edit', $product->id) .'#product-images',
        'status' => 'success',
        'message' => 'Digital product created successfully.',
      ]);
    }
  }

  public function edit(int $id)
  {
    $product = Product::findOrFail($id);
    if($product->store_id !== user()->store->id) abort(404);

    $productCategoryIds = $product->categories->pluck('id')->toArray();
    $productTagsIds = $product->tags->pluck('id')->toArray();
    $stores = Store::select(['id', 'name'])->get();
    $brands = Brand::select(['id', 'name'])->where('is_active', 1)->get();
    $tags = Tag::where('is_active', 1)->get();
    $categories = Category::getNested();

    $attributesWithValues = $product?->attributesWithValues ?? [];
    $variants = $product?->variants ?? [];

    return view('vendor-dashboard.product.edit', compact('stores', 'brands', 'tags', 'categories', 'product', 'productCategoryIds', 'productTagsIds', 'attributesWithValues', 'variants'));
  }

  public function editDigitalProduct(int $id)
  {
    $product = Product::findOrFail($id);
    if ($product->product_type != 'digital') abort(404);
    if($product->store_id !== user()->store->id) abort(404);

    $productCategoryIds = $product->categories->pluck('id')->toArray();
    $productTagsIds = $product->tags->pluck('id')->toArray();
    $stores = Store::select(['id', 'name'])->get();
    $brands = Brand::select(['id', 'name'])->where('is_active', 1)->get();
    $tags = Tag::where('is_active', 1)->get();
    $categories = Category::getNested();

    return view('vendor-dashboard.product.digital-edit', compact('stores', 'brands', 'tags', 'categories', 'product', 'productCategoryIds', 'productTagsIds'));
  }

  /**
   * @desc Upload digital product file
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function uploadDigitalProductFile(Request $request)
  {
    // seul le propriétaire du produit peut charger les fichiers
    $product = Product::findOrFail($request->product_id);
    if($product->store_id !== user()->store->id) abort(404);

    // récupérer les éléments depuis la requête
    $file = $request->file('file');
    $chunkIndex = $request->dzchunkindex;
    $totalChunks = $request->dztotalchunkcount;
    $fileName = $request->name;

    // stockage des segments
    $chunkFolder = storage_path('app/private/chunks/' . $fileName);
    if (!file_exists($chunkFolder)) mkdir($chunkFolder, 0777, true);

    $chunkPath = $chunkFolder . '/' . $chunkIndex;

    // stocker les segments dans les fichiers temporaires du dossier "chunks"
    file_put_contents($chunkPath, file_get_contents($file->getRealPath()));

    /* fusionner les segments */
    // si c'est le dernier segment
    if($chunkIndex == $totalChunks - 1){
      // générer un nom de fichier unique
      $finalFileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
      // lieu de stockage du fichier final
      $finalPath = storage_path('app/private/uploads/' . $finalFileName);
      // ouvrir le fichier et l'ecrire (contenu du fichier final)
      $output = fopen($finalPath, 'ab');

      // parcourir tous les segments, les lire et écrire toutes leurs valeurs dans un seul fichier
      for($i = 0; $i < $totalChunks; $i++){
        $chunkFile = $chunkFolder . '/' . $i;
        // ouvrir le fichier et le lire (contenu du segment)
        $input = fopen($chunkFile, 'rb');
        // obtenir le contenu du fichier (input) et l'ajouter au fichier final (output)
        stream_copy_to_stream($input, $output);
        // fermer le fichier
        fclose($input);
        // dissocier le segment du fichier final
        unlink($chunkFile);
      }

      fclose($output);

      // supprimer les fichiers inutiles
      rmdir($chunkFolder);

      // validation du fichier final
      $validationResult = $this->validateFinalFile($finalPath);
      if($validationResult !== true) {
        unlink($finalPath);
        return $validationResult;
      }

      // stocker le fichier en bdd
      $this->storeDigitalFile($file, $request->product_id, $fileName, $finalFileName);

      return response()->json(['status' => 'success']);
    }

    return response()->json(['status' => 'chunk_received']);
  }


  /** Validation du fichier final ayant été téléchargé */
  public function validateFinalFile(string $finalPath)
  {
    $maxSizeMb = 1000;
    $maxSizeBytes = $maxSizeMb * 1024 * 1024;

    // vérifier taille du fichier
    if(filesize($finalPath) > $maxSizeBytes){
      return response()->json([
        'status' => 'error',
        'message' => 'File size exceeds the maximum allowed size of ' . $maxSizeMb . ' MB.'
      ], 413);
    }

    // MIME validation
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $finalPath);
    finfo_close($finfo);

    // MIME type pris en charge
    $allowedMimeTypes = [
      'jpeg' => 'image/jpeg',
      'jpg'  => 'image/jpeg',
      'png'  => 'image/png',
      'gif'  => 'image/gif',
      'webp' => 'image/webp',
      'svg'  => 'image/svg+xml',
      'bmp'  => 'image/bmp',
      'tiff' => 'image/tiff',

      'pdf'  => 'application/pdf',

      'doc'  => 'application/msword',
      'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

      'xls'  => 'application/vnd.ms-excel',
      'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

      'ppt'  => 'application/vnd.ms-powerpoint',
      'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',

      'txt'  => 'text/plain',
      'csv'  => 'text/csv',
      'md'   => 'text/markdown',

      'mp3'  => 'audio/mpeg',
      'wav'  => 'audio/wav',
      'ogg'  => 'audio/ogg',
      'm4a'  => 'audio/mp4',

      'mp4'  => 'video/mp4',
      'webm' => 'video/webm',
      'avi'  => 'video/x-msvideo',
      'mov'  => 'video/quicktime',
      'mkv'  => 'video/x-matroska',

      'zip'  => 'application/zip',
      'rar'  => 'application/vnd.rar',
      '7z'   => 'application/x-7z-compressed',
      'tar'  => 'application/x-tar',
      'gz'   => 'application/gzip',
    ];

    if (!in_array($mimeType, $allowedMimeTypes)) {
      return response()->json(['status' => 'error', 'message' => 'Invalid file type'], 415);
    }

    return true;
  }

  /**
   * @desc Store digital product file
   * @param $file
   * @param $product_id
   * @param $fileName
   * @param $finalFileName
   * @return void
   */
  public function storeDigitalFile($file, $product_id, $fileName, $finalFileName)
  {
    $productFile = new ProductFile();
    $productFile->product_id = $product_id;
    $productFile->filename = $fileName;
    $productFile->path = "uploads/" . $finalFileName;
    $productFile->extension = $file->getClientOriginalExtension();
    $productFile->size = $file->getSize();
    $productFile->save();
  }

  /**
   * @desc Delete digital product file
   * @param int $productId
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroyDigitalProductFile(int $productId, int $id)
  {
    try {
      // vérifier que le user connecté est bien le propriétaire du produit
      $product = Product::findOrFail($productId);
      if($product->store_id !== user()->store->id) abort(404);

      // si on trouve l'id on supprime le fichier sinon erreur 404 renvoyée au user
      $productFile = ProductFile::where('id', $id)
                      ->where('product_id', $productId)
                      ->firstOrFail();

      // delete from storage
      if(Storage::disk('local')->exists($productFile->path)) {
        Storage::disk('local')->delete($productFile->path);
      }

      $productFile->delete();

      return response()->json(['status' => 'success', 'message' => 'File deleted successfully.'], 200);
    } catch (\Exception $e) {
      logger('Failed to delete file' . $e);
      return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
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
    // vérifier que le user connecté est bien le propriétaire du produit
    if($product->store_id !== user()->store->id) abort(404);

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
    $product->store_id = user()->store->id;
    $product->brand_id = $request->brand;
    $product->is_featured = $request->has('is_featured') ? 1 : 0;
    $product->is_hot = $request->has('is_hot') ? 1 : 0;
    $product->is_new = $request->has('is_new') ? 1 : 0;
    $product->save();

    /** Attach categories to the product */
    $product->categories()->sync($request->categories);

    /** Attach tags to the product */
    $product->tags()->sync($request->tags);

    AlertService::created();

    return response()->json([
      'id' => $product->id,
      'status' => 'success',
      'message' => 'Product updated successfully.',
      'redirect_url' => route('vendor.products.index')
    ]);
  }


  /** =================== Product Image ==================== */
  public function uploadImages(Request $request, Product $product)
  {
    // vérifier que le user connecté est bien le propriétaire du produit
    if($product->store_id !== user()->store->id) abort(404);

    $request->validate([
      'image' => [ 'required', 'image', 'max:2048'],
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
      'message' => 'Image uploaded successfully.',
    ]);
  }

  public function destroyImage(int $id)
  {
    $image = ProductImage::findOrFail($id);
    $product = Product::findOrFail($image->product_id);
    // vérifier que le user connecté est bien le propriétaire du produit
    if($product->store_id !== user()->store->id) abort(404);

    $this->deleteFile($image->path);
    $image->delete();

    return response()->json([
      'status' => 'success',
      'message' => 'Image deleted successfully.',
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
    // vérifier que le user connecté est bien le propriétaire du produit
    if($product->store_id !== user()->store->id) abort(404);

    $request->validate([
      'attribute_name' => ['required', 'string', 'max:255'],
      'attribute_type' => ['required', 'string', 'in:text,color'],
    ]);

    DB::beginTransaction();

    try {
      if ($request->filled('attribute_id')) {
        $this->updateExistingAttribute($request, $product);
      } else {
        $this->createNewAttribute($request, $product);
      }

      DB::commit();

      // regenerate product variants
      $this->regenerateProductVariants($product);

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
    // vérifier que le user connecté est bien le propriétaire du produit
    if($product->store_id !== user()->store->id) abort(404);

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
    // vérifier que le user connecté est bien le propriétaire du produit
    if($product->store_id !== user()->store->id) abort(404);

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
    // vérifier que le user connecté est bien le propriétaire du produit
    if($product->store_id !== user()->store->id) abort(404);

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
    // vérifier que le user connecté est bien le propriétaire du produit
    if($product->store_id !== user()->store->id) abort(404);

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
        'attribute_value_id' => $attributeValue->id,
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
    // vérifier que le user connecté est bien le propriétaire du produit
    if($product->store_id !== user()->store->id) abort(404);

    // actualiser toutes les relations existante du produit
    $product->refresh();

    // récupérer les valeurs des attributs pour le produit passé en paramètre
    $attributes = $product->attributesWithValues;

    $html = '';
    $variantHtml = '';

    foreach ($attributes as $attribute) {
      // passer les attributs à la vue
      $html .= view('vendor-dashboard.product.partials.attribute', compact('attribute', 'product'))->render();
    }

    foreach ($product->variants as $variant) {
      $variantHtml .= view('vendor-dashboard.product.partials.variant', compact('variant'))->render();
    }

    return response()->json([
      'message' => 'Attributes generated successfully.',
      'html' => $html,
      'variantHtml' => $variantHtml,
    ]);
  }

  /**
   * @desc Supprime un attribut
   * @param int $productId
   * @param int $attributeId
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroyAttribute(int $productId, int $attributeId)
  {
    try {
      $product = Product::findOrFail($productId);
      // vérifier que le user connecté est bien le propriétaire du produit
      if($product->store_id !== user()->store->id) abort(404);

      $attribute = Attribute::findOrFail($attributeId);

      $this->clearAttributeData($attribute, $product);
      $this->regenerateProductVariants($product);

      $product->refresh();

      // récupérer les valeurs des attributs pour le produit passé en paramètre
      $attributes = $product->attributesWithValues;

      // supprimer l'attribut lui-même
      $attribute->delete();

      $html = '';
      $variantHtml = '';

      foreach ($attributes as $attribute) {
        // passer les attributs à la vue
        $html .= view('vendor-dashboard.product.partials.attribute', compact('attribute', 'product'))->render();
      }

      foreach ($product->variants as $variant) {
        $variantHtml .= view('vendor-dashboard.product.partials.variant', compact('variant'))->render();
      }

      return response()->json([
        'message' => 'Attributes deleted successfully.',
        'html' => $html,
        'variantHtml' => $variantHtml
      ]);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage()], 500);
    }
  }


  /** =================== Product Variants ==================== */

  /**
   * @desc Méthode principale qui orchestre toute la génération des variantes
   * @param Product $product
   * @return void
   * @throws \Exception
   */
  public function regenerateProductVariants(Product $product)
  {
    // vérifier que le user connecté est bien le propriétaire du produit
    if($product->store_id !== user()->store->id) abort(404);

    // Supprimer toutes les anciennes variantes du produit.
    $this->clearExistingVariants($product);

    // Récupérer les valeurs d’attributs groupées par attribut
    $attributeGroups = $this->getAttributeGroups($product);

    if ($attributeGroups->isEmpty()) {
      return;
    }

    // Créer des combinaisons possibles pour les attributs
    $combinations = $this->cartesianProduct($attributeGroups);

    // Créer réellement les variantes dans la DB
    $this->createVariantsFromCombinations($product, $combinations);
  }

  /**
   * @desc Récupérer toutes les valeurs d’attributs liées à un produit, groupées par attribut,
   * et les retourner sous forme d’une collection exploitable pour le produit cartésien.
   * - Récupère toutes les associations attribut/valeur du produi
   * - Regroupe par attribut
   * - Charge réellement les objets AttributeValue
   * - Retourne une collection prête pour la génération des combinaisons
   * @param Product $product
   * @return Collection
   */
  public function getAttributeGroups(Product $product)
  {
    // vérifier que le user connecté est bien le propriétaire du produit
    if($product->store_id !== user()->store->id) abort(404);

    $groupedAttributes = DB::table('product_attribute_values')
      ->where('product_id', $product->id)
      ->get()->groupBy('attribute_id');

    // créer une collection pour accèder facilement aux valeurs sous forme d'objets
    $attributeGroups = collect();

    foreach ($groupedAttributes as $attributeId => $items) {
      // récupérer valeurs pour chaque attribut
      $attributeValues = AttributeValue::whereIn('id', $items->pluck('attribute_value_id'))->get();
      $attributeGroups->push($attributeValues);
    }

    return $attributeGroups;
  }

  /**
   * @desc Générer toutes les combinaisons possibles des attributs.
   * @param Collection $attributeGroups
   * @return array|array[]
   */
  public function cartesianProduct(Collection $attributeGroups)
  {
    $result = [[]];

    foreach ($attributeGroups as $attributeValues) {
      $temp = [];

      foreach ($result as $resultItem) {
        foreach ($attributeValues as $attributeValue) {
          $temp[] = array_merge($resultItem, [$attributeValue]);
        }
      }

      $result = $temp;
    }

    return $result;
  }

  /**
   * @desc Créer les variantes et attacher leurs attributs
   * @param Product $product
   * @param array $combinations
   * @return void
   */
  public function createVariantsFromCombinations(Product $product, array $combinations)
  {
    foreach ($combinations as $combination) {
      $variant = $this->createSingleVariant($product, $combination);
      $this->attachAttributesToVariant($variant, $combination);
    }
  }

  /**
   * @desc Créer une variante en base
   * @param Product $product
   * @param array $combination
   * @return ProductVariant
   */
  public function createSingleVariant(Product $product, array $combination)
  {
    $variantName = collect($combination)->pluck('value')->implode('/');

    return ProductVariant::create([
      'product_id' => $product->id,
      'name' => $variantName,
      'price' => 0,
      'sku' => '',
      'qty' => 0,
      'is_active' => 1,
    ]);
  }

  /**
   * @desc Insérer dans la table pivot les liaisons entre variante et valeurs.
   * @param ProductVariant $variant
   * @param array $combination
   * @return void
   */
  public function attachAttributesToVariant(ProductVariant $variant, array $combination)
  {
    foreach ($combination as $attributeValue) {
      DB::table('product_variant_attribute_value')->insert([
        'product_variant_id' => $variant->id,
        'attribute_id' => $attributeValue->attribute_id,
        'attribute_value_id' => $attributeValue->id
      ]);
    }
  }

  /**
   * @desc Update variants
   * @param Request $request
   * @param int $product
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateVariants(Request $request, int $product)
  {
    $request->validate([
      'variant_sku' => ['nullable', 'string', 'max:255'],
      'variant_price' => ['required', 'numeric'],
      'variant_special_price' => ['nullable', 'numeric'],
      'variant_manage_stock' => ['nullable', 'boolean'],
      'variant_qty' => ['nullable', 'numeric'],
      'variant_stock_status' => ['required', 'in:in_stock,out_of_stock'],
      'variant_is_default' => ['nullable', 'boolean'],
      'variant_is_active' => ['nullable', 'boolean'],
    ]);

    $product = Product::findOrFail($product);
    $variant = ProductVariant::findOrFail($request->variant_id);

    $variant->sku = $request->variant_sku;
    $variant->price = $request->variant_price;
    $variant->special_price = $request->variant_special_price;
    $variant->manage_stock = $request->variant_manage_stock ? 1 : 0;
    $variant->qty = $request->variant_qty;
    $variant->in_stock = $request->variant_stock_status == 'in_stock' ? 1 : 0;
    $variant->is_default = $request->variant_is_default ?? 0;
    $variant->is_active = $request->variant_is_active ?? 0;
    $variant->save();

    return response()->json(['message' => 'Variant updated successfully.']);
  }

  /**
   * @desc Supprime toutes les variantes existantes pour un produit
   * @param Product $product
   * @return void
   */
  public function clearExistingVariants(Product $product)
  {
    foreach ($product->variants as $variant) {
      // suppression de la relation entre le produit et les variantes
      DB::table('product_variant_attribute_value')
        ->where('product_variant_id', $variant->id)
        ->delete();

      // suppression de la variante elle-même du produit
      $variant->delete();
    }
  }

  /**
   * @desc Delete product
   * @param Product $product
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(Product $product)
  {
    if (Auth::user()->store->id == $product->store_id) {
      $product->delete();
      notyf()->success('Product deleted successfully.');
      return response()->json(['status' => 'success', 'message' => 'Product deleted successfully.']);
    }

    notyf()->error('You do not have permission to delete this product.');
    return response()->json(['status' => 'error', 'message' => 'You do not have permission to delete this product.']);
  }
  
  
  
}




















