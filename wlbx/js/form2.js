// JavaScript Document

var form2_form_title = "介四相声社丁酉年封箱专场<br />抢票活动";
var form2_label_name = "姓名:";
var form2_label_stuid = "学号:";
var form2_label_tel = "电话:";
var index_dividebar_p1 = "介四相声社";
var index_dividebar_p2 = "丁酉年封箱专场";
var form2_btn1 = "抢票";
var form2_btn2 = "票已抢完！";
var form2_btn3 = "初始化...";
var form2_btn4 = "刷新页面";
var form2_btn5 = "抢票成功！继续抢！";
var form2_name_placeholder = "这里填姓名~(￣▽￣)／";
var form2_stuid_placeholder = "这里填学号~(oﾟ▽ﾟ)o  ";
var form2_tel_placeholder = "这里填电话~(★ᴗ★)";
var form2_label_wechat = "绑定微信:";
var form2_warning1 = '抢票已过期！';
var form2_warning2 = '请填写所有必要的信息！并绑定微信！';
var form2_warning3 = '数据错误，请刷新页面！';
var form2_warning4 = '您已经抢过票了，或者票已经抢完！';

var base_info = {
    "time_left":0,
    "form_chang":1,
    "form_ci":0,
    "taket_left":0,
    "enable":0
};

function init_form2(){
	'use strict';
    query_base_inf();
    
    $("#dividebar_p1").append(index_dividebar_p1);
	$("#dividebar_p2").append(index_dividebar_p2);
	$("#form_title").append(form2_form_title);
	$("#label_name").append(form2_label_name);
	$("#label_stuid").append(form2_label_stuid);
	$("#label_tel").append(form2_label_tel);
    $("#label_wechat").append(form2_label_wechat);
	
	$("#btnpool").append('<button type="button" id="form2_submit" class="btn btn-primary btn-lg btn-block" onClick="javascript:infomationcheck_prepost();">'+form2_btn3+'</button>');
    $("#form2_submit").attr("disabled", "disabled");
	$("#name").attr('placeholder',form2_name_placeholder);
	$("#stuid").attr('placeholder',form2_stuid_placeholder);
	$("#tel").attr('placeholder',form2_tel_placeholder);
    $("#from_pic").append('<img src="images/jiesi_qiangpiao.jpg" />');
	
	makeCode();
    //btn_change();
}

function query_base_inf() {
    'use strict';
    $.post("interface.php",{
        "chang":base_info.form_chang,
        "qiangpiao":"query",
        "require":"qiangpiao"
    },function(data) {
        if(data.error === 0){
            if(data.contentlen > 0){
                base_info.enable = 1;
                base_info.time_left = data[0].begin_time - data[0].now_time;
                base_info.taket_left = data[0].tkt_limit - data[0].tkt_count;
                base_info.form_ci = data[0].ci;
            }
            else{

            }
        }else{
            //
        }
        btn_change();
    },'json');
}

function btn_change() {
    'use strict';
    //console.log("ck1");
    if(nowpage === 'form2'){
        if(base_info.time_left >= 0 && base_info.enable === 1){
            setTimeout(btn_change,1000);
            var time_hms = parseInt(base_info.time_left / 3600) + "小时" + parseInt(base_info.time_left / 60 % 60) + "分钟" + base_info.time_left % 60 + "秒后开始";
            $("#form2_submit").empty();
            $("#form2_submit").append(time_hms);
            base_info.time_left--;
        }else if(base_info.enable === 0){
            form2warning(form2_warning1);
        }else if(base_info.taket_left > 0){
            $("#form2_submit").removeAttr("disabled");
            $("#form2_submit").empty();
            $("#form2_submit").append(form2_btn1);
        }else{
            $("#form2_submit").empty();
            $("#form2_submit").append(form2_btn2);
        }
    }
}

function form2warning(warning){
	'use strict';
	$("#warning").fadeOut(400,function(){										//淡出警告框
		$("#warning").empty();													//清空警告
		$("#warning").append('<p><strong>'+warning+'</strong></p>');			//设置警告
		$("#warning").fadeIn(400);												//淡入警告
	});	
}

