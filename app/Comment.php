<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Comment extends Model
{
    protected $fillable = ['content', 'user_id', 'post_id'];

    // 关联文章
    public function post()
    {
        return $this->belongsTo('App\Post', 'post_id', 'id');
    }

    // 关联用户
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
