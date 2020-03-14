// JavaScript Document
function init_e500(){
	'use strict';
	$("#content").append(e500_content);
	$("#back").append(e500_back);
	$("#back").attr("onClick","javascript:loadpage('"+index_page+"');");
}