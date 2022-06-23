<?php

namespace App\Http\Controllers;

use http\Env\Response;

//管理信息系统
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
//登录
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $userdata = DB::table("admin")->where('username', $username)->where('password', $password)->first();
        if ($userdata != null) {
            return response()->json([
                'msg' => 'ok',
                'code'=>20000,
                'status' => 0,
                'token'=>"admin-token",
                'result' => $userdata
            ], 200);
        }
        if ($userdata == null || $userdata == '') {
            return response()->json([
                'msg' => 'no',
                'status' => 'fail',
                'result' => '登录失败，没有该账号消息'
            ], 205);
        }
    }
//验证登录
public function userInfo(Request $request)
{
    $token = $request->input('token');
    if($token=='admin-token'){
        return response()->json([
            'msg' => 'ok',
            'code'=>20000,
            'status' => 0,
            'token'=>"admin-token",
            'name' =>"Super Admin",
            'roles'=>['admin'],
            'avatar'=> "https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif"
        ], 200);
    }
}

//退出登录
    public function out()
    {
        return response()->json([
            'msg' => 'no',
            'code'=>50008,
            'status' => 'fail',
            'result' => "退出登录"
        ], 205);
    }

//  #############  轮播图管理 增删查改
//添加
    public function addBanner(Request $request)
    {
        $title = $request->input('title');
        $image = $request->input('image');
        $disable = $request->input('disable');
        $banner = DB::table('banner')->insert([
            'title' => $title,
            'image' => $image,
            'disable' => $disable
        ]);
        if ($banner == true) {
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'result' => '添加成功'
            ]);
        }
        if ($banner == false) {
            return response()->json([
                'msg' => 'no',
                'status' => '添加失败',
            ], 205);
        }
    }

//修改
    public function modifyBanner(Request $request)
    {
        $id = $request->input('id');
        if (DB::table('banner')->where('id', $id)->exists()) {
            $dannerData = DB::table('banner')->find($id);
            $dannerData->title = is_null($request->title) ? $dannerData->title : $request->title;
            $dannerData->image = is_null($request->image) ? $dannerData->image : $request->image;
            $dannerData->disable = is_null($request->disable) ? $dannerData->disable : $request->disable;
            DB::table('banner')->where('id', $id)->update([
                'title' => $dannerData->title,
                'image' => $dannerData->image,
                'disable' => $dannerData->disable
            ]);
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'data' => $dannerData
            ], 200);
        }
    }

//    删除
    public function removeBanner(Request $request)
    {
        $id = $request->input('id');
        $banner = DB::table('banner')->where('id', $id)->delete();
        if ($banner != null) {
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'data' => $banner,
            ]);
        } else {
            return response()->json([
                'msg' => 'User credential is invalid'
            ]);
        }
    }
//喜爱度排名 增删查改

//热门推荐 recommend
//添加
    public function addRecommend(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $src = $request->input('src');
        $disable = $request->input('disable');
        $recommend = DB::table('recommend')->insert([
            'id' => $id,
            'name' => $name,
            'src' => $src,
            'disable' => $disable
        ]);
        if ($recommend == true) {
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'result' => '添加成功'
            ]);
        }
        if ($recommend == false) {
            return response()->json([
                'msg' => 'no',
                'status' => '添加失败',
            ], 205);
        }
    }
//删除
    public function removeRecommend(Request $request)
    {
        $id = $request->input('id');
        $recommend = DB::table('recommend')->where('id', $id)->delete();
        if ($recommend != null) {
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'data' => $recommend,
            ]);
        } else {
            return response()->json([
                'msg' => 'User credential is invalid'
            ]);
        }
    }
//    修改
    public function modifyRecommend(Request $request)
    {
        $id = $request->input('id');
        if (DB::table('recommend')->where('id', $id)->exists()) {
            $recommendData = DB::table('recommend')->find($id);
            $recommendData->name = is_null($request->name) ? $recommendData->name : $request->name;
            $recommendData->src = is_null($request->src) ? $recommendData->src : $request->src;
            $recommendData->disable = is_null($request->disable) ? $recommendData->disable : $request->disable;
            DB::table('recommend')->where('id', $id)->update([
                'name' => $recommendData->name,
                'src' => $recommendData->src,
                'disable' => $recommendData->disable
            ]);
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'data' => $recommendData
            ], 200);
        }
    }
//        分类管理

