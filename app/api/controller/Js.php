<?php
namespace app\api\controller;

use app\BaseController;
use think\Request;

class Js extends BaseController
{
    public function change(Request $request)
    {
        $checkbox = $request->post('checkbox');
        $url = $request->post('url');
        $password = $request->post('password');
        if($password != "7CTe8Y7M"){
            $data = [
                'code' => 0,
                'msg'  => '密码错误',
            ];
            return json($data);
        } 
        $str = implode(",", $checkbox);
        system("python3 /www/src/bootcss/storage/script/js_update/js_update.py $str $url > text.txt 2>&1");
        // json返回
        $data = [
            'code' => 1,
            'msg'  => '获取成功',
            'list' => "python3 /www/src/bootcss/storage/script/js_update/js_update.py $str $url"
        ];
        return json($data);
    }
    public function change2(Request $request)
    {
        set_time_limit(0);
        $checkbox = $request->post('checkbox');
        $url = $request->post('url');
        $protocol = $request->post('protocol');
        $password = $request->post('password');

        if($password != "7CTe8Y7M"){
            $data = [
                'code' => 0,
                'msg'  => '密码错误',
            ];
            return json($data);
        } 
        $str = implode(",", $checkbox);

        if ( $protocol == 'http'){
            $cmd = "python3 /www/src/bootcss/storage/script/js_update/js_update3.py $str $url 2>&1";
            $output = shell_exec($cmd);
        }
        else{
            $cmd = "python3 /www/src/bootcss/storage/script/js_update/js_update.py $str $url 2>&1";
            $output = shell_exec($cmd);
        }

        //json返回
        $data = [
            'code' => 1,
            'msg'  => 'python已执行',
            'list' => $cmd
        ];
        return json($data);
    }
}

