<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 7/4/2018
 * Time: 5:10 AM
 */

namespace App\Admin\Controller;

use App\AdminPermission;
use App\AdminRole;
class RoleController extends Controller
{
    // 角色列表
    public function index()
    {
        $roles = AdminRole::paginate(10);
        return view('admin.role.index', compact('roles'));
    }

    // 创建角色
    public function create()
    {
        return view('admin.role.add');
    }

    // 存储角色
    public function store()
    {
        $this->validate(request(), [
            'name'        => 'required|min:3',
            'description' => 'required',
        ]);
        AdminRole::create(request(['name', 'description']));
        return redirect('/admin/roles');
    }

    // 角色权限关系
    public function permission(AdminRole $role)
    {
        $permissions = AdminPermission::all();
        $myPermissions = $role->permissions;
        return view('admin.role.permission', compact('permissions', 'myPermissions', 'role'));
    }

    // 存储角色权限关系
    public function storePermission(AdminRole $role)
    {
        $this->validate(request(), [
            'permissions' => 'required|array',
        ]);
        $permissions = \App\AdminPermission::findMany(request('permissions'));
        $myPermissions = $role->permissions;
        $addPermissions = $permissions->diff($myPermissions);
        foreach ($addPermissions as $permission) {
            $role->grantPermission($permission);
        }
        $deletePermissions = $myPermissions->diff($permissions);
        foreach ($deletePermissions as $permission) {
            $role->deletePermission($permission);
        }
        return back();
    }
}