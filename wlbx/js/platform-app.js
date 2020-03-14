// JavaScript Document
var nowpage = '';
function loadscript(scriptfamily,scriptname,callback){								//从服务器动态加载函数脚本 依据脚本的分类以及脚本的名称
	'use strict';
	$.post("interface.php",{													//连接服务器脚本管理器
		"scriptfamily":scriptfamily,												//脚本分类
		"scriptname":scriptname,														//脚本名称
        "require":"scriptmanager"
	},function(data){
		if(data.error === 0){
			$("#scriptloadplace").html(function(index,oldcontent){
				//$("#scriptloadplace").empty();
				//$("#scriptloadplace").html(oldcontent+'<script type="text/javascript">'+decode64(data.content)+'</script>');
				$("#scriptloadplace").html('<script type="text/javascript">'+decode64(data.content)+'</script>');
			});
		}
		if($.isFunction(callback)){
			callback();
		}
	},'json');																	//直接执行
}

function executescript(scriptfamily,scriptname,callback){							//从服务器加载直接执行脚本 依据分类和名称
	'use strict';
	$.post("interface.php",{
		"scriptfamily":scriptfamily,
		"scriptname":scriptname,
        "require":"scriptexecuter"
	},function(data){
		if(data.error === 0){
			eval(decode64(data.content));	
		}
		if($.isFunction(callback)){
			callback();
		}
	},'json');																		//直接执行
}

function act_cookie(){
	'use strict';
	var c="jscookietest=valid";
	document.cookie=c;
	if(document.cookie.indexOf(c)==-1){
		loadpage(cookie_error_page);
	}
	else{
		$.post("interface.php",{
			"submit":"SET",
            "require":"actcookie"
		},null,null);
		$.post("interface.php",{
			"submit":"TEST",
            "require":"actcookie"
		},function(data){
			if(data.error === 1){
				loadpage(cookie_error_page);
			}
		},'json');
	}
}

function loadpage(pagename) {														//页面加载
	'use strict';
	$("#loadsign").slideDown(500);
	$.post("interface.php",{														//连接服务器页面加载器
			"pagename":pagename,
            "require":"pagemanager"
		},function(data){															//载入后回调
			if(data.error === 0){
                nowpage = pagename;
				$("#page_body").slideUp(500,function(){								//页面淡出动画动画结束后载入页面
					$("#page_body").empty();
					$("#page_body").append(decode64(data.content));
					loadscript("pagecontent",pagename,function(){
						executescript("pagecontent",pagename,function(){
							$("#loadsign").slideUp(500);
							$("#page_body").slideDown(500,function(){				//载入后页面淡入
								loadscript("pagefunction",pagename,function(){
									executescript("pagefunction",pagename);			//执行载入后函数
                                    urlgenerate();
								});													//加载载入后函数
							});
						});															//执行初始化函数
					});																//加载初始化函数
				});
			}
			else if(data.error === 1){
				eval(decode64(data.content));
			}
		},'json');
}

function reloadpage(){
    'use strict';
    loadpage(nowpage);
}

var url_keyword_page = '页面';
var url_keyword_search = '搜索';
var url_self_check = '自检';
function urlprocess() {
    'use strict';
    var url = window.location.href;
    var page = url.indexOf(encodeURI(url_keyword_page+":"));
    if((page = url.indexOf(encodeURI(url_keyword_page+":"))) !== -1){
        var start = url.indexOf(encodeURI(":"),page);
        if(start !== -1){
            var start_name;
            for(var i=0;i<page_list.length;i++){
                start_name = url.indexOf(encodeURI(page_list[i][0]),page);
                if(start_name !== -1){
                    if(page_list[i][2] === 0){
                        loadpage(page_list[i][1]);
                        return false;
                    }
                    else{
                        var second = url.indexOf(encodeURI("."),start_name);
                        if(second !== -1){
                            var second_name;
                            for(var j=0;j<page_list[i][3].length;j++){
                                second_name = url.indexOf(encodeURI(page_list[i][3][j][0]),second);
                                if(second_name !== -1){
                                    loadpage(page_list[i][3][j][1]);
                                    return false;
                                }
                            }
                        }
                        else{
                            loadpage(page_list[i][1]);
                            return false;
                        }
                    }
                }
            }
        }
        loadpage(index_page);
    }
    else if((page = url.indexOf(url_keyword_page+":")) !== -1){
        var start = url.indexOf(":",page);
        if(start !== -1){
            var start_name;
            for(var i=0;i<page_list.length;i++){
                start_name = url.indexOf(page_list[i][0],page);
                if(start_name !== -1){
                    if(page_list[i][2] === 0){
                        loadpage(page_list[i][1]);
                        return false;
                    }
                    else{
                        var second = url.indexOf(".",start_name);
                        if(second !== -1){
                            var second_name;
                            for(var j=0;j<page_list[i][3].length;j++){
                                second_name = url.indexOf(page_list[i][3][j][0],second);
                                if(second_name !== -1){
                                    loadpage(page_list[i][3][j][1]);
                                    return false;
                                }
                                else{
                                }
                            }
                        }
                        else{
                            loadpage(page_list[i][1]);
                            return false;
                        }
                    }
                }
            }
        }
        loadpage(index_page);
    }
    else if((page = url.indexOf(encodeURI(url_keyword_search+"."))) !== -1){
        var start = url.indexOf(encodeURI("."),page);
        if(start !== -1){
            loadscript("pagecontent",search_page,function(){
                search_id(url.substr(start + 1,4));
            });
            return false;
        }
        else{
            loadpage(index_page);
        }
    }
    else if((page = url.indexOf(url_keyword_search+":")) !== -1){
        var start = url.indexOf(":",page);
        if(start !== -1){
            loadscript("pagecontent",search_page,function(){
                search_id(url.substr(start + 1,4));
            });
            return false;
        }
        else{
            loadpage(index_page);
        }
    }
    else{
        loadpage(index_page);
    }
}

function urlgenerate() {
    'use strict';
    for(var i=0;i<page_list.length;i++){
        if(nowpage === page_list[i][1] && page_list[i][2] === 0){
            window.history.pushState({},0,'/?'+url_keyword_page+':'+page_list[i][0]);
            return true;
        }
        else if(page_list[i][2] === 1){
            for(var j=0;j<page_list[i][3].length;j++){
                if(nowpage === page_list[i][3][j][1]){
                    window.history.pushState({},0,'/?'+url_keyword_page+':'+page_list[i][0]+'.'+page_list[i][3][j][0]);
                    return true;
                }
            }
        }
    }
    if(nowpage === search_result){
        window.history.pushState({},0,'/?'+url_keyword_search+'.'+repair_id);
        return true;
    }
    window.history.pushState({},0,'/');
}
