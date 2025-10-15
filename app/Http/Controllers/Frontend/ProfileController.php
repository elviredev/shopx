<?php

namespace App\Http\Controllers\Frontend;

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
   * @desc Afficher la page Profil Utilisateur
   * @return View
   */
  public function index() : View
  {
    return view('frontend.dashboard.account.index');
  }

  /**
   * @desc Modifier les infos du profil utilisateur
   * @param Request $request
   * @return RedirectResponse
   */
  public function profileUpdate(Request $request) : RedirectResponse
  {
    $request->validate([
      'name' => ['required', 'string', 'max:50'],
      'email' => ['required', 'email', 'unique:users,email,'.auth('web')->user()->id],
      'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
    ]);

    $user = auth('web')->user();

    // Trait FileUploadTrait
    $filePath = $this->uploadFile($request->file('avatar'), $user->avatar);

    $filePath ? $user->avatar = $filePath : null;
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
  public function passwordUpdate(Request $request) : RedirectResponse
  {
    $request->validate([
      'current_password' => ['required', 'string', 'current_password'],
      'password' => ['required', 'string', 'confirmed', 'min:8'],
    ]);

    $user = auth('web')->user();
    $user->password = bcrypt($request->password);
    $user->save();

    AlertService::updated();
    return redirect()->back();
  }
}