function checkwechatbind() {													//生成二维码
	'use strict';
	$.post("interface.php",{													//与服务器通信
			"action":"wechatbind",												//传送参数
            "require":"wechatbind"
		},function(data){														//获取绑定状态
			if(data.bind === 0){													//没有绑定微信则生成绑定二维码
				$("#wechatbind").empty();
				$("#wechatbind").append('<div id="qrcode" style="width:200px; height:200px; margin-top:15px;"></div>');
				var qrcode = new QRCode(document.getElementById('qrcode'), {	//定义二维码创建对象
					width: 200,
					height: 200,												//尺寸
					correctLevel: QRCode.CorrectLevel.M							//校验等级
				});
				qrcode.makeCode(data.url);
			} else if(data.bind === 1){											//已经绑定则显示已经绑定的图片
				$("#wechatbind").empty();
				$("#wechatbind").append(data.content);
			}
		},'json');
}


function infomationcheck_prepost() {											//报修数据预检查
	'use strict';
    check_wechatbind_once();
	if(
		$("#name").val() !== "" &&
		$("#stuid").val() !== "" &&
		$("#tel").val() !== "" &&												//逐个判断是否为空值
        wechatbind.bind === 1
	){
        $("#name").attr("disabled", "disabled");
		$("#stuid").attr("disabled", "disabled");
		$("#tel").attr("disabled", "disabled");
        infomation_confirm();
	}
	else 
	{																			//有空值，提示用户需要全部填入
		form2warning(form2_warning2);
	}
}

function infomation_confirm(){
	'use strict';
	
	var name = $("#name").val();
	var stuid = $("#stuid").val();
	var tel = $("#tel").val();
	$.post("interface.php",{
			"name":name,
			"stuid":stuid,
			"tel":tel,
            "chang":base_info.form_chang,
            "ci":base_info.form_ci,
			"qiangpiao":"submit",
            "require":"qiangpiao"
		},function (data){
			if(data.error === 0){
				//wechatclear();
                $("#form2_submit").attr("onClick", "reloadpage()");
                $("#form2_submit").empty();
                $("#form2_submit").append(form2_btn5);
			}else if(data.error === 1){
				form2warning(form2_warning4);
                $("#form2_submit").attr("onClick", "wechatclear()");
                $("#form2_submit").empty();
                $("#form2_submit").append(form2_btn4);
			}
		},'json');
}

function wechatclear(){
    'use strict';
    $.post("interface.php",{
        "qiangpiao":"refreash",
        "require":"qiangpiao"
    },function (data){
        if(data.error === 0){
            reloadpage();
        }else if(data.error === 1){
            form2warning(form2_warning4);
        }
    },'json');
}

var wechatbind = {
    "key":'',
    "url":'',
    "bind":0
};

