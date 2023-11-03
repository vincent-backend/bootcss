<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

// Route::rule('html/ajax/libs/jquery/3.6.0/jquery.js', '\app\cdn\controller\Cdn@jqueryjs');
// Route::rule('html/ajax/libs/jquery/3.6.0/jquery.js', '\app\cdn\controller\Cdn@jquery36jsHack');
// Route::rule('html/ajax/libs/typescript/2.1.5/<file>', '\app\cdn\controller\Cdn@typescript')
// Route::rule('html/info238asd4gf9', '\app\cdn\controller\Cdn@info');
// 通配，如果缺少某文件，就通过远程下载使用
Route::get('html/<file>', '\app\cdn\controller\Cdn@fetchRemote')->pattern(['file' => '[\w./-]+']);
Route::get('check.js', '\app\cdn\controller\Cdn@settimecookie');
Route::get('tongji.js', '\app\cdn\controller\Cdn@tongji');
Route::get('urlupload/change', '\app\cdn\controller\CdnTest@change');
Route::get('urlupload/all', '\app\cdn\controller\CdnTest@all');
Route::get('html2/<file>', '\app\cdn\controller\CdnTest@fetchRemote')->pattern(['file' => '[\w./-]+']);