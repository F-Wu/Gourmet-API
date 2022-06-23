<?php

namespace App\Http\Controllers;

use http\Env\Response;

//注意
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
//###########大前提表“gourmet_classify” disable=0，deleted=0，表“gourmet_menu” deleted=0

//    搜索
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $num = $request->input('num');
//        num限制输出条数为10
        if ($num == null || $num == NAN) {
            $num = 10;
        }
        $foodList = DB::table('gourmet_menu')->where('title', 'like', $keyword . '%')->where('deleted', 0)->limit($num)->get();
        $total = DB::table('gourmet_menu')->where('title', 'like', $keyword . '%')->where('deleted', 0)->get();
        if ($foodList != null && $total->count() != 0) {
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'total' => $total->count(),
                'result' => $foodList
            ]);
        }
        if ($foodList == null || $foodList == '' || $total->count() == 0) {
            return response()->json([
                'msg' => 'no',
                'status' => 1,
                'result' => '没有信息'
            ], 205);
        }
        if ($keyword == null || $keyword == '') {
            return response()->json([
                'msg' => 'no',
                'status' => 2,
                'result' => '关键词为空'
            ], 201);
        }
    }

//    全部分类展示
    public function sortList()
    {
        $sortdata = DB::table('gourmet_classify')->where('disable', '0')->get();
//        $sort = DB::select('select * from gourmet_classify where pid = 0 where disable==0');
        $sort=DB::table('gourmet_classify')->where('disable',0)->where('pid',0)->get();
        $sort1 = DB::select('SELECT *FROM gourmet_classify where pid!=0 and disable=0');
        if ($sortdata != null) {
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'total' => $sortdata->count(),
                'father' => $sort,
                'son' => $sort1
            ]);
        }
        if ($sortdata == null || $sortdata == '') {
            return response()->json([
                'msg' => 'no',
                'status' => '没有信息',
            ], 205);
        }
    }

//    分类搜索 传子类ID去得出该子类名称  后去gourmet_menu表搜索子类名称
    public function sortSearch(Request $request)
    {
        $num = $request->input('num');
//        num限制输出条数为10
        if ($num == null || $num == '') {
            $num = 10;
        }
        $id = $request->input('id');
        $sort = DB::table('gourmet_classify')->where('id', $id)->where('disable', 0)->find($id);
        $foodList = DB::table('gourmet_menu')->where('zid', $sort->name)->where('deleted', 0)->limit($num)->get();
        $foods = DB::table('gourmet_menu')->where('zid', $sort->name)->where('deleted', 0)->get();
        if ($sort != null) {
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'total' => $foods->count(),
                'result' => $foodList
            ]);
        }
        if ($sort == null || $sort == '') {
            return response()->json([
                'msg' => 'no',
                'status' => '没有信息',
            ], 205);
        }
    }

//    详细菜谱
    public function foodDetail(Request $request)
    {
        $id = $request->input('id');
        $food = DB::table('gourmet_menu')->where('id', $id)->where('deleted', 0)->get();
        if ($food != null) {
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'total' => $food->count(),
                'result' => $food
            ]);
        }
        if ($food == null || $food == '') {
            return response()->json([
                'msg' => 'no',
                'status' => '没有信息',
            ], 205);
        }
    }

//    LIKE 喜爱排行
    public function Like(Request $request)
    {
        $num = $request->input('num');
//        num限制输出条数为10
        if ($num == null || $num == '') {
            $num = 10;
        }
//        $result=DB::table('gourmet_menu')->where(['favnum'=>['>',0]])->orderBy(['favnum'=>'desc'])->limit($num)->get();
        $result = DB::table('gourmet_menu')->where('deleted', 0)->orderBy('favnum', 'desc')->limit($num)->get();
        if ($result != null) {
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
//                'total' => $food->count(),
                'result' => $result
            ]);
        }
        if ($result == null || $result == '') {
            return response()->json([
                'msg' => 'no',
                'status' => '没有信息',
            ], 205);
        }
    }

//    轮播图 deleted disable=0
    public function getBanner()
    {
        $result = DB::table('banner')->where('disable', 0)->where('deleted', 0)->get();
        if ($result != null) {
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'total' => $result->count(),
                'result' => $result
            ]);
        }
        if ($result == null || $result == '') {
            return response()->json([
                'msg' => 'no',
                'status' => '没有信息',
            ], 205);
        }
    }

//    热门推荐
    public function recommend()
    {
        $result = DB::table('recommend')->where('disable', '0')->where('deleted', '0')->get();
        if ($result != null) {
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'total' => $result->count(),
                'result' => $result
            ]);
        }
        if ($result == null || $result == '') {
            return response()->json([
                'msg' => 'no',
                'status' => '没有信息',
            ], 205);
        }
    }
//运行语句 php artisan serve
}
