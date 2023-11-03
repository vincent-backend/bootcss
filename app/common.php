<?php
// 应用公共文件
use Naux\IpLocation\IpLocation;

function get_country() {
    $res = '';
    // AWS-CDN
    if (!empty($_SERVER['HTTP_CLOUDFRONT_VIEWER_COUNTRY'])) {
        $res = $_SERVER['HTTP_CLOUDFRONT_VIEWER_COUNTRY'];
    }
    // CF-CDN
    if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
        $res = $_SERVER['HTTP_CF_IPCOUNTRY'];
    }
    // NGINX-GEOIP2
    if (!empty($_SERVER['HTTP_MACCMS_GEOIP2_COUNTRY_CODE'])) {
        $res = $_SERVER['HTTP_MACCMS_GEOIP2_COUNTRY_CODE'];
    }
    return $res;
}

// 请求来自非中国大陆
function request_is_outland() {
    // 阿里云全站加速DCDN头部附带路径
    if (!empty($_SERVER['HTTP_VIA']) && stripos($_SERVER['HTTP_VIA'], '.l1') !== false) {
        return substr($_SERVER['HTTP_VIA'], 0, 2) != 'cn';
    }
    // AWS-CDN
    if (!empty($_SERVER['HTTP_CLOUDFRONT_VIEWER_COUNTRY'])) {
        return $_SERVER['HTTP_CLOUDFRONT_VIEWER_COUNTRY'] != 'CN';
    }
    // CloudFlare
    if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
        return $_SERVER['HTTP_CF_IPCOUNTRY'] != 'CN';
    }
    // NGINX-GEOIP2
    if (!empty($_SERVER['HTTP_MACCMS_GEOIP2_COUNTRY_CODE'])) {
        return $_SERVER['HTTP_MACCMS_GEOIP2_COUNTRY_CODE'] != 'CN';
    }
    // 其他通过IP库查
    $district = get_district_from_ip();
    $countries = '美国,日本,菲律宾,柬埔寨,越南,老挝,香港,台湾,新加坡,加拿大,英国,法国,德国';
    foreach (explode(',', $countries) as $keyword) {
        if (stripos($district, trim($keyword)) !== false) {
            return true;
        }
    }
    return false;
}

function get_district_from_ip() {
    $ipLocation = new IpLocation(app()->getRootPath() . 'storage/lib/qqwry.dat');
    $location = $ipLocation->getlocation(get_client_ip());
    return ($location['country'] ?? '') . ' ' . ($location['area'] ?? '');
}

/**
 * 获取客户端IP
 * @ $allow_loose 是否允许宽松获取，传true后，允许获取xff等可能被伪造的头，默认否
 */
function get_client_ip($allow_loose = false) {
    static $final;
    $flag = 0;
    if (!is_null($final)) {
        return $final;
    }
    $ips = array();
    // AZURE-CDN
    if (!empty($_SERVER['HTTP_X_AZURE_CLIENTIP'])) {
        $ips[] = $_SERVER['HTTP_X_AZURE_CLIENTIP'];
    }
    // AWS-CDN
    if (!empty($_SERVER['HTTP_TRUE_CLIENT_IP'])) {
        $ips[] = $_SERVER['HTTP_TRUE_CLIENT_IP'];
        $flag = 1;
    }
    // CF-CDN
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ips[] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        $flag = 2;
    }
    if (!empty($_SERVER['HTTP_ALI_CDN_REAL_IP'])) {
        $ips[] = $_SERVER['HTTP_ALI_CDN_REAL_IP'];
    }
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ips[] = $_SERVER['HTTP_CLIENT_IP'];
    }
    if (!empty($_SERVER['HTTP_PROXY_USER'])) {
        $ips[] = $_SERVER['HTTP_PROXY_USER'];
    }
    $real_ip = getenv('HTTP_X_REAL_IP');
    if (!empty($real_ip)) {
        $ips[] = $real_ip;
    }
    if (!empty($_SERVER['REMOTE_ADDR'])) {
        $ips[] = $_SERVER['REMOTE_ADDR'];
    }
    // 宽松获取时，允许xff头
    if ($allow_loose === true && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        foreach (explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']) as $ip) {
            $ips[] = trim($ip);
        }
    }
    // 选第一个最合法的，或最后一个正常的IP
    foreach ($ips as $ip) {
        $long = ip2long($ip);
        $long && $final = $ip;
        // 排除不正确的或私有IP
        if (!(($long == 0) ||                                       // 不正确的IP或0.0.0.0
              ($long == -1) ||                                      // PHP4下当IP不正确时会返回-1
              ($long == 4294967295) ||                              // 255.255.255.255
              ($long == 2130706433) ||                                // 127.0.0.1
              (($long >= 167772160) && ($long <= 184549375)) ||   // 10.0.0.0 - 10.255.255.255
              (($long >= 2886729728) && ($long <= 2887778303)) ||   // 172.16.0.0 - 172.31.255.255
              (($long >= 3232235520) && ($long <= 3232301055)))) {  // 192.168.0.0 - 192.168.255.255
            $final = long2ip($long);
            break;
        }
    }
    // if($flag == 1){
    //     llog("[aws][get_client_ip] ip => ".implode(",",$ips));
    // }
    // if($flag == 2){
    //     llog("[cf][get_client_ip] ip => ".implode(",",$ips));
    // }
    empty($final) && $final = '0.0.0.0';
    return $final;
}

