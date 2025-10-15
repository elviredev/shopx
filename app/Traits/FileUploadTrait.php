<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

trait FileUploadTrait
{
  public function uploadFile(UploadedFile $file, ?string $oldPath = null, ?string $path = 'uploads') : ?string
  {
    if (!$file->isValid()) {
      return null;
    }

    $ignorePath = ['/default/avatar.png'];

    if ($oldPath && File::exists(public_path($oldPath)) && !in_array($oldPath, $ignorePath)) {
      File::delete(public_path($oldPath));
    }

    $folderPath = public_path($path);
    // s'assurer que le dossier existe
    if (!file_exists($folderPath)) {
      mkdir($folderPath, 0755, true);
    }

    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
    $file->move($folderPath, $filename);

    return $path . '/' . $filename;
  }
}
