<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AlertService;
use App\Traits\FileUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
  use FileUploadTrait;

  /**
   * @desc Afficher la page de modification de profile de Admin
   * @return View
   */
  public function index(): View
  {
    return view('admin.profile.index');
  }

  /**
   * @desc Modifier les infos du profil admin
   * @param Request $request
   * @return RedirectResponse
   */
  public function profileUpdate(Request $request): RedirectResponse
  {
    $request->validate([
      'name' => ['required', 'string', 'max:50'],
      'email' => ['required', 'email', 'unique:admins,email,'.auth('admin')->user()->id],
      'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
    ]);

    $user = auth('admin')->user();

    if ($request->hasFile('avatar')) {
      $filePath = $this->uploadFile($request->file('avatar'), $user->avatar);
      $filePath ? $user->avatar = $filePath : null;
    }

    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    AlertService::updated();

    return redirect()->back();
  }

  /**
   * @desc Modifier le mot de passe du profil utilisateur
   * @param Request $request
   * @return RedirectResponse
   */
  public function passwordUpdate(Request $request): RedirectResponse
  {
    $request->validate([
      'current_password' => ['required', 'string', 'current_password:admin'],
      'password' => ['required', 'string', 'confirmed', 'min:8'],
    ]);

    $user = auth('admin')->user();
    $user->password = bcrypt($request->password);
    $user->save();

    AlertService::updated();
    return redirect()->back();
  }
}








