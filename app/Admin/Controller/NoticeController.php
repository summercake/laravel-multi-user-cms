<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 7/4/2018
 * Time: 5:10 AM
 */

namespace App\Admin\Controller;

use App\Notice;
class NoticeController extends Controller
{
    //
    public function index()
    {
        $notices = Notice::all();
        return view('admin.notice.index', compact('notices'));
    }

    //
    public function create()
    {
        return view('admin.notice.create');
    }

    //
    public function store()
    {
        $this->validate(request(), [
            'title'   => 'required|string',
            'content' => 'required|string',
        ]);
        $notice = Notice::create(request(['title', 'content']));
        dispatch(new \App\Jobs\SendMessage($notice));
        return redirect('/admin/notices');
    }
}