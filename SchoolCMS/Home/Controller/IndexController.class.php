<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	echo 1;


        $user=M('Zzyb')->select();
        //die();
		var_dump($user);
		echo "sss";
    }
}