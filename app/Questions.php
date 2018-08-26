<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $table = 'questions';
    // public $timestamps = false;

    //新增
    public function add()
    {
        /*检测是否登录*/
        if (!user_ins()->isLoginedIn()) {
            return ['status' => 0, 'msg' => 'login required'];
        }

        /*检测标题存在不存在*/
        if (!rq('title')) {
            return ['status' => 0, 'msg' => 'required title'];
        }

        $this->title = rq('title');
        $this->user_id = session('user_id');
        /*检测描述是否存在*/
        if (rq('desc'))
            $this->desc = rq('desc');

        if ($this->save()) {
            return ['status' => 1, 'id' => $this->id,'msg'=>'保存数据库成功'];
        } else {
            return ['status' => 0, 'msg'=>'保存数据库失败'];
        }

    }

    //更新
    public function change()
    {
        /*检测是否登录*/
        if (!user_ins()->isLoginedIn()) {
            return ['status' => 0, 'msg' => 'login is required'];
        }

        //检测是否存在id
        if(!rq('id')){
            return ['status' => 0, 'msg' => 'id is required'];
        }


        //通过id获取当前用户的信息
        $question = $this->find(rq('id'));

        //判断当前问题存在不存在
        if($question)
            return ['status'=>0, 'msg'=>'question not exists'];

        //如果当前用户和发帖用户id不一致的话是没有权限进行此操作的
        if($question->user_id != session('user_id')){
            return ['status' => 0, 'msg' => 'permission denied'];
        }

        //如果修改了title就更新
        if(rq('title')){
            $question->title = rq('title');
        }

        //如果修改了描述就更新
        if(rq('desc')){
            $question->desc = rq('desc');
        }


        if($question->save()){
            return ['status' => 1, 'msg' => 'success'];
        }else {
            return ['status' => 0, 'msg' => 'failed'];
        }

    }

    //查看
    public function read()
    {
        $id = rq('id');
        if($id){
            return [
                'status'=> 1,
                'data' => $this->find($id)
            ];
        }

        $limit = rq('limit') ?: 15;
        $skip = (rq('page')?:1 - 1) * $limit;

        return [
            'status'=> 1,
            'data' => $this->orderBy('updated_at')->limit($limit)->skip($skip)->get()
        ];
        

    }

    // 删除
    public function remove()
    {
        //检查用户是否登录
        if(!user_ins()->isLoginedIn())
            return ['status'=>0, 'msg'=>'请登录'];


        //检查传参中是否有id
        if(!rq('id'))
            return ['status'=>0, 'msg'=>'id错误'];


        //检查问题是否存在
        $question = $this->find(rq('id'));
        if(!$question)
            return ['status'=>0, 'msg'=>'删除的问题不存在'];

        //检测当前用户是否有权限删除请求的问题
        if(session('user_id') != $question->user_id)
            return ['status'=>0, 'msg'=>'你没有权限删除此问题'];


        if($question->delete()){
            return ['status'=>1, 'msg'=>'删除的问题成功'];
        }else {
            return ['status'=>0, 'msg'=>'删除的问题失败'];
        }
    }
}