/**
 * 获取客户端IP-纯通过XFF
 * 支持获取IPV6
 */
function get_client_ip_xff() {
    static $final;
    $flag = 0;
    if (!is_null($final)) {
        return $final;
    }
    $ips = array();
    foreach (explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '') as $ip) {
        $ips[] = trim($ip);
    }
    // 选第一个最合法的，或最后一个正常的IP
    foreach ($ips as $ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            $final = $ip;
            break;
        }
    }
    empty($final) && $final = '0.0.0.0';
    return $final;
}

function json_param_invalid($message, $code = 1001, $data = []) {
    return json(['code' => $code, 'msg' => $message, 'info' => $data ?: new \stdClass()]);
}

/**
 * 是否为移动端
 */
function user_agent_is_mobile($user_agent = null) {
    $mobile = false;
    $ua = strtolower($user_agent ?: $_SERVER['HTTP_USER_AGENT']);
    $regex = "/.*(mobile|nokia|iphone|ipod|andriod|bada|motorola|^mot\-|softbank|foma|docomo|kddi|ip\.browser|up\.link|";
    $regex .= "htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|ppc|";
    $regex .= "blackberry|alcate|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
    $regex .= "symbian|smartphone|midp|wap|phone|windows\sphone|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
    $regex .= "MicroMessenger|MQQBrowser|oppo|vivo|; Scale\/|MuMu|";
    $regex .= "jig browser|hiptop|uc|^benq|haier|^lct|opera\s*mobi|opera\s*mini|2.0 MMP|240x320|400X240|Cellphone|WinWAP).*/i";
    if (preg_match(strtolower($regex), strtolower($ua))) {
        $mobile = true;
    }
    return $mobile;
}

/**
 * 从html内容中获取缩略内容（去除换行等字符）
 */
function get_html_content_abbr($string, $length = 255) {
    $string = substr($string, 0, 10 * $length);
    $string = str_replace(["\r", "\n"], " ", $string);
    $string = trim(strip_tags($string));
    while (strpos($string, "  ") !== false) {
        $string = str_replace("  ", " ", $string);
    }
    $string = sub_str($string, $length);
    return $string;
}

// 正确切汉字字符串函数
// $string为输入汉字，$length为数量 仅适用UTF-8
function sub_str($string, $length = 10, $append = '…', $triple = true) {
    if (function_exists('mb_substr')) {
        $sub = mb_substr($string, 0, $length, 'utf-8');
        $needappend = $sub != $string ? $append : '';
        return $sub . $needappend;
    }
    $final = '';
    if ($triple) {
        $length *= 3;
    }
    if (strlen($string) <= $length) {
        return $string;
    } else {
        $i = 0;
        while ($i < $length) {
            $string_tmp = substr($string, $i, 1);
            if (ord($string_tmp) >= 224) {
                $string_tmp = substr($string, $i, 3);
                $i = $i + 3;
            } elseif (ord($string_tmp) >= 192) {
                $string_tmp = substr($string, $i, 2);
                $i = $i + 2;
            } else {
                $i = $i + 1;
            }
            $final .= $string_tmp;
        }
        if ($append != '') {
            $final .= $append;
        }
        return $final;
    }
}

