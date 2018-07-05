<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
class Post extends Model
{
    use Searchable;

    // 定义索引里面的type
    public function searchableAs()
    {
        return 'post';
    }

    // 定义有哪些字段需要索引
    public function toSearchableArray()
    {
        return [
            'title'   => $this->title,
            'content' => $this->content,
        ];
    }

    protected $table = 'posts';
    //protected $guarded; // 不可以注入的字段
    //protected $guarded = []; // 所有字段都可以注入
    protected $fillable = ['title', 'content', 'user_id', 'status']; // 可以注入的字段

    // 关联用户
    public function user()
    {
        /**
         * user_id 为 Post 的外键
         * id 为 User 的主键
         */
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    // 关联评论
    public function comments()
    {
        return $this->hasMany('App\Comment', 'post_id', 'id')->orderBy('created_at', 'desc');
    }

    // 关联点赞
    public function like($user_id)
    {
        return $this->hasOne(\App\Like::class)->where('user_id', $user_id);
    }

    // 关联点赞
    public function likes()
    {
        return $this->hasMany(\App\Like::class);
    }

    // 属于某个专题的文章
    public function scopeAuthorBy($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function postTopics()
    {
        return $this->hasMany(\App\PostTopic::class, 'post_id', 'id');
    }

    // 不属于某个专题的文章
    public function scopeTopicNotBy($query, $topic_id)
    {
        return $query->doesntHave('postTopics', 'and', function ($q) use ($topic_id){
            $q->where('topic_id', $topic_id);
        });
    }

    // 使用全局scope验证页面内容
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('available', function ($builder){
            $builder->whereIn('status', [0, 1]);
        });
    }
}
