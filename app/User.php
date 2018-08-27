<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Request;


class User extends Model
{

    protected $table = 'users';
    // public $timestamps = false;

    //注册
    public function signUp()
    {
        $check = $this->hasUsernameAndPassword();
        if (!$check) //不存在
            return err('用户名和密码都不能为空');

        $username = $check[0];
        $password = $check[1];

        /*2.检查用户名是否存在*/
        $user_exists = $this->where('username', $username)->exists();
        if ($user_exists) 
            return err('用户名已存在');

        /*3.加密密码*/
        $hased_passwod = bcrypt($password);

        /*4.存入数据库*/
        $user = $this;
        $user->username = $username;
        $user->password = $hased_passwod;
        if ($user->save()) 
            return suc(['id' => $user->id,'msg' => '保存成功']);
        return err('保存失败');
    }

    //登录
    public function login()
    {
        // 判断输入是否正确
        $check = $this->hasUsernameAndPassword();
        if (!$check) 
            return err('用户名和密码都不能为空');
        
        $username = $check[0];
        $password = $check[1];

        //检查用户是否存在
        $user = $this->where('username', $username)->first();
        if (!$user) 
            return err('用户名不存在');

        // 检查密码是否正确
        $hased_passwod = $user->password;
        if (!Hash::check($password, $hased_passwod)) {//不正确
            return err('密码有误');
        } else {
            session()->put('username', $user->username);
            session()->put('user_id', $user->id);
            return suc(['id' => $user->id,'msg' => '登录成功']);
        }

    }

    //退出
    public function logout()
    {
        //请开工session
        session()->forget('username');
        session()->forget('user_id');
        return suc(['msg'=>'退出成功']);
    }

    //检测用户是否登录
    public function isLoginedIn()
    {
        return session('user_id') ?:false;
    }

    //修改密码api
    public function changePassword()
    {
        
        //验证是否登录
        if(!$this->isLoginedIn())
            return ['status'=>0, 'msg'=>'请登录'];

        //验证原始密码和新密码
        if(!rq('old_password') || !rq('new_password'))
           return err('原始密码和新密码不能为空');
            // return ['status'=>0, 'msg'=>'原始密码和新密码不能为空'];
        
        //获取当前用户的信息
        $user = $this->find(session('user_id'));
       
        //验证原始密码是否正确
        if(!Hash::check(rq('old_password'), $user->password))
            return err('原始密码不正确');

        //原始密码通过验证，保存新密码
        $user->password = bcrypt(rq('new_password'));
        
        if($user->save())
            return suc(['msg'=>'密码修改成功']);
            // return ['status'=>1, 'msg'=>'密码修改成功'];
        return err('密码修改失败');

    }

    //获取用户信息
    public function read()
    {
        if(!rq('id'))
            return err('id不能为空');
        //指定获取的字段
        $get = ['id', 'username', 'intro'];
        $user = $this->find(rq('id'), $get);
        $data = $user->toArray();
        
        $answer_count = answer_ins()->where('user_id',rq('id'))->count();
        $question_count = question_ins()->where('user_id',rq('id'))->count();

        $data['answer_count'] = $answer_count;
        $data['question_count'] = $question_count;
        return suc(['data'=>$data]);

    }

    public function hasUsernameAndPassword()
    {
        $username = rq('username');
        $password = rq('password');
        /*1.检查用户名和密码是否为空*/
        if ($username && $password) {
            return [$username, $password];
        } else {
            return false;
        }
    }

    //多对多的关系
    public function answers()
    {
        return $this
            ->belongsToMany('App\Answers')
            ->withPivot('vote')
            ->withTimestamps(); 
    }

    public function questions()
    {
        return $this
            ->belongsToMany('App\Questions')
            ->withPivot('vote')
            ->withTimestamps(); 
    }
}
