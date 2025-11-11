<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\AlertService;
use App\Traits\FileUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class BrandController extends Controller implements HasMiddleware
{
  use FileUploadTrait;

  /**
   * @desc Middleware pour vérifier l'autorisation d'accès aux méthodes du controller
   * si user n'a pas la permission, il ne pourra pas accèder aux routes et vues du controller
   * @return Middleware[]
   */
  static function Middleware(): array
  {
    return [
      new Middleware('permission:Brand Management')
    ];
  }

  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    $brands = Brand::paginate(20);
    return view('admin.brand.index', compact('brands'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    return view('admin.brand.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'brand_logo' => ['required', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
      'name' => ['required', 'string', 'max:255']
    ]);

    $logoPath = $this->uploadFile($request->file('brand_logo'));

    $brand = new Brand();
    $brand->name = $request->name;
    $brand->slug = Str::slug($request->name);
    $brand->image = $logoPath;
    $brand->is_active = $request->has('status') ? 1 : 0;
    $brand->save();

    AlertService::created();
    return to_route('admin.brands.index');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Brand $brand)
  {
    return view('admin.brand.edit', compact('brand'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Brand $brand)
  {
    $request->validate([
      'brand_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
      'name' => ['required', 'string', 'max:255']
    ]);

    // update logo
    if ($request->hasFile('brand_logo')) {
      $logoPath = $this->uploadFile($request->file('brand_logo'), $brand->image);
      $brand->image = $logoPath;
    }

    $brand->name = $request->name;
    $brand->slug = Str::slug($request->name);
    $brand->is_active = $request->has('status') ? 1 : 0;
    $brand->save();

    AlertService::updated();
    return to_route('admin.brands.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Brand $brand)
  {
    // delete logo
    $this->deleteFile($brand->image);

    // delete brand
    $brand->delete();
    AlertService::deleted();
    return response()->json([
      'status' => 'success',
      'message' => 'Brand deleted successfully.'
    ]);
  }
}
