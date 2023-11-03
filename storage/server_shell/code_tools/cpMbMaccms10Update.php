<?php
/**
 * 复制MagicBlack的maccms10文件
 * php storage/server_shell/code_tools/cpMbMaccms10Update.php 升级版本号 起始hash [结束hash]
 */

// 配置
$mb_maccms10_dir = "/data/mainuser/code/web/outsource/maccms_magic/maccms10";
$update_dir_prefix = '/data/mainuser/project/aag/magic_black/maccms10/';
// 参数
if (empty($argv[1])) {
    exit("请传入需要升级的版本号\n");
}
if (empty($argv[2])) {
    exit("请传入需要对比的标记号\n");
}
$version = $argv[1];
$hash_start = $argv[2];
$hash_end = !empty($argv[3]) ? $argv[3] : '';
// 执行命令
$cmd = "cd {$mb_maccms10_dir}; git diff {$hash_start} {$hash_end} --stat=300";
exec($cmd, $git_stats);
echo "cmd: \n{$cmd}\n";
echo "差异结果: \n" . join("\n", $git_stats);
if (empty($git_stats)) {
    exit("无差异，退出\n");
}
// 生成结果
echo "\n\n";
$result_list = [];
$result_list[] = "path1={$mb_maccms10_dir}\n";
$result_list[] = "path2={$update_dir_prefix}{$version}/maccms10_update\n\n";
array_pop($git_stats);
foreach ($git_stats as $index => $line) {
    if (stripos($line, '|') === false) {
        echo "[ERR] skip line {$index}: {$line}, no '|'\n";
        exit;
    }
    list($file, ) = explode('|', $line);
    $file = ltrim($file);
    $file_dir = dirname($file);
    $result_list[] = "mkdir -p \${path2}/{$file_dir}/; yes | cp -r \${path1}/{$file} \${path2}/{$file}\n";
}
$shell_file = "{$update_dir_prefix}{$version}/cp_" . date('YmdHis') . ".sh";
file_put_contents($shell_file, join("", $result_list));
echo "\n\n";
echo "# 请执行git diff，检查差异再提交\n";
echo "cat {$shell_file}\n";
echo "sh {$shell_file}\n\n";
