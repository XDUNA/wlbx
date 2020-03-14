//
function init_report(){
    'use strict';
	get_wechatbind();
    console.log("here");
}

function initas_button(){
    'use strict';
    $("#report_area").empty();
    $("#report_area").append("<button type=\"button\" class=\"btn btn-primary btn-lg btn-block\" onClick=\"javascript:location.href='" + wechatbind.url + "&redirect=页面:报告" + "'\">点击认证身份</button>");
    $("#report_header").append("<strong>身份认证</strong>");
}

function initas_warning(){
    'use strict';
    $("#report_area").empty();
    $("#report_header").append("<strong>您没有权限访问该页面</strong>");
}

function initas_table(){
    'use strict';
    $("#report_header").append("<strong>加载中...</strong>");
    $.post("interface.php",{													//与服务器通信
			"queryrouterreport":"querytoday",												//传送参数
            "require":"queryrouterreport"
		},function(data){
            if(data.error === 0){
                if (data.contentlen > 0) {
                    $("#report_header").empty();
                    $("#report_header").append("<strong>交换机扫描报告:</strong>");
                    $("#report_danger").append("<tr class=\"danger\"><th>故障设备ip</th></tr>");
                    for (var i = 0; i < data.contentlen; i++) {
                        if(data[i].cond === "0"){
                            $("#report_danger").append("<tr><td>" + decode64(data[i].host) + "</td></tr>");
                        }
                    }
                    $("#report_warning").append("<tr class=\"warning\"><th>不畅通设备ip</th><th>丢包率(%)</th><th>平均延迟(ms)</th></tr>");
                    for (var i = 0; i < data.contentlen; i++) {
                        if(data[i].cond === "1" && data[i].loss !== "0"){
                            $("#report_warning").append("<tr><td>" + decode64(data[i].host) + "</td><td>" + data[i].loss + "</td><td>" + data[i].rtt + "</td></tr>");
                        }
                    }
                    $("#report_info").append("<tr class=\"info\"><th>正常设备ip</th><th>平均延迟(ms)</th></tr>");
                    for (var i = 0; i < data.contentlen; i++) {
                        if(data[i].cond === "1" && data[i].loss === "0"){
                            $("#report_info").append("<tr><td>" + decode64(data[i].host) + "</td><td>" + data[i].rtt + "</td></tr>");
                        }
                    }
                }else{
                    $("#report_header").empty();
                    $("#report_header").append("<strong>今日报告暂未生成</strong>");
                    $("#report_area").empty();
                }
            }
		},'json');
}

var wechatbind = {
    "key":'',
    "url":'',
    "bind":0,
};
      
      
function get_wechatbind() {
	'use strict';//生成二维码
	$.post("interface.php",{													//与服务器通信
			"action":"wechatbind",												//传送参数
            "require":"wechatbind"
		},function(data){
            if(data.error === 0){
                if(data.bind === 0){													//没有绑定微信则生成绑定二维码
                    wechatbind.bind = 0;
                    wechatbind.url = data.url;
                    wechatbind.key = data.key;
                    initas_button();
                    setTimeout(check_wechatbind_report,1000);
                } else if(data.bind === 1){											//已经绑定则显示已经绑定的图片
                    check_permission_report();
                }
            }
		},'json');
}

function check_wechatbind_report() {
    'use strict';
    $.post("interface.php",{
        "action":"query",
        "require":"wechatbind"
    },function(data) {
        if(data.error === 0){
            wechatbind.bind = data.bind;
            if(data.bind === 0){
                if(nowpage === 'report'){
                    setTimeout(check_wechatbind_report,1000);
                }
            }else if(data.bind === 1){
                check_permission_report();
                //
            }
        }
    },'json');
}



function check_permission_report() {
    'use strict';
    $.post("interface.php",{
        "action":"query",
        "require":"permission"
    },function(data) {
        if(data.error === 0){
            if(data.permission === 1){
                //
                initas_table();
            }else{
                //
                initas_warning();
            }
        }
    },'json');
}

