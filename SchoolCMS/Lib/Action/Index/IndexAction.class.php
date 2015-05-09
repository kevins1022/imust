<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){

	$user=M("Zzyb")->select();
	var_dump($user);
	echo "sss";
	die();

		// $str = '前台首页';
		// $this->assign('strindex', $str);
		// $this->display('Index/index');
    }
}