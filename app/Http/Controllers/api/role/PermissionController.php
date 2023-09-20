<?php

namespace App\Http\Controllers\api\role;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use App\Http\Controllers\api\Controller;
use App\Helpers\Main;
use App\Helpers\ResponseFormatter;

class PermissionController extends Controller
{
    public function createPermission(Request $request)
    {
        $validator = Main::validator($request, [
            'rules'=>[
                'name' => 'required|unique:permissions',
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        }

        $permission = Permission::create(['name' => $request->name, 'guard_name' => 'api']);

        return ResponseFormatter::success(__('messages.permission_created'));
    }

    public function assignPermissionToRole(Request $request)
    {
        $validator = Main::validator($request, [
            'rules'=>[
                'role' => 'required|exists:roles,name,guard_name,api',
                'permission' => 'required|exists:permissions,name,guard_name,api', 
            ],
            'messages'=>[
                // 'permission.exists' => 'The permission you selected was not found.',
                'exists' => 'The :attribute you selected was not found.',
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        }
        
        $role = Role::findByName($request->role, 'api');
        $permission = Permission::findByName($request->permission, 'api');

        if ($role->hasPermissionTo($permission->name)) {
            return ResponseFormatter::error(422, __('messages.already_has_permission')); 
        }

        $role->givePermissionTo($permission);

        return ResponseFormatter::success(__('messages.permission_assigned')); 
    }
}