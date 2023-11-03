<?php

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------

return [
    // 默认缓存驱动
    'default' => env('cache.driver', 'file'),

    // 缓存连接方式配置
    'stores'  => [
        'file' => [
            // 驱动方式
            'type'       => 'File',
            // 缓存保存目录
            'path'       => '/home/www/cache/',
            // 缓存前缀
            'prefix'     => '',
            // 缓存有效期 0表示永久缓存
            'expire'     => 0,
            // 缓存标签前缀
            'tag_prefix' => 'tag:',
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => [],
            'timeout'    => 10,
        ],
        // 更多的缓存连接
        'redis' => [
            'type'       => 'redis',
            'host'       => env('redis_host', '127.0.0.1'),
            'port'       => env('redis_port', '6379'),
            'password'   => env('redis_password', ''),
            'select'     => '0',
            'expire'     => 0,// 全局缓存有效期（0为永久有效）
            'prefix'     => env('redis_prefix', 'maccmspro_portal:'),// 缓存前缀
            'persistent' => true,
            'timeout'    => 10,
        ],
    ],
];
