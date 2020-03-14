// JavaScript Document
function init_ecookie(){
	'use strict';
	$("#content").append(ecookie_content);
	$("#back").append(ecookie_back);
	$("#back").attr("onClick","javascript:loadpage('"+index_page+"');");
}