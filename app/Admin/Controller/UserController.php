<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 7/4/2018
 * Time: 5:10 AM
 */

namespace App\Admin\Controller;

use App\AdminRole;
use App\AdminUser;
class UserController extends Controller
{
    // 管理员列表
    public function index()
    {
        $users = AdminUser::paginate(10);
        return view('admin.user.index', compact('users'));
    }

    // 管理员创建页面
    public function create()
    {
        return view('admin.user.add');
    }

    // 存储管理员信息
    public function store()
    {
        $this->validate(request(), [
            'name'     => 'required|min:3',
            'password' => 'required',
        ]);
        $name = request('name');
        $password = bcrypt(request('password'));
        AdminUser::create(compact('name', 'password'));
        return redirect("/admin/users");
    }

    // 用户角色页面
    public function role(AdminUser $user)
    {
        $roles = \App\AdminRole::all();
        $myRoles = $user->roles;
        return view('admin.user.role', compact('roles', 'myRoles', 'user'));
    }

    // 存储用户角色
    public function storeRole(AdminUser $user)
    {
        $this->validate(request(), [
            'roles' => 'required|array',
        ]);
        $roles = AdminRole::findMany(request('roles'));
        $myRoles = $user->roles;
        // 要增加的
        $addRoles = $roles->diff($myRoles);
        foreach ($addRoles as $role) {
            $user->assignRole($role);
        }
        // 要删除的
        $deleteRoles = $myRoles->diff($roles);
        foreach ($deleteRoles as $role) {
            $user->deleteRole($role);
        }
        return back();
    }
}