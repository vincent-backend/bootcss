#!/bin/sh
# 记录常用命令的，无需执行
exit


# 基于Nginx日志实时观察QPS
tail -f union.maccms.la.access.log | grep "union.dplayerstatic.com" | awk '{print substr($1,14,20)}'| uniq -c

# 最近10w个请求，独立IP情况
tail -100000 union.maccms.la.access.log | grep "union.dplayerstatic.com" | awk '{print $6}' | sort | uniq -c | sort -n

# 进入union的日志目录
cd /www/src/bootcss/runtime/union/log



