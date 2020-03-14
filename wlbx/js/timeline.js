//
//ID discribe class head href hrefcontent paragraphnum paragraph1 paragraphinsertname1 paragraph2 paragraphinsert2...
/*
status:
0:submitsuccess
1:infconfirm
2:inferror
3:scheduled
4:rescheduling
5:rescheduled
6:repairsuccess
7:repairdely
8:repairfail
100:closed
*/

/*
status:
0:submitsuccess
10:infconfirm
11:inferror
12:usercancel
20:scheduled
30:rescheduling
31:rescheduled
40:repairsuccess
50:repairdely
55:repairfail
100:closed
*/
var timelinemap = [
	['0', 'submitsuccess', 'success', timeline_h_0, 'timecancel();', timeline_p_17, 1, timeline_p_1, 'NULL'],
	['10', 'infconfirm', 'success', timeline_h_1, 'timecancel();', timeline_p_17, 1, timeline_p_2, 'NULL'],
	['11', 'inferror', 'fail', timeline_h_2, 'NULL', 'NULL', 1, timeline_p_3, 'remark'],
    ['12', 'usercancel', 'success', timeline_h_9, 'NULL', 'NULL', 1, timeline_p_18, 'NULL'],
	['20', 'scheduled', 'success', timeline_h_3,'timereschedule();', timeline_p_4, 2, timeline_p_5, 'exptime', timeline_p_6, 'NULL'],
	['30', 'rescheduling', 'success', timeline_h_4, 'NULL', 'NULL', 1, timeline_p_7, 'NULL'],
	['31', 'rescheduled', 'success', timeline_h_5, 'timereschedule();', timeline_p_8, 2, timeline_p_9, 'exptime', timeline_p_10, 'NULL'],
	['40', 'repairsuccess', 'success', timeline_h_6, 'NULL', 'NULL', 1, timeline_p_11, 'NULL'],
	['50', 'repairdely', 'warning', timeline_h_7, 'timereschedule();', timeline_p_12, 2, timeline_p_13, 'remark', timeline_p_14, 'NULL'],
	['55', 'repairfail', 'fail', timeline_h_8, 'NULL', 'NULL', 2, timeline_p_15, 'remark', timeline_p_16, 'NULL']
];

var repair_id;

function init_timeline(){
	'use strict';
	$("#dividebar_p1").append(timeline_dividebar_p1);
	$("#dividebar_p2").append(timeline_dividebar_p2);
	$("#timeline_footer").append(timeline_timeline_footer);
	$("#btn_back_index").append(timeline_btn_back_index);
	//f233;
}

function gettimeline(){
	'use strict';
	$.post("interface.php",{
		"query":"query",
        "require":"timeline"
	},function(data){
		if(data.error === 0){
			$("#dividebar_p2").append(data.ID);
            repair_id = data.ID;
			for(var i=0;i<data.datasize;i++){
				if(data[i].status !== 100){
					for(var j=0;j<timelinemap.length;j++){
						if(data[i].status === timelinemap[j][0]){
							var para = "";
							var href = "";
							for(var k = 0;k < timelinemap[j][6];k++){
								if(timelinemap[j][7+2*k] !== 'NULL'){
									para += timelinemap[j][7+2*k];
								}
								if(timelinemap[j][7+2*k+1] !== 'NULL'){
									para += decode64(data[i][timelinemap[j][7+2*k+1]]);
								}
							}
							if(i === 0 && timelinemap[j][4] !== 'NULL'){
								href = '<a style="cursor: pointer;" class="cd-read-more" target="_blank" onClick="'+timelinemap[j][4]+'" id="act_btn">'+timelinemap[j][5]+'</a>';
							}
							$("#timeline_pool").append('<div class="cd-timeline-block"><div class="cd-timeline-img cd-'+timelinemap[j][2]+'"></div><div class="cd-timeline-content"><h4><strong>'+timelinemap[j][3]+'</strong></h4><p>'+para+'</p>'+href+'<span class="cd-date">'+decode64(data[i].updatetime)+'</span></div></div>');
							break;
						}
					}
				}
			}
		}
	},'json');
}

function timereschedule(){
	'use strict';
	$.post("interface.php",{
		"query":"reschedule",
        "require":"timeline"
	},function(data){
		if(data.error === 0){
			$("#act_btn").hide();
			$("#timeline_pool").prepend('<div class="cd-timeline-block"><div class="cd-timeline-img cd-'+timelinemap[5][2]+'"></div><div class="cd-timeline-content"><h4><strong>'+timelinemap[5][3]+'</strong></h4><p>'+timelinemap[5][7]+'</p><span class="cd-date">'+timeline_justnow+'</span></div></div>');
		}
		else
		{
			var temp = $("#act_btn").html();
			$("#act_btn").clear();
			$("#act_btn").append(timeline_btn_1);
			setTimeout(function(){
				$("#act_btn").clear();
				$("#act_btn").append(temp);
			},3000);
		}
	},'json');
}

function timecancel(){
	'use strict';
	$.post("interface.php",{
		"query":"cancel",
        "require":"timeline"
	},function(data){
		if(data.error === 0){
			$("#act_btn").hide();
			$("#timeline_pool").prepend('<div class="cd-timeline-block"><div class="cd-timeline-img cd-'+timelinemap[3][2]+'"></div><div class="cd-timeline-content"><h4><strong>'+timelinemap[3][3]+'</strong></h4><p>'+timelinemap[3][7]+'</p><span class="cd-date">'+timeline_justnow+'</span></div></div>');
		}
		else
		{
			var temp = $("#act_btn").html();
			$("#act_btn").clear();
			$("#act_btn").append(timeline_btn_1);
			setTimeout(function(){
				$("#act_btn").clear();
				$("#act_btn").append(temp);
			},3000);
		}
	},'json');
}
