<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    //
    protected $table = 'comments';

    //添加评论
    public function add()
    {
        //检查是否登录
        if (!user_ins()->isLoginedIn()) 
            return ['status'=>0, 'msg'=>'请登录'];

        //判断提交的数据是否有content
        if(!rq('content'))
            return ['status'=>0, 'msg'=>'评论不能为空'];

        //判断问题的id和回复的id是否存在
        //不能同时存在或 不能都不存在
        if((!rq('question_id') && !rq('answer_id')) || rq('question_id') && rq('answer_id'))
            return ['status'=>0, 'msg'=>'question_id或answer_id不存在'];

        //判断
        if(rq('question_id')){
            $question = question_ins()->find(rq('question_id'));
            if(!$question)
                return ['status'=>0, 'msg'=>'问题不存在'];
            $this->question_id = rq('question_id');
        }else {
            $answer = answer_ins()->find(rq('answer_id'));
            if (!$answer) {
                return ['status'=>0, 'msg'=>'回复不存在'];
            }
            $this->answer_id = rq('answer_id');
        }

        //判断replay_to
        if(rq('replay_to')) {
            $target = $this->find(rq('replay_to'));
            if(!$target)
                return ['status'=>0, 'msg'=>'replay_to不存在'];
            if($target->user_id == session('user_id'))
                return ['status'=>0, 'msg'=>'你不能回复自己的问题'];
            $this->replay_to = rq('replay_to');
        }
        $this->content = rq('content');
        $this->user_id = session('user_id');

        if($this->save()){
            return ['status'=>1, 'id'=>$this->id, 'msg'=>'评论成功'];
        }else {
            return ['status'=>0, 'msg'=>'评论失败'];
        }
    }

    //查看评论
    public function read ()
    {
        if(!rq('question_id') && !rq('answer_id'))
            return ['status'=>0, 'msg'=>'question_id或answer_id必传'];
        
        if(rq('question_id')){
            $question = question_ins()->find(rq('question_id'));
            if(!$question)
                return ['status'=>0, 'msg'=>'question_id不存在'];
            $data = $this->where('question_id',rq('question_id'))->get();
        } else {
            $answer = answer_ins()->find(rq('answer_id'));
            if (!$answer) 
                return ['status'=>0, 'msg'=>'answer_id不存在'];
            $data = $this->where('answer_id', rq('answer_id'))>get();
        }
        return ['status'=>1, 'data'=>$data];
    }

    //删除评论
    public function remove()
    {
        //检查是否登录
        if (!user_ins()->isLoginedIn()) 
            return ['status'=>0, 'msg'=>'请登录'];
        //检测是否存在id
        if(!rq('id'))
            return ['status'=>0, 'msg'=>'id必传'];
        $comment = $this->find(rq('id'));
        if(!$comment)
            return ['status'=>0, 'msg'=>'评论不存在'];
        //检测是否有权限删除此评论
        if($comment->user_id != session('user_id'))
            return ['status'=>0, 'msg'=>'你没有权限删除此评论'];
        
        //删除此评论下的所有的回复
        $this->where('replay_to',rq('id'))->delete();

        if($comment->delete()){
            return ['status'=>1, 'msg'=>'删除评论成功'];
        }else {
            return ['status'=>0, 'msg'=>'删除权限失败'];
        }



    }
}
