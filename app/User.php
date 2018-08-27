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
        if (!$check) {//不存在
            return [
                'status' => 0,
                'msg' => '用户名和密码都不能为空'
            ];
        }

        $username = $check[0];
        $password = $check[1];

        /*2.检查用户名是否存在*/
        $user_exists = $this->where('username', $username)->exists();
        if ($user_exists) {
            return [
                'status' => 0,
                'msg' => '用户名已存在'
            ];
        }

        /*3.加密密码*/
        $hased_passwod = bcrypt($password);

        /*4.存入数据库*/
        $user = $this;
        $user->username = $username;
        $user->password = $hased_passwod;
        if ($user->save()) {
            return [
                'status' => 1,
                'id' => $user->id,
                'msg' => '保存成功'
            ];
        } else {
            return [
                'status' => 0,
                'msg' => '保存失败'
            ];
        }


    }

    //登录
    public function login()
    {
        // 判断输入是否正确
        $check = $this->hasUsernameAndPassword();
        if (!$check) {
            return [
                'status' => 0,
                'msg' => '用户名和密码都不能为空'
            ];
        }
        $username = $check[0];
        $password = $check[1];

        //检查用户是否存在
        $user = $this->where('username', $username)->first();
        if (!$user) {
            return ['status' => 0, 'msg' => '用户名不存在'];
        }

        // 检查密码是否正确
        $hased_passwod = $user->password;
        if (!Hash::check($password, $hased_passwod)) {//不正确
            return ['status' => 1, 'msg' => '密码有误'];
        } else {
            session()->put('username', $user->username);
            session()->put('user_id', $user->id);
            return [
                'status' => 1,
                'id' => $user->id,
                'msg' => '登录成功'
            ];
        }

    }

    //退出
    public function logout()
    {
        //请开工session
        session()->forget('username');
        session()->forget('user_id');
        return ['status'=>1,'msg'=>'退出成功'];
    }

    //检测用户是否登录
    public function isLoginedIn()
    {
        return session('user_id') ?:false;
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
            ->belongsToMany('App\User')
            ->withPivot('vote')
            ->withTimestamps(); 
    }
}
