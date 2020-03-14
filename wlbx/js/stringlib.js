//字符表
////////////////////////////////////////////////////////////
//主页页眉字符表
var index_page_title = '新校区学生校园网络报修';
var index_main_title = '西安电子科技大学';
var index_keywords = '西安电子科技大学宿舍区网络报修平台';
var index_sub_title = '新校区学生校园网络报修';
var index_sidebar_title = '校园网络故障一站式服务平台';
var index_sidebar_content = '西安电子科技大学学生网管会竭诚为居于新校区宿舍区的您提供快捷方便的网络服务，同时在您的网络出现异常时在第一时间提供细致周到的维修信息。数位资深网络维护人员将尽己所能保障您的网络体验。同时烦请您在遇到问题时耐心地配合，帮助我们更快的排查问题所在。最后如果您有什么宝贵的意见以及建议，望您能及时与我们联系。';
var index_dividebar_p1 = '希望您如实填写下列表格';
var index_dividebar_p2 = '以便我们尽快联系到您';
var page_timeout = '页面加载超时，请刷新！';
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
//主页页脚字符表
var footer_title_broad = '最新通告';
var footer_footer_menu_title = '相关链接';
var footer_footer_another_title = '其他服务';
var footer_address_title = '联系我们';
var copyrights = '版权所有 © 2017.信息化建设处学生网管会. Powered by <a href="https://www.tiferking.cn">Tifer King</a>';
var footer_footer_menu = [
['http://www.xidian.edu.cn','西安电子科技大学'],
['http://xxc.xidian.edu.cn','信息化建设处'],
['http://zfw.xidian.edu.cn','校园网自助流量查询'],
['http://gis.xidian.edu.cn','校园地理信息系统']

];
var footer_footer_another = [
['http://stumail.xidian.edu.cn','学生邮箱申请'],
['http://noa.xidian.edu.cn','虚拟服务器申请'],
['/?页面:更多','意见及建议'],
['/?页面:更多','投诉与反馈']
];
var footer_address = [
['地址：','图书馆C区西北角信息化建设处'],
['电话:','029-81891357']
];
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
//form1页面字符表
var form1_form_title = '报修信息';
var form1_label_name = '姓名：';
var form1_label_stuid = '学号：';
var form1_label_tel = '电话：';
var form1_label_time = '空闲时间：<br>(请尽量多选)<br>舍友在宿舍的时间也行哦~';
var form1_label_time_list = [
	'周一晚7-9点',
	'周一晚9-11点',
	'周二晚7-9点',
	'周二晚9-11点',
	'周三晚7-9点',
	'周三晚9-11点',
	'周四晚7-9点',
	'周四晚9-11点',
	'周五晚7-9点',
	'周五晚9-11点'];
var form1_label_bulnum = '宿舍地址：';
var form1_label_detail = '问题描述：';
var form1_label_wechat = '绑定微信：';
var form1_wechatbindurl = '点此绑定微信';
var form1_label_repairid = '报修ID：';
var form1_warning1 = '请仔细核对信息以免延误维修给您带来不便！';
var form1_warning2 = '请填写所有必要的信息！并绑定微信！';
var form1_warning3 = '数据错误，请刷新页面！';
var form1_warning4 = '您已经报修过了，我们正在处理，请耐心等待哦~';
var form1_btn1 = '提交';
var form1_btn2 = '不，我填错了';
var form1_btn3 = '没问题，来修吧';
var form1_btn4 = '返回主页';
var form1_name_placeholder = '姓名';
var form1_stuid_placeholder = '学号';
var form1_tel_placeholder = '联系电话';
var form1_repairid_placeholder = '提交成功后在此处获得报修ID';
var form1_detail_placeholder = '网络故障大致情况,使用的方式(路由器或直连),拨号错误代码以及网络故障的表现等';
var form1_bulnum = '#楼';
var form1_floor = '层';
var form1_room = '宿舍';
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
//404页面字符表
var e404_content = '您要访问的页面不存在哦~<br>(如果问题持续出现，请清除浏览器缓存后再试)';
var e404_back = '点击我回到主页';
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
//500页面字符表
var e500_content = '服务器内部错误(>_<)';
var e500_back = '点击我回到主页';
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
//cookies页面字符表
var ecookie_content = '我们的页面需要cookies支持，希望您打开cookies功能之后继续。';
var ecookie_back = '点击我回到主页';
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
//search页面字符表
var search_search_main_header = '报修状态查询';
var search_search_sub_header = '输入报修ID以进行报修流程状态的查询';
//var search_searchbox_title = '报修ID:';
var search_searchbox = '请在此处填入报修ID';

