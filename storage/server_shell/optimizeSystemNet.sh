#!/bin/sh
# /bin/sh /www/src/bootcss/storage/server_shell/optimizeSystemNet.sh

# 优化网络
rm -rf /etc/sysctl.d/local.conf
touch /etc/sysctl.d/local.conf
# 写入配置
cat > /etc/sysctl.d/local.conf <<EOF
fs.file-max = 51200

net.ipv4.ip_local_port_range = 15000 65535
net.core.somaxconn = 16384
net.ipv4.tcp_max_syn_backlog = 16384

net.ipv4.tcp_syn_retries = 1
net.ipv4.tcp_syncookies = 1
net.ipv4.tcp_tw_reuse = 1
net.ipv4.tcp_tw_recycle = 0
net.ipv4.tcp_fin_timeout = 30
net.ipv4.tcp_keepalive_time = 300
net.ipv4.tcp_orphan_retries = 2
EOF

# 加载
sysctl --system

# 关闭ipv6
echo 1 > /proc/sys/net/ipv6/conf/ens5/disable_ipv6
