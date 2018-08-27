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
// 分页
function paginate($page=1, $limit=15)
{
    $limit = $limit ?: 15;
    $skip = ($page ? $page-1 : 0) * $limit;
    return [$limit, $skip];
}

//
function err($msg=null)
{
    return ['status'=>0, 'msg'=>$msg];
}

function suc($data_to_merge=null)
{
    // return ['status'=>1, 'data'=>$data];
    $data = ['status'=>1];
    if($data_to_merge)
        $data = array_merge($data, $data_to_merge);
    return $data;    
}


//请求参数
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

//comments 实例
function cmoment_ins(){
    return new App\Comments;
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

//修改密码
Route::any('api/user/change_password', function () {
    return user_ins()->changePassword();
});

//找回密码
Route::any('api/user/reset_password', function () {
    return user_ins()->resetPassword();
});
// 获取用户信息
Route::any('api/user/read', function () {
    return user_ins()->read();
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

//
Route::any('api/answer/vote', function () {
    return answer_ins()->vote();
});

//评论
Route::any('api/comment/add', function () {
    return cmoment_ins()->add();
});
Route::any('api/comment/read', function () {
    return cmoment_ins()->read();
});
Route::any('api/comment/remove', function () {
    return cmoment_ins()->remove();
});


//
Route::any('api/timeline', 'CommonController@timeline');

