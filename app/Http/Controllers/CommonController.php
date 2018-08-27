<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    //
    public function timeline()
    {
        list($limit, $skip) = paginate(rq('page'), rq('limit'));
        $questions = question_ins()
            ->limit($limit)
            ->skip($skip)
            ->orderBy('updated_at','desc')
            ->get();
        $answers = answer_ins()
            ->limit($limit)
            ->skip($skip)
            ->orderBy('updated_at', 'desc')
            ->get();
        $data = $questions->merge($answers);
        //sortBy 正序排列 sortByDesc倒序排列
        $data = $data->sortByDesc(function($item){
            return $item->updated_at;
        })->values()->all();
        return ['status'=>1, 'data'=>$data];
    }
}
