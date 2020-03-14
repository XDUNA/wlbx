// JavaScript Document
function init_index(){
	$("#page_title").append(index_page_title);
	$("#main_title").append(index_main_title);
	$("#sub_title").append(index_sub_title);
	$("#sidebar_title").append(index_sidebar_title);
	$("#sidebar_content").append(index_sidebar_content);
	$("#title_broad").append(footer_title_broad);
	$("#footer_menu_title").append(footer_footer_menu_title);
	$("#footer_another_title").append(footer_footer_another_title);
	$("#address_title").append(footer_address_title);
	$("#copyrights").append(copyrights);
	$("#keywords").attr("content",index_keywords);
	for(var i=0;i<footer_footer_menu.length;i++){
		$("#footer_menu").append('<li><a target="view_window" href="'+footer_footer_menu[i][0]+'">'+footer_footer_menu[i][1]+'</a></li>');
	}
	for(var i=0;i<footer_footer_another.length;i++){
		$("#footer_another").append('<li><a target="view_window" href="'+footer_footer_another[i][0]+'">'+footer_footer_another[i][1]+'</a></li>');
	}
	for(var i=0;i<footer_address.length;i++){
		$("#address").append('<li><strong>'+footer_address[i][0]+'</strong></li><li>'+footer_address[i][1]+'</li>');
	}
	$("#blank").height($("#nav").height());
}

function hidebanner(delay,time,callback){
	'use strict';
	setTimeout(function(){
		var scrollnow = $(document).scrollTop();
		if(scrollnow < ($("#banner").height()-$("#nav").height() + $("#banner").offset().top)){
			$("html,body").animate({ 
				scrollTop:$("#banner").height()-$("#nav").height() + $("#banner").offset().top
			}, time, function(){
				if($.isFunction(callback)){
					callback();
				}
			});
		}
	},delay);
}

function showbanner(delay,time,callback){
	'use strict';
	setTimeout(function(){
		$("html,body").animate({ 
				scrollTop:0
			}, time, function(){
				if($.isFunction(callback)){
					callback();
				}
			});
	},delay);
}


function hidenav(delay,time,callback){
	'use strict';
	$("#nav").addClass('displayfadeout');
	if($.isFunction(callback)){
		callback();
	}
}

function shownav(delay,time,callback){
	'use strict';
	$("#nav").removeClass('displayfadeout');
	if($.isFunction(callback)){
		callback();
	}
}

var scrollpos = 0;
var scrollnow = $(document).scrollTop();
$(document).scroll(function(){
	'use strict';
	scrollnow = $(document).scrollTop();
	if(scrollnow < scrollpos || scrollnow < $("#banner").height()){
		shownav(0,400,function(){
			scrollpos = scrollnow;
		});
	}else if(scrollnow > scrollpos && scrollnow > $("#banner").height()){
		hidenav(0,400,function(){
			scrollpos = scrollnow;
		});
	}else{
		scrollpos = scrollnow;
	}
	$("#blank").height($("#nav").height());
});