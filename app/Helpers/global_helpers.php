<?php
/** Check user has permissions */

use App\Models\User;
use Illuminate\Support\Facades\Auth;

if (!function_exists('hasPermission')) {
  function hasPermission(array $permissions): bool
  {
    if (auth('admin')->user()->hasRole('Super Admin')) return true;

    return auth('admin')->user()->hasAnyPermission($permissions);
  }
}

/** Get user */
if (!function_exists('user')) {
  function user(): User
  {
    return Auth::user('web');
  }
}
