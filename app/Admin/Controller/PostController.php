<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 7/4/2018
 * Time: 5:10 AM
 */

namespace App\Admin\Controller;

use App\Post;
class PostController extends Controller
{
    // 管理员后台文章列表
    public function index()
    {
        $posts = Post::withoutGlobalScope('available')->where('status', 0)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.post.index', compact('posts'));
    }

    // 文章状态管理
    public function status(Post $post)
    {
        $this->validate(request(), [
            'status' => 'required|in:-1,1',
        ]);
        $post->status = request('status');
        $post->save();
        return ['error' => 0, 'msg' => ''];
    }
}