// JavaScript Document

function init_more(){
	'use strict';
	$("#form_title").append(more_form_title);
	$("#label_name").append(more_label_name);
	$("#label_stuid").append(more_label_stuid);
	$("#label_tel").append(more_label_tel);
    $("#label_detail").append(more_label_detail);
	$("#dividebar_p1").append(more_dividebar_p1);
	$("#dividebar_p2").append(more_dividebar_p2);
	$("#btnpool").append('<button type="button" class="btn btn-primary btn-lg btn-block" onClick="javascript:feedback_post();" id="feedback_btn">'+more_btn1+'</button>');
	$("#btnpool").append('<button type="button" class="btn btn-primary btn-lg btn-block" onClick="javascript:loadpage(index_page);">'+more_btn2+'</button>');
	$("#feedback_name").attr('placeholder',more_name_placeholder);
	$("#feedback_id").attr('placeholder',more_stuid_placeholder);
	$("#feedback_tel").attr('placeholder',more_tel_placeholder);
	$("#feedback_detail").attr('placeholder',more_detail_placeholder);
}

function feedback_post(){
    'use strict';
    if($("#feedback_detail").val() !== ""){
        var name = $("#feedback_name").val();
        var fb_id = $("#feedback_id").val();
        var contact = $("#feedback_tel").val();
        var detail = $("#feedback_detail").val();
        if(name === ""){
            name = '匿名用户';
        }
        if(fb_id === ""){
            fb_id = '无信息';
        }
        if(contact === ""){
            contact = '无信息';
        }
        $.post("interface.php",{
			"action":"post_feedback",
            "name":name,
            "id":fb_id,
            "contact":contact,
            "detail":detail,
            "require":"feedback"
		},function (data){
            if(data.error === 0){
                $("#feedback_btn").empty();
                $("#feedback_btn").append(more_btn4);
                $("#feedback_btn").attr("disabled", "disabled");
            }
            else{
                $("#feedback_btn").empty();
                $("#feedback_btn").append(more_btn5);
                setTimeout(function(){
                    $("#feedback_btn").empty();
                    $("#feedback_btn").append(more_btn1);
                },1000);
            }
        },'json');
    }
    else{
        $("#feedback_btn").empty();
        $("#feedback_btn").append(more_btn3);
        setTimeout(function(){
            $("#feedback_btn").empty();
            $("#feedback_btn").append(more_btn1);
        },1000);
    }
}