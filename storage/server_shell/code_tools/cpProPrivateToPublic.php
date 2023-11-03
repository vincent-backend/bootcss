<?php
/**
 * 复制pro_private的代码到maccms_pro
 * php storage/server_shell/code_tools/cpProPrivateToPublic.php 起始hash [结束hash]
 */

// 配置
$maccmspro_dir = "/data/mainuser/code/web/outsource/maccmspro";
$output_shell_dir = "/data/mainuser/project/aag/tmpshell";
// 参数
$hash_start = '';
if (empty($argv[1])) {
    exit("请传入需要对比的标记号\n");
}
$hash_start = $argv[1];
$hash_end = !empty($argv[2]) ? $argv[2] : '';
// 执行命令
$cmd = "cd {$maccmspro_dir}/pro_private; git diff {$hash_start} {$hash_end} --stat=300";
exec($cmd, $git_stats);
echo "cmd: \n{$cmd}\n";
echo "差异结果: \n" . join("\n", $git_stats);
if (empty($git_stats)) {
    exit("无差异，退出\n");
}
// 生成结果
echo "\n\n";
$result_list = [];
$result_list[] = "path1={$maccmspro_dir}/pro_private\n";
$result_list[] = "path2={$maccmspro_dir}/maccms_pro\n\n";
array_pop($git_stats);
foreach ($git_stats as $index => $line) {
    if (stripos($line, '|') === false) {
        echo "[ERR] skip line {$index}: {$line}, no '|'\n";
        exit;
    }
    list($file, ) = explode('|', $line);
    $file = ltrim($file);
    $result_list[] = "yes | cp -r \${path1}/{$file} \${path2}/{$file}\n";
}
$shell_file = "{$output_shell_dir}/cpProPrivateToPublic_" . date('YmdHis') . ".sh";
file_put_contents($shell_file, join("", $result_list));
echo "\n\n";
echo "# 请执行git diff，检查差异再提交\n";
echo "cat {$shell_file}\n";
echo "sh {$shell_file}\n\n";