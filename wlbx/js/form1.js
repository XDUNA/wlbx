// JavaScript Document
function init_form1(){
	'use strict';
	$("#form_title").append(form1_form_title);
	$("#form_back_home").append(timeline_btn_back_index);
	$("#label_name").append(form1_label_name);
	$("#label_stuid").append(form1_label_stuid);
	$("#label_tel").append(form1_label_tel);
	$("#label_time").append(form1_label_time);
	$("#label_bulnum").append(form1_label_bulnum);
	$("#label_detail").append(form1_label_detail);
	$("#label_wechat").append(form1_label_wechat);
	//$("#label_repairid").append(form1_label_repairid);
	$("#dividebar_p1").append(index_dividebar_p1);
	$("#dividebar_p2").append(index_dividebar_p2);
	$("#btnpool").append('<button type="button" class="btn btn-primary btn-lg btn-block" onClick="javascript:infomationcheck_prepost();">'+form1_btn1+'</button>');
	$("#btnpool").append('<button type="button" class="btn btn-primary btn-lg btn-block" onClick="javascript:loadpage(index_page);">'+form1_btn4+'</button>');
	$("#name").attr('placeholder',form1_name_placeholder);
	$("#stuid").attr('placeholder',form1_stuid_placeholder);
	$("#tel").attr('placeholder',form1_tel_placeholder);
	$("#detail").attr('placeholder',form1_detail_placeholder);
	//$("#repairid").attr('placeholder',form1_repairid_placeholder);
	for(var i=0;i<form1_bulnum_list.length;i++){
		$("#bulnum").append('<option value="'+form1_bulnum_list[i]+'">'+form1_bulnum_list[i]+form1_bulnum+'</option>');
	}
	for(var i=0;i<form1_floor_list[0].length;i++){
		$("#floor").append('<option value="'+form1_floor_list[0][i]+'">'+form1_floor_list[0][i]+form1_floor+'</option>');
	}
	/*for(var i=0;i<form1_floor_list[0].length;i++){
		$("#floor").append('<option value="'+form1_floor_list[0][i]+'">'+form1_floor_list[0][i]+form1_floor+'</option>');
	}*/
	for(var i=0;i<form1_section_list[0].length;i++){
		$("#section").append('<option value="'+form1_section_list[0][i]+'">'+form1_section_list[0][i]+'</option>');
	}
	for(var i=0;i<form1_roomside_list[0].length;i++){
		$("#roomside").append('<option value="'+form1_roomside_list[0][i]+'">'+form1_roomside_list[0][i]+'</option>');
	}
	for(var i=0;i<form1_room_list[0][0].length;i++){
		$("#roomnum").append('<option value="'+form1_room_list[0][0][i]+'">'+form1_room_list[0][0][i]+form1_room+'</option>');
	}
	if(form1_label_time_list.length % 2 == 0){
		for(var i=0;i<form1_label_time_list.length;i+=2){
			$("#time").append('<div data-toggle="buttons"><label class="btn btn-primary btn-lg" for="time'+i+'" id="label_time'+i+'"><input type="checkbox" autocomplete="off" name="time" value="'+i+'" id="time'+i+'">'+form1_label_time_list[i]+'</label><label class="btn btn-primary btn-lg" for="time'+(i+1)+'" id="label_time'+(i+1)+'"><input type="checkbox" autocomplete="off" name="time" value="'+(i+1)+'" id="time'+(i+1)+'">'+form1_label_time_list[i+1]+'</label></div>');
		}
	}else if(form1_label_time_list.length % 2 == 1){
		for(var i=0;i<form1_label_time_list.length - 1;i+=2){
			$("#time").append('<div data-toggle="buttons"><label class="btn btn-primary btn-lg" for="time'+i+'" id="label_time'+i+'"><input type="checkbox" autocomplete="off" name="time" value="+i+" id="time'+i+'">'+form1_label_time_list[i]+'</label><label class="btn btn-primary btn-lg" for="time'+(i+1)+'" id="label_time'+(i+1)+'"><input type="checkbox" autocomplete="off" name="time" value="'+(i+1)+'" id="time'+(i+1)+'">'+form1_label_time_list[i+1]+'</label></div>');
		}
		$("#time").append('<div data-toggle="buttons"><label class="btn btn-primary btn-lg" for="time'+(form1_label_time_list.length - 1)+'" id="label_time'+(form1_label_time_list.length - 1)+'"><input type="checkbox" autocomplete="off" name="time" value="'+(form1_label_time_list.length - 1)+'" id="time'+(form1_label_time_list.length - 1)+'">'+form1_label_time_list[(form1_label_time_list.length - 1)]+'</label></div>');
	}
	
	makeCode();
//	if(form1_label_time_list.length % 2 == 0){
//		for(var i=0;i<form1_label_time_list.length;i+=2){
//			$("#time").append('<tr><td><label class="checkbox-inline" for="time'+i+'" id="label_time'+i+'"><input type="checkbox" name="time" value="'+i+'" id="time'+i+'">'+form1_label_time_list[i]+'</label></td><td><label class="checkbox-inline" for="time'+(i+1)+'" id="label_time'+(i+1)+'"><input type="checkbox" name="time" value="'+(i+1)+'" id="time'+(i+1)+'">'+form1_label_time_list[i+1]+'</label></td></tr>');
//		}
//	}else if(form1_label_time_list.length % 2 == 1){
//		for(var i=0;i<form1_label_time_list.length - 1;i+=2){
//			$("#time").append('<tr><td><label class="checkbox-inline" for="time'+i+'" id="label_time'+i+'"><input type="checkbox" name="time" value="+i+" id="time'+i+'">'+form1_label_time_list[i]+'</label></td><td><label class="checkbox-inline" for="time'+(i+1)+'" id="label_time'+(i+1)+'"><input type="checkbox" name="time" value="'+(i+1)+'" id="time'+(i+1)+'">'+form1_label_time_list[i+1]+'</label></td></tr>');
//		}
//		$("#time").append('<tr><td><label class="checkbox-inline" for="time'+(form1_label_time_list.length - 1)+'" id="label_time'+(form1_label_time_list.length - 1)+'"><input type="checkbox" name="time" value="'+(form1_label_time_list.length - 1)+'" id="time'+(form1_label_time_list.length - 1)+'">'+form1_label_time_list[(form1_label_time_list.length - 1)]+'</label></td></tr>');
//	}
}

