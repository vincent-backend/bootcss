import redis
import sys
from urllib.parse import urlparse

table_array = ['bc_list','maccms_yingshi_list','tiyu_list','xs_list','qp_list','dz_list','sm_list','6hecai_list','cp_list']
r = redis.Redis(host='42.236.74.165', port=6379, db=1)
r0 = redis.Redis(host='42.236.74.165', port=6379, db=0)

def nofugai(table: str, domain: str):
    temp = False
    for  value2 in table_array:
        if table == value2:
            continue
        else:
            if domain.startswith("http"):
                res = r.sismember(value2, domain)
                if res == True:
                    temp = True
            else:
                res = r.sismember(value2, "http://" + domain)
                if res == True:
                    temp = True
                res = r.sismember(value2, "https://" + domain)
                if res == True:
                    temp = True
    if temp == False:
        if domain.startswith("http"):
            res = r.sadd(table, domain)
        else:
            res = r.sadd(table, "http://" + domain)
            res = r.sadd(table, "https://" + domain)
    return res

def fugai(table: str, domain: str):
    if domain.startswith("http"):
        res = r.sadd(table, domain)
    else:
        res = r.sadd(table, "http://" + domain)
        res = r.sadd(table, "https://" + domain)
    for value2 in table_array:
        if table == value2:
            continue
        else:
            if domain.startswith("http"):
                res = r.srem(value2, domain)
            else:
                res = r.srem(value2, "http://" + domain)
                res = r.srem(value2, "https://" + domain)
    return res

def insert_redis(file, table, isfugai = "false"):
    # 打开 txt 文件，按行读取并添加到 Redis 集合中
    with open(file, 'r',encoding='utf-8') as f:
        for line in f:
            try:
            # 删除行末的换行符
                line = line.rstrip('\n')
                host = urlparse(line).hostname
                if host == '' or host == None:
                    test_host = "https://" + line
                    host = urlparse(test_host).hostname
                    res = r.sismember("access_host", host)
                else :
                    res = r.sismember("access_host", host)
                if line.endswith(".gov.cn") or line.endswith(".edu.cn") or res != True :
                    continue
                line1 = urlparse(line).scheme + "://" + urlparse(line).netloc + urlparse(line).path
                if isfugai == "true":
                    fugai(table,line1)
                else:
                    nofugai(table,line1)
            except Exception as e:
                print(e)
                continue

if __name__ == '__main__':
    if len(sys.argv) > 2:
        insert_redis(sys.argv[1],sys.argv[2],sys.argv[3])
    