```bash
#!/bin/sh
# 记录安装Geoip2
exit

# 1. 准备基础数据
mkdir -p /data/vendor/geo2db
cd /data/vendor/geo2db
# rz -bye 
wget https://raw.githubusercontent.com/Loyalsoldier/geoip/release/Country.mmdb
chown -R www:www /data/vendor


# 2. 安装访问数据客户端
cd ~/downloads
wget https://github.com/maxmind/libmaxminddb/releases/download/1.6.0/libmaxminddb-1.6.0.tar.gz
tar zxvf libmaxminddb-1.6.0.tar.gz
cd libmaxminddb-1.6.0
./configure && sudo make && sudo make install
echo '/usr/local/lib' > /etc/ld.so.conf.d/local.conf
ldconfig
# 测试客户端访问
mmdblookup --file /data/vendor/geo2db/Country.mmdb --ip 165.154.227.253

# 3. Nginx添加geoip模块
mkdir -p /data/vendor
cd /data/vendor
wget https://github.com/leev/ngx_http_geoip2_module/archive/refs/heads/master.zip
unzip master.zip
mv ngx_http_geoip2_module-master /data/vendor/ngx_http_geoip2_module
# 将参数添加到lnmp配置里、编译升级nginx
cd ~/lnmp1.8
vi lnmp.conf
./upgrade.sh nginx


# 4. Nginx配置
# geoip2-配置nginx文件，增加IP映射源
# 支持ipv6: https://blog.c1gstudio.com/archives/1869
map $http_x_forwarded_for $geoip2_realip {
	~^(?P<firstAddr>[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+|[0-9a-fA-F]+:[0-9a-fA-F:]+:[0-9a-fA-F\.]+),?.*$ $firstAddr;
	default $remote_addr;
}
# geoip2-读取mmdb数据
geoip2 /data/vendor/geo2db/Country.mmdb {
	auto_reload 15m;
	$geoip2_metadata_country_build metadata build_epoch;
	# 国家编码
	$geoip2_country_code source=$geoip2_realip country iso_code;
	# 国家英文名-精简版没有
	# $geoip2_country_name_en source=$geoip2_realip country names en;
	# 国家中文名-精简版没有
	# $geoip2_country_name_cn source=$geoip2_realip country names zh-CN;
}
# 设置公共内部头
more_set_input_headers "maccms_geoip2_real_ip: $geoip2_realip";
more_set_input_headers "maccms_geoip2_country_code: $geoip2_country_code";
# 国家英文名-精简版没有
# more_set_input_headers "maccms_geoip2_country_name_en: $geoip2_country_name_en";
# 不能设置CN，因为头部编码不允许中文
# more_set_input_headers "maccms_geoip2_country_name_cn: $geoip2_country_name_cn";

```