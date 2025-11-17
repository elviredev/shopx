<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

trait FileUploadTrait
{
  /**
   * @desc Upload un fichier non sensible dans le dossier public/uploads
   * @param UploadedFile $file
   * @param string|null $oldPath
   * @param string|null $path
   * @return string|null
   */
  public function uploadFile(UploadedFile $file, ?string $oldPath = null, ?string $path = 'uploads') : ?string
  {
    if (!$file->isValid()) {
      return null;
    }

    $ignorePath = ['/default/avatar.png', '/default/banner.png', '/default/shop.png'];

    // supprimer l'ancien fichier s'il existe
    if ($oldPath && File::exists(public_path($oldPath)) && !in_array($oldPath, $ignorePath)) {
      File::delete(public_path($oldPath));
    }

    $folderPath = public_path($path);
    // s'assurer que le dossier existe sinon le créé (/public/uploads)
    if (!file_exists($folderPath)) {
      mkdir($folderPath, 0755, true);
    }

    // générer un nom unique
    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
    // déplacer le fichier vers le dossier uploads
    $file->move($folderPath, $filename);

    return $path . '/' . $filename;
  }

  public function uploadPrivateFile(UploadedFile $file, ?string $oldPath = null, ?string $path = 'uploads') : ?string
  {
    if (!$file->isValid()) {
      return null;
    }

//    $ignorePath = ['/default/avatar.png'];

//    if ($oldPath && File::exists(public_path($oldPath)) && !in_array($oldPath, $ignorePath)) {
//      File::delete(public_path($oldPath));
//    }

    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

    $path = $file->storeAs($path, $filename, 'local');

    return $path;
  }

  public function deleteFile(string $path) : bool
  {
    if (File::exists(public_path($path))) {
      File::delete(public_path($path));
      return true;
    }
    return false;
  }
}
