<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\Like;
use Illuminate\Http\Request;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$user = \Auth::user();
        $posts = Post::orderBy('created_at', 'desc')->withCount(['comments', 'likes'])->with('user')->paginate(6);
        //$posts->load('user');
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 验证
        $this->validate(request(), [
            'title'   => 'required|string|max:100|min:5',
            'content' => 'required|string|min:10',
        ]);
        // 逻辑
        $user_id = \Auth::id();
        $params = array_merge(request(['title', 'content']), ['user_id' => $user_id]);
        //dd($params);
        Post::create($params);
        // 渲染
        return redirect('/posts');
    }

    public function show(Post $post)
    {
        $post->load('comments');
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\r $r
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\r $r
     * @return \Illuminate\Http\Response
     */
    public function update(Post $post)
    {
        // 权限验证
        $this->authorize('update', $post);
        // 验证
        $this->validate(request(), [
            'title'   => 'required|string|max:100|min:5',
            'content' => 'required|string|min:10',
        ]);
        // 逻辑
        $post->title = request('title');
        $post->content = request('content');
        $post->save();
        // 渲染
        return redirect("/posts/{$post->id}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\r $r
     * @return \Illuminate\Http\Response
     */
    public function delete(Post $post)
    {
        // 权限验证
        $this->authorize('delete', $post);
        $post->delete();
        return redirect('/posts');
    }

    // 图片上传
    public function imageUpload(Request $request)
    {
        $path = $request->file('wangEditorH5File')->storePublicly(md5(time()));
        return asset('storage/'.$path);
    }

    // 提交评论
    public function comment(Post $post)
    {
        // 验证
        $this->validate(request(), [
            'content' => 'required|min:3',
        ]);
        // 逻辑
        $comment = new Comment();
        $comment->user_id = \Auth::user()->id;
        $comment->content = request('content');
        $post->comments()->save($comment);
        // 渲染
        return back();
    }

    public function like(Post $post)
    {
        $param = [
            'user_id' => \Auth::user()->id,
            'post_id' => $post->id,
        ];
        // firstOrCreate 如何表中没有，则创建， 避免重复创建
        Like::firstOrCreate($param);
        return back();
    }

    public function unlike(Post $post)
    {
        $post->like(\Auth::id())->delete();
        return back();
    }

    // 搜索结果页
    public function search()
    {
        // 验证
        $this->validate(request(), [
            'query' => 'required',
        ]);
        // 逻辑
        $query = request('query');
        $posts = \App\Post::search($query)->paginate(2);
        // 渲染
        return view('posts.search', compact('posts', 'query'));
    }


}
