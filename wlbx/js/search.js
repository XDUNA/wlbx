//
function init_search(){
	'use strict';
	$('#search_main_header').append(search_search_main_header);
	$('#search_sub_header').append(search_search_sub_header);
	//$('#searchbox_title').append(search_searchbox_title);
	$('#search_btn').append(search_search_btn);
	////2018-9-9后增加 一行
	$('#go2service_btn').append(go2service_btn);
	
	$('#service_main_header').append(search_service_main_header);
	$('#service_sub_header').append(search_service_sub_header);
	$('#searchbox').attr("placeholder",search_searchbox);
    $('#searchbox').attr("data-content",search_qrcode+'<img width="200" height="200" id="qrimg" style="display: block;">');
    //$('#searchbox').attr("onClick","check_wechatbind_once();");
    
	for(var i = 0;i < search_fn_left.length;i++){
		$('#servicebox_left').append('<div class="service-section-grid" style="cursor: pointer;" onClick="javascript:loadpage(\''+search_fn_left[i][0]+'\')"><div class="icon"><i class="'+search_fn_left[i][1]+'"></i></div><div class="icon-text"><h4>'+search_fn_left[i][2]+'</h4><p>'+search_fn_left[i][3]+'</p></div><div class="clearfix"></div></div>');
	}
	for(var i = 0;i < search_fn_right.length;i++){
		$('#servicebox_right').append('<div class="service-section-grid" style="cursor: pointer;" onClick="javascript:loadpage(\''+search_fn_right[i][0]+'\')"><div class="icon"><i class="'+search_fn_right[i][1]+'"></i></div><div class="icon-text"><h4>'+search_fn_right[i][2]+'</h4><p>'+search_fn_right[i][3]+'</p></div><div class="clearfix"></div></div>');
	}
}

function check_search(searchval){
	'use strict';
	var map = {"A":0,"B":1,"C":2,"D":3,"E":4,"F":5,"G":6,"H":7,"I":8,"J":9,"K":10,"L":11,"M":12,"N":13,"O":14,"P":15,"Q":16,"R":17,"S":18,"T":19,"U":20,"V":21,"W":22,"X":23,"Y":24,"Z":25,"1":26,"2":27,"3":28,"4":29,"5":30,"6":31,"7":32,"8":33,"9":34,"0":35,};
	var temp = 0;
	temp += map[searchval[0]] * 10;
	temp *= map[searchval[1]] + 22;
	temp += map[searchval[2]] * 8;
	temp %= 36;
	if(temp === map[searchval[3]]){
		return true;
	}else{
		return false;
	}
}

function search_warning(warning){
	'use strict';
	$('#search_btn').empty();
	$('#search_btn').append(warning);
	$('#search_btn').attr("disabled","disabled");
	setTimeout(function (){
		$('#search_btn').empty();
		$('#search_btn').append(search_search_btn);
		$('#search_btn').removeAttr("disabled");
	},3000);
}
function go2service(){
    loadpage('form1')
	//$("html,body").animate({scrollTop: //$("#service").offset().top}, 600);
}
function search(){
	'use strict';
    //check_wechatbind_once();
     //$('#searchbox').popover('hide');
    //$('#searchbox').focus();
	var searchval = $("#searchbox").val().toUpperCase();
	if(searchval.length === 4){
		if((/^[A-Z0-9]+$/).test(searchval) && check_search(searchval)){
			$.post("interface.php",{
				"search":searchval,
				"submit":"true",
                "require":"search"
			},function (data){
				if(data.error === 0){
					loadpage(search_result);
				}else if(data.error === 1){
					search_warning(search_search_btn_war3);
				}
			},'json');
		}
		else{
			search_warning(search_search_btn_war2);
		}
	}
    else if( wechatbind.bind === 1){
        $.post("interface.php",{
            "search":"wechat",
            "submit":"true",
            "require":"search"
        },function (data){
            if(data.error === 0){
                loadpage(search_result);
            }else if(data.error === 1){
                search_warning(search_search_btn_war3);
            }
        },'json');
    }
	else
	{
		search_warning(search_search_btn_war1);
	}
}

