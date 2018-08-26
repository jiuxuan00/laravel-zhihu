<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    //
    protected $table = 'answers';

    //回答问题
    public function add()
    {
        //检查是否登录
        if (!user_ins()->isLoginedIn())
            return ['status'=>0, 'msg'=>'请登录'];
        
        //检测上传的内容是否包含id和content
        if (!rq('question_id') || !rq('content')) 
            return ['status'=>0, 'msg'=>'问题id和内容不能为空'];
        
        //检测数据库中是否含有此id的问题
        $question = question_ins()->find(rq('question_id'));
        if(!$question)
            return ['status'=>0, 'msg'=>'此问题不存在'];

        //验证当前用户是否回答过此问题
        $answered = $this->where(['user_id'=>session('user_id'), 'question_id'=>rq('question_id')])->count();
        if($answered)
            return ['status'=>0, 'msg'=>'你已经回答过此问题了，请不要重复提交'];            
        
        //保存数据
        $this->content = rq('content');
        $this->question_id = rq('question_id');
        $this->user_id = session('user_id');

        if($this->save()){
            return ['status'=>1, 'msg'=>'问题回答成功'];
        }else {
            return ['status'=>0, 'msg'=>'插入数据库失败'];

        }
    }

    //编辑问题
    public function read()
    {
        //检查是否有id和question_id
        if(!rq('id') && !rq('question_id'))
            return ['status'=>0, 'msg'=>'id和question_id错误'];

        if(rq('id')) {
            //查看单个回答
            $answer = $this->find(rq('id'));
            if(!$answer)
                return ['status'=>0, 'msg'=>'你查看的回答不存在'];
            return ['status'=>1, 'data'=>$answer];
        }
    
        //查看问题前检测问题是否存在
        if(!question_ins()->find($rq('question_id')))
            return ['status'=>0, 'msg'=>'你查看的问题不存在'];

    }
}
