<?php
/*
*
*系统全局配置
*
*/
		return array(
		//'配置项'=>'配置值'
	    /* 数据库配置 */
	    'DB_TYPE'   => 'mysql', // 数据库类型
	   'DB_HOST'   => '127.0.0.1', //数据库地址
	    'DB_NAME'   => 'appli_cation', // 数据库名
	    'DB_USER'   => 'root', // 用户名
	    'DB_PWD'    => 'root',  // 密码
	    'DB_PORT'   => '3306', // 端口

    /* 错误页面模板 */
    'TMPL_ACTION_ERROR'     =>  'Public:error', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  'Public:success', // 默认成功跳转对应的模板文件
    'TMPL_EXCEPTION_FILE'   =>  'Public:exception',// 异常页面的模板文件


);