function search_id(id){
	'use strict';
	var searchval = id.toUpperCase();
	if(searchval.length === 4){
		if((/^[A-Z0-9]+$/).test(searchval) && check_search(searchval)){
			$.post("interface.php",{
				"search":searchval,
				"submit":"true",
                "require":"search"
			},function (data){
				if(data.error === 0){
					loadpage(search_result);
				}else if(data.error === 1){
					loadpage(index_page);
				}
			},'json');
		}
		else{
			loadpage(index_page);
		}
	}
    else if( wechatbind.bind === 1){
        $.post("interface.php",{
            "search":"wechat",
            "submit":"true",
            "require":"search"
        },function (data){
            if(data.error === 0){
                loadpage(search_result);
            }else if(data.error === 1){
                loadpage(index_page);
            }
        },'json');
    }
	else
	{
		loadpage(index_page);
	}
}

$("#searchbox").keydown(function(event){  //这里如果写成$(window)，在ie下面就不会起作用
    'use strict';
    if(event.keyCode === 13){
            $("#searchbox").popover('hide');
            search();
            return false;
    }
});


var successimg = "data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAMAAACahl6sAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAAZQTFRFAKnsAAAARPm6qAAAAAJ0Uk5T/wDltzBKAAADFElEQVR42uzc226EIBCA4Zn3f+km7VpOw1EE3PwmTbdFkA8HGL1Y0S85BAgQIECAAAECBAgQIECAAAECBAgQIECAAHkXRKQILBfbpeKK652KmpCGiy+CuP+JWTnuueRLV0JKVaRc/Nej378kBxkNLWmBSO5ohVxlckHSunbLNUi2xhjkqlMs1F+IXBB9ClLuqbquqIkvtux+W5Bq5PbNkQbIFRy5SeSfb8bd/0/s8E5esPx6cSH1hSDs2zUsNuRvhEZCawQiIn63ahXD+S9e79NV6jM6zZDqHKhEXjwbNPgYNRtPG7Ug+bn4EMSdG3TfAaLGMydqcunwsx+/FUh2R9bCBiXh+Mc2PzSiGIyj0hzDoGm5FulRSGmryBckq28Okp5lxJHbMvUZiJoacxsxIWFUZiAfQFOuJaV0rZq3SXWpNedIsLNbya9EycFaiHRBNIZYG3sakWOrljmdS5VbQyveGdWfzkEAnw0J9xGX5UgC6X4eqWzoWUjLHKlA3C7hTXFznFZB0jIpLL9+7qz2CtsDSZ+UzXR1LLTCvK8EKaZ6TdmvSwHiXw0QKUOkBpHsPqJxilCDBAt1N6T6YiQfWu1J4zUaRYjE+2vL5t7lcJfQ7KplP84Fd8a6rg2JbonUIZVndonvr1VoT7LoEkk6mkIkSQVEKw+ZPZD8ODetFtF9KGe/3e9PO+bIeNW2vvISGwgQIECAAAECBAiQ8QZbDyBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAABAgQIECBAgAB5N0TmH0DuhdZBjptz5BwHkCckW5ffUxxA5ku27+xnOGakKEc4gEyWHJL9HuAAMlVy0IPVdgeQiZLDntk3O4BMkxz4OmirY+p7rZ0OIHMkx74y3ecAMkNy9Nv4XQ4g9yV6OET3OIDclegLILrD8cwXTm5wALkl0ddAdLkDyA2Jvgqiix1AhiX6OogudQAZlOgrIbrQ8fC3lK9zABmR6IshusoBpF+iL4foGgeQXol+AURXOIAcd5EfAQYAxV6A4Zcwr+gAAAAASUVORK5CYII=";
var wechatbind = {
    "key":'',
    "url":'',
    "bind":0,
};

