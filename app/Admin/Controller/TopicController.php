<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 7/4/2018
 * Time: 5:10 AM
 */

namespace App\Admin\Controller;

use App\Topic;
class TopicController extends Controller
{
    //
    public function index()
    {
        $topics = Topic::all();
        return view('admin.topic.index', compact('topics'));
    }

    //
    public function create()
    {
        return view('admin.topic.create');
    }

    //
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|string',
        ]);
        Topic::create(['name' => request('name')]);
        return redirect('/admin/topics');
    }

    //
    public function destroy(Topic $topic)
    {
        $topic->delete();
        return [
            'error' => 0,
            'msg'   => '',
        ];
    }
}