var search_search_btn = '查询报修';
var go2service_btn = '还未报修?';
var search_search_btn_war1 = '请填入内容';
var search_search_btn_war2 = '请正确填写';
var search_search_btn_war3 = '没有相关信息';
var search_service_main_header = '从下面选择一个您需要的服务';
var search_service_sub_header = '希望能够尽快为您解决网络故障';
var search_qrcode = '<p>微信扫一扫直接查询</p>';
var search_fn_left = [
	[self_check,'s1','自我检查','通过我们为您提供的教程，一步一步排查简单的网络错误，以便通过您自己的力量快速恢复网络连接，不必因等待维修人员到达现场而浪费您宝贵的时间。'],
	[repair_page,'s2','进行报修','通过在线预约网络维护人员，在您空闲的时间到达现场对您的网络进行检查，为您解决网络故障，保证您的网络通畅。']
	//['manage','s3','管理员页','管理员页面']
];
var search_fn_right = [
	[faq_page,'s3','常见问题','我们总结的一些用户们各方面经常遇到的问题，并给出了详细的解答，您也可能遇到其中一个或者几个问题，仔细阅读一下问题列表，说不定您的问题会在这里得到解决。'],
    [more_page,'s6','更多功能','更多丰富的功能，敬请期待我们的工程师来开发和发布，当然您也可以点击这里向我们反馈一些您所期望的功能，帮助我们更好的了解用户的需求，让我们能更好的为您提供服务。']
];
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
//timeline页面字符表
var timeline_dividebar_p1 = '报修过程一览';
var timeline_dividebar_p2 = '您的报修ID：';
var timeline_timeline_footer = '感谢您使用西电网络报修系统';
var timeline_btn_back_index = '返回主页';

var timeline_h_0 = '成功提交报修表单';
var timeline_h_1 = '维护人员已确认报修信息';
var timeline_h_2 = '您的报修信息有误';
var timeline_h_3 = '维护人员已确定维修时间';
var timeline_h_4 = '重新预约中';
var timeline_h_5 = '重新预约时间成功';
var timeline_h_6 = '维护成功';
var timeline_h_7 = '维护出现了问题';
var timeline_h_8 = '维护失败了';
var timeline_h_9 = '用户撤回了报修';

var timeline_p_1 = '如果您没有绑定微信，请您牢记您的报修ID，或者将其记在您常用的记录工具上，后续的业务将十分依赖这个ID，当然凭借微信扫描二维码您可以代替这个ID。';
var timeline_p_2 = '维护人员已经确认您的报修信息，请您耐心等待维护人员安排维护时间。';
var timeline_p_3 = '您的报修信息未通过审核，具体原因是：';
var timeline_p_4 = '我那个时候有事不在';
var timeline_p_5 = '维护人员计划在';
var timeline_p_6 = '到现场为您进行网络维护，请您届时保证宿舍内至少有一个人可以引导维修人员找到您的确切位置，包括给维修人员开门。谢谢您的合作。';
var timeline_p_7 = '您临时有事的请求我们已经收到啦，请耐心等候，我们会及时重新安排维修人员上门维修的。';
var timeline_p_8 = '我还是有事啊';
var timeline_p_9 = '维护人员重新计划在';
var timeline_p_10 = '到现场为您进行网络维护，请您届时保证宿舍内至少有一个人可以引导维修人员找到您的确切位置，包括给维修人员开门。谢谢您的合作。';
var timeline_p_11 = '( ゜- ゜)つロ 乾杯~';
var timeline_p_12 = '重新预约一下吧';
var timeline_p_13 = '由于：';
var timeline_p_14 = '我们的维护人员没有成功的完成维护，还请您谅解，重新预约一个您方便的时间，维修人员再来给您维修吧。';
var timeline_p_15 = '由于：';
var timeline_p_16 = '我们的维护人员没有完成维护，您本次的报修只能不得已的被废弃了，还请您谅解。';
var timeline_p_17 = '撤回报修';
var timeline_p_18 = '您通过本平台自助撤回了报修，感谢您的使用。( ゜- ゜)つロ 乾杯~';
var timeline_justnow = '刚刚';
var timeline_btn_1 = '预约失败';
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
//selfcheck页面字符表
var self_check_btn1 = '返回前一步';
var self_check_btn2 = '返回首页';
var self_check_btn3 = '我碰到了这种问题';
var self_check_btn4 = '已经不能再前了';
var self_check_btn5 = '进入报修';
var self_check_direct1 = '我没有遇到以上任何问题';
var self_check_direct2 = '可是网络依然故障';
var self_check_direct3 = '点击下方按钮直接跳转到报修界面';
var self_check_direct4 = '直接报修';
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
//faq页面字符表
var faq_btn1 = '返回首页';
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
//more页面字符表
var more_form_title = '请写下您宝贵的意见及建议，或者您期待的新功能';
var more_label_name = '姓名或昵称：';
var more_label_stuid = '学号或工号：';
var more_label_tel = '联系电话：';
var more_label_detail = '意见或建议：';
var more_dividebar_p1 = '您宝贵的意见';
var more_dividebar_p2 = '是我们前进的动力';
var more_name_placeholder = '让我们知道如何称呼阁下(非必填)';
var more_stuid_placeholder = '我们将阁下加入抽奖等活动的依据(非必填)';
var more_tel_placeholder = '我们联系阁下的方式，如非电话请注明(非必填)';
var more_detail_placeholder = '阁下期待的新功能或者我们所存在的不足或者对我们改进的意见，烦请阁下详细写明。(必填)';
var more_btn1 = '提交';
var more_btn2 = '返回主页';
var more_btn3 = '请填写必要信息';
var more_btn4 = '提交成功';
var more_btn5 = '提交失败';
////////////////////////////////////////////////////////////

