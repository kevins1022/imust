<?php

/***
 *	copyright	:	Copyright (c) 2007-2013 SchoolCMS Inc. (http://www.schoolcms.cn/)
 *	license		:	http://www.schoolcms.cn/
 *	ContactQQ	:	1655098383
 *	E-mail		:	386392432@qq.com
 *	您可以在完全遵守本许可协议的基础上，将本软件应用于非商业用途，而不必支付软件版权许可费用。
 ***/
 
class IndexAction extends CommonAction
{
    public function Index()
	{
		if(true == empty($_REQUEST['action'])) {
			/* 验证是否已经登录 */
			if(!$_SESSION['schoolcms_admin']['aname']) Header('Location:'.__ROOT__.'/admin.php?action=login');

			/* 页面初始化数据 */
			$this->assign('SITE_ROOT',SITE_PATH);  //定义项目所在绝对地址
			$this->assign('admin', $_SESSION['schoolcms_admin']['aname']);  //当前管理员
			$this->assign('schoolcmsdate', date('Y', time()));
			$this->display('Index/index');
		} else {
			/* action 参数调用方法 */
			$this->AdminActionInfo();
		}
    }
	
	/*
		action 参数调用方法
	*/
	private function AdminActionInfo()
	{
		switch($_REQUEST["action"]) {
			/* 管理员登录 */
			case 'login';
				$this->Login();
			break;
				
			/* 管理员退出 */
			case 'logout';
				$this->Logout();
			break;
				
			/* 管理员登录验证 */
			case 'loginverification';
				$this->LoginVerification();
			break;

			default:
			/* 没有这个参数的时候，默认跳转到首页 */
			Header('Location:'.__ROOT__.'/admin.php');
		}
	}
	
	public function Welcome()
	{

		$xh=$_SESSION['schoolcms_admin']['aname'];

		$info=M("XsXjb")->where("xh='$xh'")->find();
		$this->info=$info;
		$this->display('Index/welcome');
	}
	
	
	
	
}
?>