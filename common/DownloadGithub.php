<?php

namespace common;

class DownloadGithub
{
    /**
     * 通用下载
     */
    public static function downloadRootZip($file_name, $saved_path) {
        if (!is_file($saved_path) || filesize($saved_path) < 10) {
            // 存到本地
            $url = 'https://raw.githubusercontent.com/maccmspro/download/master/' . $file_name;
            $client = NetworkRequest::initGuzzleClient([], 10);
            $response = $client->get($url);
            $body = $response->getBody();
            if (empty($body)) {
                return '';
            }
            file_put_contents($saved_path, $body);
        }
        return download($saved_path, $file_name);
    }
}
