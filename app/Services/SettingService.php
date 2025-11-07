<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
  /**
   * @desc Récupère et met en cache les settings (chargement initial ou lecture)
   * @return mixed
   */
  function getSettings()
  {
    return Cache::rememberForever('settings', function () {
      return Setting::pluck('value', 'key')->toArray();
    });
  }

  /**
   * @desc Injecte les settings dans config()
   * Après le chargement (ex: dans un ServiceProvider)
   * @return void
   */
  function setSettings()
  {
    $settings = $this->getSettings();
    config()->set('settings', $settings);
  }

  /**
   * @desc Vider le cache des settings (après modification des settings)
   * @return void
   */
  function clearCachedSettings()
  {
    Cache::forget('settings');
  }
}
