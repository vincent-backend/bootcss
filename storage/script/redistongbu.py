import redis

r1 = redis.Redis(host='42.236.73.60', port=6379, db=0, password='tPC0zsS1yKX4ndW')
r2 = redis.Redis(host='42.236.74.165', port=6379, db=0)
keyss = r2.keys("*")

visitors_list = r1.keys("visitors*")

for visitor in visitors_list:
    ip = r1.hmget(visitor,'ip')[0].decode()
    if ip in keyss:
        continue
    else:
        print(ip)
        time = 60*60*24*30*3;
        r2.setex(ip,time,'1')