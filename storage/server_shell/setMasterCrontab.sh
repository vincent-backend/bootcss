#!/bin/sh

# 这些任务都是并发执行的
# /bin/sh /www/src/bootcss/storage/server_shell/setMasterCrontab.sh

# 新建文件
echo '' > /tmp/master_init_crontab
# 写文件
cat > /tmp/master_init_crontab <<EOF
# 每天凌晨收集前一天的union列表（给1小时logrotate执行、各节点收集任务在0-10分钟内随机开始，防止并发读写redis）
0 0 * * * chmod 0644 /etc/logrotate.d/nginx; chown root:root /etc/logrotate.d/nginx; /usr/sbin/logrotate /etc/logrotate.conf >> /data/log/crontab/logrotate.log 2>&1 &
0 1 * * * php -r "sleep(rand(0, 600));"; php /www/src/bootcss/think union:CollectRefererOrigin >> /data/log/crontab/php-think-union-CollectRefererOrigin.log 2>&1 &
#emptyline
EOF

# 如果机器上有自定义crontab
diy_crontab="/data/runtime/flag/additionalCrontab.txt"
if [ -f "$diy_crontab" ] ;then
    echo "引入自定义crontab"
    cat $diy_crontab >> /tmp/master_init_crontab
fi

# 导入crontab
crontab /tmp/master_init_crontab
/usr/sbin/service crond restart
