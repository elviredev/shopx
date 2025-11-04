<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AlertService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
  /**
   * @desc Middleware pour vérifier l'autorisation d'accès aux méthodes du controller
   * si user n'a pas la permission, il ne pourra pas accèder aux routes et vues du controller
   * @return Middleware[]
   */
  static function Middleware(): array
  {
    return [
      new Middleware('permission:Role Management')
    ];
  }

  /**
   * @desc Afficher la page des roles avec
   * le nombre de permissions par role
   */
  public function index()
  {
    $roles = Role::withCount('permissions')->get();
    return view('admin.role.index', compact('roles'));
  }

  /**
   * @desc Afficher le formulaire de création d'un role et
   * lister les permissions par group_name
   */
  public function create()
  {
    $permissions = Permission::all()->groupBy('group_name');
    return view('admin.role.create', compact('permissions'));
  }

  /**
   * @desc Enregistrer un role en bdd et
   * lui assigner les permissions sélectionnées
   */
  public function store(Request $request)
  {
    $request->validate([
      'role' => ['required', 'string', 'max:255', 'unique:roles,name'],
      'permissions' => ['required', 'array'],
    ]);

    // créer le role
    $role = Role::create(['name' => $request->role]);
    // assigner permissions au role
    $role->syncPermissions($request->permissions);

    AlertService::created();

    return to_route('admin.role.index');
  }

  /**
   * @desc Afficher le formulaire de mise à jour d'un role
   * et ses permissions associées
   */
  public function edit(Role $role)
  {
    $permissions = Permission::all()->groupBy('group_name');
    return view('admin.role.edit', compact('role', 'permissions'));
  }

  /**
   * @desc Mettre à jour un role et ses permissions
   */
  public function update(Request $request, Role $role)
  {
    // check if role is super admin
    if($role->name == 'Super Admin') {
      AlertService::error('You can not update Super Admin role.');
      return to_route('admin.role.index');
    }

    $request->validate([
      'role' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
      'permissions' => ['required', 'array'],
    ]);

    $role->update(['name' => $request->role]);
    $role->syncPermissions($request->permissions);

    AlertService::updated();

    return to_route('admin.role.index');
  }

  /**
   * @desc Supprimer un role et ses permissions
   */
  public function destroy(Role $role): JsonResponse
  {
    // check if role is super admin
    if($role->name == 'Super Admin') {
      return response()->json(['status' => 'error', 'message' => 'You can not delete Super Admin role.']);
    }

    try {
      DB::beginTransaction();
      // detach role of users
      $role->users()->detach();
      //detach permissions from role
      $role->permissions()->detach();
      //delete role
      $role->delete();
      DB::commit();

      AlertService::deleted();
      return response()->json(['status' => 'success', 'message' => 'Deleted Successfully']);

    } catch (\Throwable $th) {
      DB::rollBack();
      Log::error('Role Delete Error: ' . $th);

      return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
    }
  }
}
