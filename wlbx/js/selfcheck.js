// JavaScript Document

var self_check_choose = [
    [0,1,'有线网络','黄色叹号','右下角网络连接标识上有黄色叹号'],
    [0,2,'有线网络','红色叉号','右下角网络连接标识上有红色叉号'],
    [0,3,'无线网络','无法登陆','连接上无线网无法弹出登陆界面'],
    [1,4,'一直存在','黄色叹号一直存在','黄色叹号从开学到目前一直有'],
    [1,5,'突然出现','黄色叹号突然出现','在前段时间的使用过程中突然出现黄色叹号'],
    [2,1000,'更换电脑','更换同学电脑尝试上网','更换同学电脑后可以上网'],
    [2,1001,'更换电脑','更换同学电脑尝试上网','更换同学电脑后仍然不能上网'],
    [3,1002,'手动打开','手动打开登录界面','手动在浏览器输入<a href="http://10.255.44.33">10.255.44.33</a>后仍然无法打开登录页面'],
    [4,1003,'端口能用','室友能用电脑在这里上网','室友用他的电脑在这里可以上网'],
    [4,6,'端口不能用','室友不能用电脑在这里上网','室友也无法在这个端口登录'],
    [5,7,'插紧网线','将网线两端插紧','自己使用的网线的两端分别插紧后依然不能上网'],
    [6,1004,'物理损坏','网络端口处有物理损坏','网络端口处有较为明显的物理损坏导致可能发生接触不良等故障'],
    [6,1005,'物理未损坏','网络端口处没有物理损坏','尝试更换自己的网线后仍然无法上网'],
    [7,1006,'缺流量','查询流量','登录<a href="http://zfw.xidian.edu.cn">zfw.xidian.edu.cn</a>后发现流量已经用完'],
    [7,1007,'不缺流量','查询流量','查询后发现流量还有剩余']
];
function init_selfcheck(){
    'use strict';
    $("#btnpool").append('<button type="button" class="btn btn-primary btn-lg btn-block" onClick="javascript:self_check_back();" id="self_check_back_btn">'+self_check_btn1+'</button>');
	$("#btnpool").append('<button type="button" class="btn btn-primary btn-lg btn-block" onClick="javascript:loadpage(index_page);">'+self_check_btn2+'</button>');
    self_check_load(0);
}
var self_check_history = [];
function self_check_load(id){
    'use strict';
    self_check_history.push(id);
    self_check_dispaly(id);
}

function self_check_back(){
    'use strict';
    if(self_check_history.length > 1){
        self_check_dispaly(self_check_history[self_check_history.length - 2]);
        self_check_history.pop();
    }
    else{
        $("#self_check_back_btn").empty();
        $("#self_check_back_btn").append(self_check_btn4);
        setTimeout(function (){
            $("#self_check_back_btn").empty();
            $("#self_check_back_btn").append(self_check_btn1);
        },1000);
    }
}

function self_check_dispaly(id){
    'use strict';
    if(id < 1000){
        $("#self_check").empty();
        var num = 1;
        for(var i=0;i<self_check_choose.length;i++){
            if(self_check_choose[i][0] === id){
                $("#self_check").append('<div class="col-sm-6 col-md-3 PlanPricing" id="self_check_'+num+'"><div class="planName"><span class="price">#'+num+'</span><h3>'+self_check_choose[i][2]+'</h3><p>'+self_check_choose[i][3]+'</p></div><div class="planFeatures"><ul></ul></div><p><a role="button" class="btn btn-primary btn-lg" onClick="self_check_load('+self_check_choose[i][1]+')">'+self_check_btn3+'</a></p></div>');
                for(var j=4;j<self_check_choose[i].length;j++){
                    $("#self_check_"+num+" ul").append('<li>'+self_check_choose[i][j]+'</li>');
                }
                num++;
            }
        }
        $("#self_check").append('<div class="col-sm-6 col-md-3 PlanPricing"><div class="planName"><span class="price">!</span><h3>'+self_check_direct1+'</h3><p>'+self_check_direct2+'</p></div><div class="planFeatures"><ul><li>'+self_check_direct3+'</li></ul></div><p><a role="button" class="btn btn-primary btn-lg" onClick="loadpage(repair_page)">'+self_check_direct4+'</a></p></div>');
    }
    else{
        for(var i=0;i<self_check_page_list.length;i++){
            if(id.toString() === self_check_page_list[i][0]){
                loadpage(self_check_page_list[i][1]);
            }
        }
    }
}