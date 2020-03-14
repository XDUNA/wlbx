// JavaScript Document
function init_e404(){
	'use strict';
	$("#content").append(e404_content);
	$("#back").append(e404_back);
	$("#back").attr("onClick","javascript:loadpage('"+index_page+"');");
    $.ajax({
     url:'/',
     dataType:'json',
     data:{},
     cache:false, 
     ifModified :true,
     success:function(response){
         //操作
     },
     async:false
     });
}