<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 7/4/2018
 * Time: 5:10 AM
 */

namespace App\Admin\Controller;

use App\AdminPermission;
class PermissionController extends Controller
{
    // 权限列表页面
    public function index()
    {
        $permissions = AdminPermission::paginate(10);
        return view('admin.permission.index', compact('permissions'));
    }

    // 创建权限的页面
    public function create()
    {
        return view('admin.permission.add');
    }

    // 存储创建的权限
    public function store()
    {
        $this->validate(request(), [
            'name'        => 'required|min:3',
            'description' => 'required',
        ]);
        AdminPermission::create(request(['name', 'description']));
        return redirect('/admin/permissions');
    }
}