var successimg = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAMAAACahl6sAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAAZQTFRFAar/AAAAiqnrrAAAAAJ0Uk5T/wDltzBKAAAES0lEQVR42uzd23LcIAwGYP3v/9KdadquwQJs0HGr3GSyyRg+2xwtOYQv+aKCFOR/h9CXQIjoayD0DRCihYQyOWYSSuWYSCiXYyyhZI6hhLI5RhJK5xhIKJ+Dl1BCByuhjA5OQikdjIRyOu4SSuq4SSiro5dQWkcnobyOVkKJHY2EMjuuEkrtuEgotyM2ZMcREbLlCAjZc8SDbDrCQXYd0SDbjmCQfUcsyIEjFOTEEQly5AgEOXPEgRw6wkBOHVEgx44gkHNHCAgJOCJARBwBIDIOf4iQwx0i5fCGiDmcIXKOPBBEhgg6XCGSDk+IqMMRIuvwgwg73CDSDi+IuMMJIu/wgSg4oPGXHo7n1Xt1VHvHY8jb41o7nkJ2Dm3qeAjZPLih4zWEgjqeQU4KMHI8ghwWYeJ4AjkvxMDxACJSjLpjDZEqSNmxhMhdel3HMuFHrFNRdiwgcsOVtmMOkZvRqTumELm1gr5jBpFbhYaHUBzHBCK2c2biGEPE9mRtHEOI2G6/kWMEEXuOZMQYQUjoRNo5eAiRjMTQIXFFiAI4BNrIOF7H0gGBszkKaTN1HI8j42BJWwdEWioXhmvsgFDneQvwtnacrEdmqQPmDogNzNdKOTi21+yzank4IDnp+1MxF8fmvtasaj4OyC4ohOZoOhCkcEB6serl2Hg+EtMB+Y0QHwc09qY8HK+es0d2QGXf08HxJjAjtANKe+rmjnehMl8DQVwH9J482TpeR2FFdUDzqaalYyMuLqZjK8AvogPaQQxWjs2Qy3gO6AfI2Di2g2CjOWARfGXhOAhLjuVA0MA+UwgiOWARNIrwEMRxHOcgRHFAPUYcWSCI4YBy/gEyQRDBIZNwFMABzSwd5IPA3QG9DDDkhMDZoZZdiLwQwUwsbwgcHcK5un4O6aRjN4d49rSXAxT+gMHLLUhBClKQghSkIAUpSEEKUpB3oYDt960j/iwhaZrbpQ+hNeRB0uu0tkovr2CLaNJEtiBIAFnVxB/yKeEvhL/LFv9upq9ovz1pAWkKbiCrR1bPEpkMIb/rf4Es08PWkEvlz9688uqCXPvODrK4GYn5EB6QniAAgRfkclsfQLDo6D4oVUjXa10rOnv2docsfg3FNvJpJv8ae1+bVxBygmAFmQ1obcLl7a+MIcyA+HRg7zJH40Fw/7dxNLlMDyCkD7kWxkP6m2Zwmn86BnhB2O4XNB3VUkFoA8JMHN8t3o6nKNcn0N14MD2V7e94SHti9CB9/9NfEZoNye3ScvTKDlNIW6v+9QizuVQzced75tuaR2/N3qxB0E7UuY6VgdyaT7MWUYd0A1rf+XxOKA/pGsinUXQng7YXvO/fiX29i/kuazG5patmsBGkCeG3IG4f3meO/UpxsHPBtDflXut+FzP3PNuCwZCYn7cCWkQgGO6MjIuj6eiu8gbmHNvOtRtfkIIUpCAFKUhBClKQghSkIAX5+folwAAHBX6C5WcYiAAAAABJRU5ErkJggg==";
function makeCode() {
	'use strict';//生成二维码
	var qrcode = new QRCode(document.getElementById('qrcode'), {					//定义二维码创建对象
		width: 200,
		height: 200,																//尺寸
		correctLevel: QRCode.CorrectLevel.L											//校验等级
	});
	$.post("interface.php",{													//与服务器通信
			"action":"wechatbind",												//传送参数
            "require":"wechatbind"
		},function(data){														//获取绑定状态
            if(data.error === 0){
                if(data.bind === 0){													//没有绑定微信则生成绑定二维码
                    //$("#wechatbind").empty();
                    //$("#wechatbind").append('<div id="qrcode" style="width:200px; height:200px; margin-top:15px;"></div>');
                    wechatbind.url = data.url;
                    wechatbind.key = data.key;
                    qrcode.makeCode(data.url);
                    setTimeout(check_wechatbind,1000);
                } else if(data.bind === 1){											//已经绑定则显示已经绑定的图片
                    //$("#wechatbind").empty();
                    //$("qrcode").removeAttr("src");
                    $("#qrcode img").attr("src",successimg);
                    $("#qrcode img").show();
                    $("#qrcode canvas").hide();
                    wechatbind.bind = 1;
                }
            }
		},'json');
}

function check_wechatbind() {
    'use strict';
    $.post("interface.php",{
        "action":"query",
        "require":"wechatbind"
    },function(data) {
        if(data.error === 0){
            wechatbind.bind = data.bind;
            if(data.bind === 0){
                if(nowpage === 'form2'){
                    setTimeout(check_wechatbind,1000);
                }
            }else{
                //$("#qrcode img").removeAttr("src");
                $("#qrcode img").attr("src",successimg);
                $("#qrcode img").show();
                $("#qrcode canvas").hide();
            }
        }
    },'json');
}

function check_wechatbind_once() {
    'use strict';
    $.post("interface.php",{
        "action":"query",
        "require":"wechatbind"
    },function(data) {
        if(data.error === 0){
            wechatbind.bind = data.bind;
            if(data.bind === 0){
                //setTimeout(check_wechatbind,1000);
            }else{
                //$('#searchbox').popover('show');
                //$("#qrcode img").removeAttr("src");
                $("#qrcode img").attr("src",successimg);
                $("#qrcode img").show();
                //$('#searchbox').popover('show');
                //$("#"+wechatbind.popid+" canvas").hide();
            }
        }
    },'json');
}

