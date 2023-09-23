<?php

namespace App\Http\Controllers\api\role;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

use App\Http\Controllers\api\Controller;
use App\Helpers\Main;
use App\Models\User;
use App\Helpers\ResponseFormatter;

class RoleController extends Controller
{
    public function createRole(Request $request)
    {
        $validator = Main::validator($request, [
            'rules'=>[
                'name' => 'required|unique:roles',
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        }
        $res = new ResponseFormatter;

        $role = Role::create(['name' => $request->name, 'guard_name' => 'api']);

        return $res::success(__('messages.role_created'));
    }

    public function assignRoleToUser(Request $request)
    {
        $validator = Main::validator($request, [
            'rules'=>[
                'role' => 'required|exists:roles,name,guard_name,api',
                'user' => 'required|exists:users,email',
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        }
        $res = new ResponseFormatter;

        $user = User::where('email', $request->user)->first();
        $role = Role::findByName($request->role, 'api');

        if ($user->hasRole($role->name)) {
            return $res::error(422, __('messages.already_has_role'), $res::traceCode('ROLE001')); 
        }
        
        $user->assignRole($role);

        return $res::success(__('messages.role_assigned'));
    }

}