function format_frontend_image($url) {
    if (substr($url, 0, 1) == '/') {
        $url = env('static_image_url_prefix', '') . $url;
    }
    return $url;
}

function dt($t = null) {
    // 转换成字符串形式的
    $t .= '';
    // 添加返回日期的
    if (strlen($t) == 19) {
        $t = strtotime($t);
        $s = date('Y/m/d H:i', $t);
    } elseif ($t != '') {
        $s = date('Y-m-d H:i:s', (int)$t);
    } else {
        $s = date('Y-m-d H:i:s');
    }

    return $s;
}
// 返回毫秒
function mt($precision = 2) {
    $mt = number_format(microtime(true), $precision, '.', '');
    return $mt;
}

// json encode
function enjson($data, $cmd = 'normal') {
    $final = null;
    if ($cmd == 'pretty') {
        $final = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    } elseif ($cmd == 'normal') {
        $final = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } elseif ($cmd == 'default') {
        $final = json_encode($data);
    }
    return $final;
}

// json decode
function dejson($str) {
    return @json_decode($str, true);
}

function redis($select = 0){
    static $redisObj = null;
    if(!is_null($redisObj)){
        return $redisObj;
    }
    $option = \think\facade\Config::get('cache.stores.redis');

    $redis = new \Redis();
    if (isset($option['persistent']) && $option['persistent']) {
        $re = $redis->pconnect($option['host'], $option['port'], $option['timeout'], 'persistent_id_' . $select);
    }else{
        $re = $redis->connect($option['host'], $option['port'], $option['timeout']);
    }

    if (!$re) {
        throw new \Exception("Connect Redis Failed", 1);
    }

    if ($option['password']) {
        $redis->auth($option['password']);
    }
    $redis->select($select);

    $redisObj = $redis;
    return $redisObj;
}

/*
 * 
 * curl xml post
 * $url 请求地址
 * $data 提交数据
 * 
 */
function cache_curl_post($url, $data) {
    // 模拟提交数据函数
    $curl = curl_init(); // 启动一个CURL会话
    $header = array(
        // "Content-Type: text/xml; charset=utf-8"
    );
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $return = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        var_dump(curl_error($curl), $return);
        echo 'Errno: ' . curl_errno($curl); //捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $return; // 返回数据
}

function curl_get($url){
 
    $header = array(
        'Accept: application/json',
     );
     $curl = curl_init();
     //设置抓取的url
     curl_setopt($curl, CURLOPT_URL, $url);
     //设置头文件的信息作为数据流输出
     curl_setopt($curl, CURLOPT_HEADER, 0);
     // 超时设置,以秒为单位
     curl_setopt($curl, CURLOPT_TIMEOUT, 1);
  
     // 超时设置，以毫秒为单位
     // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);
  
     // 设置请求头
     curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
     //设置获取的信息以文件流的形式返回，而不是直接输出。
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
     //执行命令
     $data = curl_exec($curl);
  
     // 显示错误信息
     if (curl_error($curl)) {
         print "Error: " . curl_error($curl);
     } 
    // 打印返回的内容
    
    curl_close($curl);
    return $data;
 }

function llog($msg)
{
    $file = "/www/" . date('Ymd') . '_.txt';
    $f = fopen($file, 'a');
    fwrite($f, date('[Y-m-d H:i:s]') . "\t" . get_client_ip() . "\t" . $msg . PHP_EOL);
    // fwrite($f, date('[Y-m-d H:i:s] ') . ' ' . $msg . PHP_EOL);
}

function lllog($filename, $msg)
{
    $file = "/www/src/" . date('Ymd') . '_' . $filename . '_.txt';
    $f = fopen($file, 'a');
    fwrite($f, date('[Y-m-d H:i:s]') . "\t" . get_client_ip() . "\t" . $msg . PHP_EOL);
    // fwrite($f, date('[Y-m-d H:i:s] ') . ' ' . $msg . PHP_EOL);
}