function changeroomnum() {														//在选择楼层之后更改房间号
    'use strict';
	$("#roomnum").empty();
    var roof = $("#floor").val();
	var building = $("#bulnum").val();
	building = $.inArray(building,form1_bulnum_list);
	if(building > -1){
		roof = $.inArray(roof,form1_floor_list[building]);
		if(roof > -1){
			for(var i=0;i<form1_room_list[building][roof].length;i++){
				$("#roomnum").append('<option value="'+form1_room_list[building][roof][i]+'">'+form1_room_list[building][roof][i]+form1_room+'</option>');
			}
		}else{
			form1warning(form1_warning3);
		}
	}else{
		form1warning(form1_warning3);
	}
}

function changefloor() {														//在选择楼号之后更改层号
	'use strict';
    $("#floor").empty();
	var building = $("#bulnum").val();
	building = $.inArray(building,form1_bulnum_list);
	if(building > -1){
		for(var i=0;i<form1_floor_list[building].length;i++){
			$("#floor").append('<option value="'+form1_floor_list[building][i]+'">'+form1_floor_list[building][i]+form1_floor+'</option>');
		}
	}else{
		form1warning(form1_warning3);
	}
}

function changesection() {														//在选择楼号之后更改区域号
	'use strict';
    $("#section").empty();
	var building = $("#bulnum").val();
	building = $.inArray(building,form1_bulnum_list);
	if(building > -1){
		for(var i=0;i<form1_section_list[building].length;i++){
			$("#section").append('<option value="'+form1_section_list[building][i]+'">'+form1_section_list[building][i]+'</option>');
		}
	}else{
		form1warning(form1_warning3);
	}
}

function changeroomside() {														//在选择楼号之后更改小室号
	'use strict';
    $("#roomside").empty();
	var building = $("#bulnum").val();
	building = $.inArray(building,form1_bulnum_list);
	if(building > -1){
		for(var i=0;i<form1_roomside_list[building].length;i++){
			$("#roomside").append('<option value="'+form1_roomside_list[building][i]+'">'+form1_roomside_list[building][i]+'</option>');
		}
	}else{
		form1warning(form1_warning3);
	}
}

function form1warning(warning){
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
				//$("#wechatbindurl").append('<a href="weixin://'+data.url+'">'+form1_wechatbindurl+'</a>');
			} else if(data.bind === 1){											//已经绑定则显示已经绑定的图片
				$("#wechatbind").empty();
				$("#wechatbind").append(data.content);
			}
		},'json');
}