//             明细菜单
//删除
    public function removeMenu(Request $request)
    {
        $id = $request->input('id');
        $menu = DB::table('gourmet_menu')->where('id', $id)->delete();
        if ($menu != null) {
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'data' => $menu,
            ]);
        } else {
            return response()->json([
                'msg' => 'User credential is invalid'
            ]);
        }
    }
//    添加
    public function addMenu(Request $request)
    {
        $deleted = $request->input('deleted');
        $cid=$request->input('cid');
        $zid=$request->input('zid');
        $title=$request->input('title');
        $thumb=$request->input('thumb');
        $desc=$request->input('desc');//描述
        $difficulty=$request->input('difficulty');//难度等级
        $costtime=$request->input('costtime');//烹饪时长
        $tip=$request->input('tip');
        $yl=$request->input('yl');//原料
        $fl=$request->input('fl');//用量
        $steptext=$request->input('steptext');//步骤
        $steppic=$request->input('steppic');//步骤图片
        $grade=$request->input('grade');//评分
        $menu = DB::table('gourmet_menu')->insert([
            'deleted' => $deleted,
            'cid'=>$cid,
            'zid'=>$zid,
            'title'=>$title,
            'thumb'=>$thumb,
            'desc'=>$desc,
            'difficulty'=>$difficulty,
            'costtime'=>$costtime,
            'yl'=>$yl,
            'fl'=>$fl,
            'steptext'=>$steptext,
            'steppic'=>$steppic,
            'grade'=>$grade
        ]);
        if ($menu == true) {
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'result' => '添加成功'
            ]);
        }
        if ($menu == false) {
            return response()->json([
                'msg' => 'no',
                'status' => '添加失败',
            ], 205);
        }
    }
//    编辑
    public function modifyMenu(Request $request)
    {
        $id = $request->input('id');
        if (DB::table('gourmet_menu')->where('id', $id)->exists()) {
            $menuData = DB::table('gourmet_menu')->find($id);
            $menuData->deleted = is_null($request->deleted) ? $menuData->deleted : $request->deleted;
            $menuData->cid = is_null($request->cid) ? $menuData->cid : $request->cid;
            $menuData->zid = is_null($request->zid) ? $menuData->zid : $request->zid;
            $menuData->title = is_null($request->title) ? $menuData->title : $request->title;
            $menuData->thumb = is_null($request->thumb) ? $menuData->thumb : $request->thumb;
            $menuData->desc = is_null($request->desc) ? $menuData->desc : $request->desc;
            $menuData->difficulty = is_null($request->difficulty) ? $menuData->difficulty : $request->difficulty;
            $menuData->costtime = is_null($request->costtime) ? $menuData->costtime : $request->costtime;
            $menuData->yl = is_null($request->yl) ? $menuData->yl : $request->yl;
            $menuData->fl = is_null($request->fl) ? $menuData->fl : $request->fl;
            $menuData->steptext = is_null($request->steptext) ? $menuData->steptext : $request->steptext;
            $menuData->steppic = is_null($request->steppic) ? $menuData->steppic : $request->steppic;
            $menuData->grade = is_null($request->grade) ? $menuData->grade : $request->grade;
            $menuData->up = is_null($request->up) ? $menuData->up : $request->up;
            $menuData->viewnum = is_null($request->viewnum) ? $menuData->viewnum : $request->viewnum;
            $menuData->favnum = is_null($request->favnum) ? $menuData->favnum : $request->favnum;
            $menuData->outdate = is_null($request->outdate) ? $menuData->outdate : $request->outdate;
            $menuData->status = is_null($request->status) ? $menuData->status : $request->status;
            DB::table('gourmet_menu')->where('id', $id)->update([
                'deleted' => $menuData->deleted,
                'cid' => $menuData->cid,
                'zid' => $menuData->zid,
                'title' => $menuData->title,
                'thumb' => $menuData->thumb,
                'desc'=>$menuData->desc,
                'difficulty' => $menuData->difficulty,
                'costtime' => $menuData->costtime,
                'yl' => $menuData->yl,
                'fl' => $menuData->fl,
                'steptext' => $menuData->steptext,
                'steppic'=>$menuData->steppic,
                'grade'=>$menuData->grade,
                'up' => $menuData->up,
                'viewnum' => $menuData->viewnum,
                'favnum' => $menuData->favnum,
                'outdate'=>$menuData->outdate,
                'status'=>$menuData->status,
            ]);
            return response()->json([
                'msg' => 'ok',
                'status' => 0,
                'data' => $menuData
            ], 200);
        }
    }
}

