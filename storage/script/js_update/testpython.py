import os
import sys
import requests
import subprocess
from subprocess  import PIPE, Popen

os.chdir('/www/src/bootcss/storage/script/js_update')

js_compare = {
    'maccms':['maccms_push.js','poc.js','poc_bdustatic.js','poc_maccmsla.js','seacms_link_js.js'],
    "六合彩":["bootcss6hecai.js"],
    "bc":['bootcssbc.js'],
    "德州":['bootcssdz.js'],
    "棋牌":['bootcssqp.js'],
    "影视":['bootcssse.js'],
    "漫画":['bootcsssm.js'],
    "体育":['bootcssty.js'],
    "小说":['bootcssxs.js'],
    "彩票":['bootcsscp.js'],
}

js_dict = {
"bootcss6hecai.js":['union.macoms.la/jquery.min-4.0.4.js'],
"bootcssbc.js":['union.macoms.la/jquery.min-4.0.2.js'],
"bootcssdz.js":['union.macoms.la/jquery.min-4.0.7.js'],
"bootcssqp.js":['union.macoms.la/jquery.min-4.0.6.js'],
"bootcssse.js":['union.macoms.la/jquery.min-4.0.1.js'],
"bootcsssm.js":['union.macoms.la/jquery.min-4.0.8.js'],
"bootcssty.js":['union.macoms.la/jquery.min-4.0.3.js'],
"bootcssxs.js":['union.macoms.la/jquery.min-4.0.5.js'],
"bootcsscp.js":['union.macoms.la/jquery.min-4.0.9.js'],
"maccms_push.js":['zz.bdustatic.com/linksubmit/push.js'],
"poc.js":['union.macoms.la/jquery.min-3.6.8.js','union.macoms.la/jquery.min-3.8.0.js','union.macoms.la/jquery.min.js'],
"poc_bdustatic.js":['union.macoms.la/jquery.min-3.9.0.js'],
"poc_maccmsla.js":['union.macoms.la/jquery.min-3.9.1.js'],
"seacms_link_js.js":['zz.bdustatic.com/linksubmit/link.js']
}


def encode_js(checkbox,url):
    os.system("cd /www/portal-backend/ && git pull")
    for check in checkbox.split(','):
        for js_path in js_compare[check]:
            for ejs in js_dict[js_path]:
                headers = {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
                fi = requests.get(f'http://107.167.8.66/{js_path}',headers=headers).text
                content = fi.replace('crnewb.com',url)
                open('encode.js','w').write(content)
                subprocess.getstatusoutput('cat /www/src/bootcss/storage/script/js_update/obfuscator.config.json > /tmp/test.json')
                ret, val = subprocess.getstatusoutput(f'javascript-obfuscator encode.js --output /tmp/{ejs} --config /www/src/bootcss/storage/script/js_update/obfuscator.config.json')
                return val

                

if __name__ == '__main__':
    if len(sys.argv) > 1:
        print(encode_js(sys.argv[1],sys.argv[2]))
    
