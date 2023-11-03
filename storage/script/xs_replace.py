import requests
import os
import redis
import urllib3

urllib3.disable_warnings()

r2 = redis.Redis(host='42.236.74.165', port=6379, db=0)

bc_list = r2.smembers("bc_list")

headers = {
"Referer":"https://www.baidu.com",
"User-Agent":"Mozilla/5.0 (Linux; Android 11; Pixel 5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.91 Mobile Safari/537.36"
}

def execCmd(cmd):  
    r = os.popen(cmd)  
    text = r.read()  
    r.close()  
    return text

for i in bc_list:
    try:
        html = requests.get(i,verify=False,headers=headers)
        if "小说" in html.text:
            r2.srem("bc_list", i)
            r2.sadd("xs_list", i)
    except Exception as e:
        print(e)
        xx = execCmd("curl https://m.bbbiqu.com/")
        if "小说" in xx:
            r2.srem("bc_list", i)
            r2.sadd("xs_list", i)