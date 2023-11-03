<?php

namespace common;

use GuzzleHttp\Client;

class NetworkRequest {


    /**
     * 初始化通用网络头
     */
    public static function initGuzzleClient($headers = [], $timeout = 3) {
        $headers = $headers + [
            'User-Agent'                => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36',
            'Accept'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Encoding'           => 'gzip, deflate',
            'Accept-Language'           => 'zh-CN,zh;q=0.9,en;q=0.8,en-US;q=0.7',
            'Connection'                => 'keep-alive',
            'DNT'                       => '1',
            'Upgrade-Insecure-Requests' => '1',
        ];
        return new Client([
            // 'debug'   => true,
            'timeout' => $timeout,
            'headers' => $headers
        ]);
    }

    /**
     * GET
     */
    public static function get($url, $headers = [], $timeout = 5) {
        $client = self::initGuzzleClient($headers, $timeout);
        $response = $client->get($url);
        return (string)$response->getBody();
    }
}