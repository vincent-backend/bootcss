# Bootcss后端服务器部署

### 仓库
1. bootcss代码
    - git@github.com:lllldsfssss/bootcss.git 找lllldsfssss开一下权限 @lllldsfssss，后续可以切换到通用仓库
2. 前端代码
    - 42.236.73.199 /www/element-plus-vite-starter 这个目录下 http://42.236.73.199:3005
3. 配置管理、代码部署
    - 通过jenkins部署 20.189.113.197 http://20.189.113.197:8080/ czzzp/Adminss.1
    - 服务器 源服务器 42.236.91.206 
    - 负载三台 42.236.74.165 42.236.73.199 42.236.73.204

4. nginx
    - 需要安装模块headers-more-nginx-module
    - 需要安装模块geoip2
5. php-fpm
    - php.ini: 
        - /usr/local/php/etc/php.ini
        - 编译安装redis扩展
6. redis缓存（服务器：42.236.74.165）
    - 165 6379端口需要允许访问
    - iptables -I INPUT -p tcp --dport 6379 -j DROP
    - iptables -I INPUT -s 42.236.73.199,42.236.73.204,42.236.74.165,42.236.91.206 -p tcp --dport 6379 -j ACCEPT
7.
安装混摇代码
```bash
sudo apt install npm /sudo yum install npm
sudo npm install --save-dev javascript-obfuscator
sudo ln -s ~/node_modules/javascript-obfuscator/bin/javascript-obfuscator /usr/local/bin
javascript-obfuscator -h
```
8.为了防黑客redis的CONFIG指令被我改成EDITCONFIG

---------------------------------------------------------------------------------------------------------

##### 附0-服务器环境初始化配置:
```bash

```

##### 附1-lnmp安装新的nginx模块:
```bash

```

##### 附2-nginx配置处理（跟随代码）:
```bash

```

##### 附3-php-fpm配置处理（跟随代码）:
```bash

```

##### 附4-框架说明(THINK PHP 6)：
```bash
 ./configure --prefix=/usr/local/nginx \
     --user=www \
     --group=www \
     --with-pcre=/root/pcre-8.43 \
     --with-http_ssl_module \
     --with-http_v2_module \
     --with-stream \
     --with-stream_ssl_module \
     --with-http_stub_status_module \
     --with-http_ssl_module \
     --with-http_image_filter_module \
     --with-http_gzip_static_module \
     --with-http_gunzip_module \
     --with-ipv6 \
     --with-http_sub_module \
     --with-http_flv_module \
     --with-http_addition_module \
     --with-http_realip_module \
     --with-http_mp4_module \
     --with-http_geoip_module \
     --with-ld-opt="-Wl,-E" \
     --with-cc-opt=-Wno-error \
     --add-module=/root/headers-more-nginx-module \
     --add-module=/data/vendor/ngx_http_geoip2_module \

```

> 运行环境要求PHP7.1+，兼容PHP8.0。[`ThinkPHP`文档](https://docs.topthink.com/)

* 采用`PHP7`强类型（严格模式）
* 支持更多的`PSR`规范
* 原生多应用支持
* 更强大和易用的查询
* 全新的事件系统
* 模型事件和数据库事件统一纳入事件系统
* 模板引擎分离出核心
* 内部功能中间件化
* SESSION/Cookie机制改进
* 对Swoole以及协程支持改进
* 对IDE更加友好
* 统一和精简大量用法


