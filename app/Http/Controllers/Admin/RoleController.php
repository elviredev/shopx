<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AlertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
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
  public function destroy(Role $role)
  {
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
