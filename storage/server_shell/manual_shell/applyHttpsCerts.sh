
# 使用acme.sh申请证书（需要临时关闭80端口nginx配置里的rewrite）
/root/.acme.sh/acme.sh --issue -d union.maccms.pro --nginx /etc/nginx/nginx.conf
cp /root/.acme.sh/union.maccms.pro/union.maccms.pro.key /data/certs/union.maccms.pro/key.pem
cp /root/.acme.sh/union.maccms.pro/fullchain.cer /data/certs/union.maccms.pro/fullchain.pem
service nginx reload

