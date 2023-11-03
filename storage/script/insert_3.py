import redis
import sys
from urllib.parse import urlparse

r = redis.Redis(host='42.236.74.165', port=6379, db=0)

def insert_redis(file, table):
    # 打开 txt 文件，按行读取并添加到 Redis 集合中
    with open(file, 'r',encoding='utf-8') as f:
        for line in f:
            try:
            # 删除行末的换行符
                line = line.rstrip('\n')
                res = r.sadd(table, "http://" + line)
                res = r.sadd(table, "https://" + line)
                
            except Exception as e:
                print(e)
                continue

if __name__ == '__main__':
    insert_redis(sys.argv[1],sys.argv[2])
    