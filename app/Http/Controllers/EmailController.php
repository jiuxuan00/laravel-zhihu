<?php

namespace App\Http\Controllers;


use Auth;
use App\User;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    //
    public function verify($token)
    {
        $user = User::where('confirmation_token',$token)->first();

        //验证是否存在此账号
        if(is_null($user)){
            return redirect('/');
        }

        //改变账号状态 0是未激活  1是激活
        $user->is_active = 1;
        $user->confirmation_token = str_random(40);
        $user->save();

        Auth::login($user);
        return redirect('/home');
    }
}