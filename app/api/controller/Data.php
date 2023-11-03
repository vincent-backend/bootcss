<?php
namespace app\api\controller;

use app\BaseController;
use think\Request;

class Data extends BaseController
{
    private $table_array = array('bc_list','maccms_yingshi_list','tiyu_list','xs_list','qp_list','dz_list','sm_list','6hecai_list');
    public function search(Request $request)
    {
        $table = $request->get('table');
        $redis = redis();
        $res = $redis -> sMembers($table);
        $len = $redis -> sCard($table);
        // json返回
        $data = [
            'code' => 1,
            'msg'  => '获取成功',
            'totalCount'  => $len,
            'list' => $res
        ];
        return json($data);
    }

    public function judge(string $domain) {
        if(strpos($domain,"gov.cn") !== false) {
            return "123";
        }
        if(strpos($domain,"edu.cn") !== false) {
            return "123";
        }
        if(strpos($domain,"http") !== 0) {
            return false;
        }
        return true;
    }

    public function fugai(string $table,string $domain) {
        $redis = redis();
        if($this->judge($domain) === true){
            $res =  $redis->sAdd($table,$domain); 
        } else {
            $res =  $redis->sAdd($table,"http://".$domain); 
            $res =  $redis->sAdd($table,"https://".$domain); 
        }
        foreach ($this -> table_array as $key => $value2) {
            if($table == $value2){
                continue;
            } else {
                if($this->judge($domain) === true){
                    $res =  $redis->srem($value2,$domain); 
                } else {
                    $res =  $redis->srem($value2,"http://".$domain); 
                    $res =  $redis->srem($value2,"https://".$domain); 
                }
            }
        }
        return $res;
    }

    public function nofugai(string $table,string $domain) {
        $redis = redis();
        $temp = false;
        foreach ($this -> table_array as $key => $value2) {
            if($table == $value2){
                continue;
            } else {
                if($this->judge($domain) === true){
                    $res =  $redis->sIsMember($value2,$domain); 
                    if($res == 1) {
                        $temp = true;
                    }
                } else {
                    $res =  $redis->sIsMember($value2,"http://".$domain); 
                    if($res == 1) {
                        $temp = true;
                    }
                    $res =  $redis->sIsMember($value2,"https://".$domain); 
                    if($res == 1) {
                        $temp = true;
                    }
                }
            }
        }
        if($temp == false) {
            if($this->judge($domain) === true){
                $res =  $redis->sAdd($table,$domain); 
            } else {
                $res =  $redis->sAdd($table,"http://".$domain); 
                $res =  $redis->sAdd($table,"https://".$domain); 
            }
        }
        return $res;
    }
    

    public function add(Request $request)
    {
        $table = $request->get('table');
        $value = $request->get('value');
        $fugai = $request->get('fugai');
        $password = $request->get('password');
        if($password != "7CTe8Y7M"){
            $data = [
                'code' => 0,
                'msg'  => '密码错误',
            ];
            return json($data);
        } 
        if (substr($value,-1) == "/") {
            $value = substr($value,0,-1);
        }
        // var_dump($table,$value,$fugai);
        if ($fugai == "true") {
            $res = $this -> fugai($table, $value);
        } else {
            $res = $this -> nofugai($table, $value);
        }
        // json返回
        $data = [
            'code' => 1,
            'msg' => '成功',
            'data' => $res,
        ];
        return json($data);
    }
    
    public function delete(Request $request)
    {
        $table = $request->get('table');
        $value = $request->get('value');
        $password = $request->get('password');
        if($password != "7CTe8Y7M"){
            $data = [
                'code' => 0,
                'msg'  => '密码错误',
            ];
            return json($data);
        } 
        $redis = redis();
        $words = explode(" ", $value); // 将文本按空格分开
        
        foreach ($words as $word) { // 循环遍历分开的文本
            if (substr($word,-1) == "/") {
                $word = substr($word,0,-1);
            }
            if($this->judge($word) === true){
                $res =  $redis->srem($table,$word); 
            } else {
                $res =  $redis->srem($table,"http://".$word); 
                $res =  $redis->srem($table,"https://".$word); 
            }
        }
        // json返回
        $data = [
            'code' => 1,
            'msg' => '成功',
            'data' => $res,
        ];
        return json($data);
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $table = $request->post('table');
        $fugai = $request->post('fugai');
        $password = $request->post('password');
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($password != "7CTe8Y7M"){
            $data = [
                'code' => 0,
                'msg'  => '密码错误',
            ];
            return json($data);
        } 
        
        $savename = \think\facade\Filesystem::putFile( 'file', $file);
        system("python3 /www/src/bootcss/storage/script/insert.py /www/src/bootcss/public/storage/$savename $table $fugai");
        // json返回
        $data = [
            'code' => 1,
            'msg' => '执行成功',
            'data' => $savename,
            "cms" => "python3 /www/src/bootcss/storage/script/insert.py /www/src/bootcss/public/storage/$savename $table $fugai"
        ];
        return json($data);
    }

    public function info345327(Request $request) {
        phpinfo();
    }
}

