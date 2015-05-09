<?php

/**
 *	开发团队: http://www.weiletao.com	
 *	官方网址: http://www.phpjuxing.com
 *	官方Q Q :1655098383
 *
 *	本PHP程序是演示程序，建议不要直接在实际项目中使用。
 *	如果您确定直接使用本程序，使用之前请仔细完成安全服务器配置。
 *	本程序可以二次开发及二次销售。
 *
 */

//'配置项'=>'配置值'
return array(
	'SHOW_PAGE_TRACE'		=>		true,  //是否开启调试模式（项目上线关闭）
	'SESSION_AUTO_START'	=>		true,  //是否开启SESSION
	'TMPL_L_DELIM'			=>		'<!--{',  //模板左定界符
	'TMPL_R_DELIM'			=>		'}-->',  //模板右定界符
	'APP_GROUP_LIST'		=>		'Index,Admin',  //项目分组设定
	'DEFAULT_GROUP'			=>		'Admin',  //默认分组
	'DB_TYPE'				=>		'Oracle',  //数据库类型
	
	'DB_HOST'				=>		'10.129.5.192',  //数据库连接地址
	'DB_USER'				=>		'wwwckbk',  //数据库管理用户名称
	'DB_PWD'				=>		'jwcckbk',  //数据库管理用户密码
	'DB_PREFIX'				=>		'',  //数据表前缀

	//'DB_NAME'				=>		'(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=10.129.5.192)(PORT=1521))(CONNECT_DATA=(SID=orcl)))',  //数据库名称  //education
	"DB_NAME"               =>      "10.129.5.192/orcl",
	'DB_PORT'				=>		'1521',  //数据库端口
	 // 'DB_SEQUENCE_PREFIX' =>    'seq_',//序列名前缀
 	// 	'DB_TRIGGER_PREFIX'    =>    'tig_',//触发器名前缀
 
 
);