function creatqrcode() {
    'use strict';
    $('#searchbox').removeAttr("onFocus");
    $('#searchbox').popover({html:true});
    $('#searchbox').popover('show');
    $('#qrimg').parent("div").parent("div").append('<div id="qrcode"></div>');
    $('#searchbox').attr("data-content",search_qrcode);
    makeCode_search();
}
      
      
function makeCode_search() {
	'use strict';//生成二维码
    $('#qrimg').hide();
	var qrcode = new QRCode(document.getElementById("qrcode"), {					//定义二维码创建对象
		width: 200,
		height: 200,																//尺寸
		correctLevel: QRCode.CorrectLevel.L											//校验等级
	});
	$.post("interface.php",{													//与服务器通信
			"action":"wechatbind",												//传送参数
            "require":"wechatbind"
		},function(data){
            if(typeof($("#searchbox").attr("aria-describedby"))=="undefined"){
                $('#searchbox').popover('show');
            }//获取绑定状态
            if(data.error === 0){
                if(data.bind === 0){													//没有绑定微信则生成绑定二维码
                    wechatbind.bind = 0;
                    //$("#wechatbind").empty();
                    //$("#wechatbind").append('<div id="qrcode" style="width:200px; height:200px; margin-top:15px;"></div>');
                    wechatbind.url = data.url;
                    wechatbind.key = data.key;
                    qrcode.makeCode(data.url);
                    setTimeout(check_wechatbind_search,1000);
                    //$("#qrimg img").attr("id","qrcodeimg");
                    //$("#qrimg canvas").attr("id","qrcodecan");
                    //$("#qrcodeimg").attr("onClick",'javascript:window.location.href=\'weixin://' + data.url.substr(7, data.url.length - 7) + '\'');
                } else if(data.bind === 1){											//已经绑定则显示已经绑定的图片
                    //$("#wechatbind").empty();
                    //$("qrcode").removeAttr("src");
                    //$("#"+wechatbind.popid+" img").attr("src",successimg);
                    qrcode.makeCode(data.url);
                    //$("#qrimg img").attr("id","qrcodeimg");
                    //$("#qrimg canvas").attr("id","qrcodecan");
                    //$("#qrcodeimg").show();
                    //$("#qrcodeimg").prev().attr("src",successimg);
                    wechatbind.bind = 1;
                    check_permission_search();
                    draw_img();
                    //$("#qrcodeimg").hide();
                }
            }
		},'json');
}

function check_wechatbind_search() {
    'use strict';
    $.post("interface.php",{
        "action":"query",
        "require":"wechatbind"
    },function(data) {
        if(data.error === 0){
            wechatbind.bind = data.bind;
            if(data.bind === 0){
                if(nowpage === 'search'){
                    setTimeout(check_wechatbind_search,1000);
                }
            }else if(data.bind === 1){
                //$('#searchbox').popover('show');
                //$("#qrcode img").removeAttr("src");
                //$("#qrcodeimg").attr("src",successimg);
                //$("#qrcodeimg").show();
                check_permission_search();
                //$("#qrcodeimg").hide();
                //$("#qrcodeimg").prev().attr("src",successimg);
                draw_img();
                //$('#searchbox').popover('show');
                //$("#"+wechatbind.popid+" canvas").hide();
            }
        }
    },'json');
}



function check_permission_search() {
    'use strict';
    $.post("interface.php",{
        "action":"query",
        "require":"permission"
    },function(data) {
        if(data.error === 0){
            if(data.permission === 1){
                //$('#searchbox').popover('show');
                if(typeof($("#searchbox").attr("aria-describedby"))=="undefined"){
                    $('#searchbox').popover('show');
                }
                $("#qrcode").parent("div").attr("onClick","$('#searchbox').popover('hide');loadpage(\"manage\");");
                //$("#searchbox").attr("onClick","$('#searchbox').popover('hide');loadpage(\"manage\");");
            }else{
            }
        }
    },'json');
}

var success_image = new Image();
success_image.src = successimg ;

function draw_img() {
    'use strict';
    //var image = new Image();
    //image.src = "/images/successimg.png";

    if(typeof($("#searchbox").attr("aria-describedby"))=="undefined"){
        $('#searchbox').popover('show');
    }
    //$('#searchbox').popover('show');
    //var image = new Image();
    //image.src = successimg;
    //image.src = "/images/top-arrow.png";
    var c = $("#qrcode canvas")[0];
    var ctx = c.getContext("2d");
    ctx.fillStyle = 'rgba(225,225,225,0.8)';
    ctx.fillRect(0,0,200,200);
    //ctx.clearRect(0,0,200,200);
    ctx.drawImage(success_image,0,0);
    $("#qrcode canvas").show();
    $("#qrcode img").hide();
}