function infomationcheck_prepost() {											//报修数据预检查
	'use strict';
    check_wechatbind_once();
	var time = new Array();
	$('input:checkbox[name="time"]:checked').each(function() {					//获得空闲时间数据
		time.push($(this).val());												//值添加入数组
	});
	if(
		$("#bulnum").val() !== "" &&
		$("#detail").val() !== "" &&
		$("#floor").val() !== "" &&
		$("#name").val() !== "" &&
		$("#roomnum").val() !== "" &&
		$("#roomside").val() !== "" &&
		$("#section").val() !== "" &&
		$("#stuid").val() !== "" &&
		$("#tel").val() !== "" &&												//逐个判断是否为空值
        wechatbind.bind === 1 &&
		time.length > 0
	){
        $("html,body").animate({ 
				scrollTop:$("#banner").height()-$("#nav").height() + $("#banner").offset().top
			}, time, function(){});
		$("#bulnum").attr("disabled", "disabled");
		$("#detail").attr("disabled", "disabled");
		$("#floor").attr("disabled", "disabled");
		$("#name").attr("disabled", "disabled");
		$("#time").attr("disabled", "disabled");
		$("#roomnum").attr("disabled", "disabled");
		$("#roomside").attr("disabled", "disabled");
		$("#section").attr("disabled", "disabled");
		$("#stuid").attr("disabled", "disabled");
		$("#tel").attr("disabled", "disabled");
		$("#time").attr("disabled", "disabled");								//逐个禁用输入框，等待用户核实信息
		$('input:checkbox[name="time"]').each(function() {						//获得空闲时间数据
			$(this).attr("disabled", "disabled");								//值添加入数组
			$(this).parent().attr("disabled", "disabled");
		});
		$('input:checkbox[name="time"]:checked').each(function() {					//获得空闲时间数据
			$(this).parent().addClass("btn-success");												//
		});
		form1warning(form1_warning1);				
		$("#btnpool").fadeOut(400,function(){
			$("#btnpool").empty();
			$("#btnpool").append('<button type="button" class="btn btn-primary btn-lg btn-block" onClick="javascript:infomation_cancel();">'+form1_btn2+'</button>');
			$("#btnpool").append('<button type="button" class="btn btn-primary btn-lg btn-block" onClick="javascript:infomation_confirm();">'+form1_btn3+'</button>');
			$("#btnpool").fadeIn(400);
		});
	}
	else 
	{																			//有空值，提示用户需要全部填入
		form1warning(form1_warning2);
	}
}
function infomation_cancel(){
	'use strict';
	$("#bulnum").removeAttr("disabled");
	$("#detail").removeAttr("disabled");
	$("#floor").removeAttr("disabled");
	$("#name").removeAttr("disabled");
	$("#time").removeAttr("disabled");
	$("#roomnum").removeAttr("disabled");
	$("#roomside").removeAttr("disabled");
	$("#section").removeAttr("disabled");
	$("#stuid").removeAttr("disabled");
	$("#tel").removeAttr("disabled");
	$("#time").removeAttr("disabled");											//逐个清除禁用属性
	$('input:checkbox[name="time"]').each(function() {							//获得空闲时间数据
		$(this).removeAttr("disabled");											//值添加入数组
		$(this).parent().removeAttr("disabled");
	});
	$('input:checkbox[name="time"]:checked').each(function() {					//获得空闲时间数据
		$(this).parent().removeClass("btn-success");												//
	});
	$("#btnpool").fadeOut(400,function(){										//替换提交按键
		$("#btnpool").empty();
		$("#btnpool").append('<button type="button" class="btn btn-primary btn-lg btn-block" onClick="javascript:infomationcheck_prepost();">'+form1_btn1+'</button>');
		$("#btnpool").fadeIn(400);
        $("#btnpool").append('<button type="button" class="btn btn-primary btn-lg btn-block" onClick="javascript:loadpage(index_page);">'+form1_btn4+'</button>');
	});
}

function infomation_confirm(){
	'use strict';
	var bulnum = $("#bulnum").val();
	var detail = $("#detail").val();
	var floors = $("#floor").val();
	var name = $("#name").val();
	var time = new Array();
	$('input:checkbox[name="time"]:checked').each(function() {					//获得空闲时间数据
		time.push($(this).val());												//值添加入数组
	});
	var roomnum = $("#roomnum").val();
	var roomside = $("#roomside").val();
	var section = $("#section").val();
	var stuid = $("#stuid").val();
	var tel = $("#tel").val();
	$.post("interface.php",{
			"bulnum":bulnum,
			"detail":detail,
			"floor":floors,
			"name":name,
			"time":time,
			"roomnum":roomnum,
			"roomside":roomside,
			"section":section,
			"stuid":stuid,
			"tel":tel,
			"datapost":"submit",
            "require":"datapost"
		},function (data){
			if(data.error === 0){
				eval(decode64(data.script));
			}else if(data.error === 1){
				form1warning(form1_warning4);
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
                if(nowpage === 'form1'){
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
                check_permission();
                //$('#searchbox').popover('show');
                //$("#"+wechatbind.popid+" canvas").hide();
            }
        }
    },'json');
}

