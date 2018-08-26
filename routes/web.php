<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



function rq($key=null, $default=null){
    if(!$key){
        return Request::all();
    }else {
        return Request::get($key, $default);
    }
}


//User实例
function user_ins(){
    return new App\User;
}

//Questions实例
function question_ins(){
    return new App\Questions;
}

//Answers 实例
function answer_ins(){
    return new App\Answers;
}


Route::get('/', function () {
    return view('welcome');
});


//注册 /api/question/signup?username=qiuxi&password=123456
Route::get('api/signup',function (){
    return user_ins()->signUp();
});

//登录 /api/question/login?username=qiuxi&password=123456
Route::get('api/login',function (){
   return user_ins()->login();
});

//退出 /api/logout
Route::get('api/logout',function (){
    return user_ins()->logout();
});

//创建问题 /api/question/add?title=地球是不是圆的呢12222&id=1&desc=描述描述描述描述描述
Route::any('api/question/add',function (){
    return question_ins()->add();
});

//修改问题 /api/question/change?title=地球是不是圆的呢12222&id=1&desc=描述描述描述描述描述
Route::any('api/question/change',function (){
    return question_ins()->change();
});

//查看问题 /api/question/read?id=1
Route::any('api/question/read',function (){
    return question_ins()->read();
});

//删除问题
Route::any('api/question/remove',function (){
    return question_ins()->remove();
});

//回答问题
Route::any('api/answer/add',function(){
  return answer_ins()->add();
});

//查看回答问题
Route::any('api/answer/read', function () {
    return answer_ins()->read();
});
