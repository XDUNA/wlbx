// JavaScript Document
var index_page = 'search';
var search_page = 'search';
var search_result = 'timeline';
var repair_page = 'form1';
var cookie_error_page = 'cookie';
var error_404 = '404';
var error_500 = '500';
var self_check = 'selfcheck';
var faq_page = 'faq';
var more_page = 'more';
var report_page = 'report';

var error_list = [
    ['404',error_404],
    ['500',error_500],
    ['cookie',cookie_error_page]
];
var self_check_page_list = [
    ['1000','selfcheck_1000'],
    ['1001','selfcheck_1001'],
    ['1002','selfcheck_1002'],
    ['1003','selfcheck_1003'],
    ['1004','selfcheck_1004'],
    ['1005','selfcheck_1005'],
    ['1006','selfcheck_1006'],
    ['1007','selfcheck_1007']
];
var qiangpiao_list = [
    ['介四相声社丁酉年封箱专场','form2']
];
var page_list = [
    ['首页',index_page,0],
    ['查询',search_page,0],
    ['报修',repair_page,0],
    ['自检',self_check,1,self_check_page_list],
    ['错误',index_page,1,error_list],
    ['FAQ',faq_page,0],
    ['更多',more_page,0],
    ['抢票',index_page,1,qiangpiao_list],
    ['报告',report_page,0]
];
//页面名字,页面指向,是否有二级页面,二级页面列表