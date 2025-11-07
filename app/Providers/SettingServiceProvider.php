<?php

namespace App\Providers;

use App\Services\SettingService;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
  /**
   * Enregistre le service
   */
  public function register(): void
  {
    $this->app->singleton(SettingService::class, fn () => new SettingService());
  }

  /**
   * Initialise le service
   */
  public function boot(): void
  {
    $settings = $this->app->make(SettingService::class);
    $settings->setSettings();
  }
}
