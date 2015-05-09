<?php

/***
 *	copyright	:	Copyright (c) 2007-2013 SchoolCMS Inc. (http://www.schoolcms.cn/)
 *	license		:	http://www.schoolcms.cn/
 *	ContactQQ	:	1655098383
 *	E-mail		:	386392432@qq.com
 *	您可以在完全遵守本许可协议的基础上，将本软件应用于非商业用途，而不必支付软件版权许可费用。
 ***/

class CommonAction extends Action
{

	/*
		退出方法
	*/
	public function Logout()
	{
		unset($_SESSION['schoolcms_admin']);
		$this->SchoolCMSPrompt('退出成功！', __ROOT__.'/admin.php?action=login', '再次登录');
	}
	
	/*
		判断是否已经登录
	*/
	private function LoginSession()
	{
		// echo "登陆成功！";
		// die();
		if((false == empty($_SESSION['schoolcms_admin']['aname'])) && (false == empty($_SESSION['schoolcms_admin']['apwd']))) {
			$this->SchoolCMSPrompt('已经登录！', __ROOT__.'/admin.php','进入管理首页');
		}
	}
	
	/*
		登录页面
	*/
	// public function logina(){
	// 	echo 1;

	// }
	public function Login() {
		/* 判断是否已经登录 */
		//$this->LoginSession();
		//echo "ssss";
		
		$FullName = array(
			'textname' => '用户名：',
			'name' => 'aname',
			'prompt' => '输入用户名',
			'prompttype' => 1,
			'lengthsmall' => 3,
			'lengthlarge' => 12,
			'required' => 1,
			'mousedown' => '请输入3至8位之间的字符！',
			'width' => '150px',
		);
		$FullNameObj = new InputTextModel($FullName);
		$this->assign('aname', $FullNameObj->GetInputTextHtmlInfo());
		
		$FullName = array(
			'textname' => '密　码：',
			'name' => 'apwd',
			'prompt' => '输入用户密码',
			'prompttype' => 1,
			'lengthsmall' => 2,
			'lengthlarge' => 12,
			'required' => 1,
			'mousedown' => '请输入2至12位之间的字符！',
			'type' => 'password',
			'width' => '150px',
		);
		$FullNameObj = new InputTextModel($FullName);
		$this->assign('apwd', $FullNameObj->GetInputTextHtmlInfo());
		
		$FullName = array(
			'textname' => '验证码：',
			'name' => 'verify',
			'prompt' => '输入验证码',
			'prompttype' => 1,
			'lengthsmall' => 6,
			'lengthlarge' => 6,
			'required' => 1,
			'mousedown' => '请输入6位字符！',
			'width' => '72px',
		);
		$FullNameObj = new InputTextModel($FullName);
		$this->assign('verify', $FullNameObj->GetInputTextHtmlInfo());
		$this->assign('schoolcmsdate', date('Y', time()));
		
		$this->display('Admin/login');
	}
	
	/*
		管理员登录验证
	*/
	public function LoginVerification() {
		/* 判断是否已经登录 */
		//$this->LoginSession();
		// var_dump($_POST);
		// die();
		
		$adminnamestatu = false;
		$passwordstatu = false;
		$verifystatu = false;
			
		/* 验证管理员名称是否为空 */
		if(true == empty($_POST['aname'])) {
			$this->SchoolCMSPrompt('管理员名不能为空！', __ROOT__.'/admin.php?action=login');
		}
				
		/* 验证管理员密码是否为空 */
		if(true == empty($_POST['apwd'])) {
			$this->SchoolCMSPrompt('管理密码不能为空！', __ROOT__.'/admin.php?action=login');
		}
				
		/* 验证 验证码是否为空 */
		if(true == empty($_POST['verify'])) {
			$this->SchoolCMSPrompt('验证码不能为空！', __ROOT__.'/admin.php?action=login');
		}
				
		/* 链接管理员表 */
		$Admin = M('SysYhmmb');
		//var_dump($Admin);

		//$Condition = array('name' => trim($_POST['aname']));
		//dump($Condition);
		$name=trim($_POST['aname']);
			
		/* 把查询条件传入查询方法 */
		$AdminData = $Admin->where("name='{$name}'")->find(); 
		//dump($AdminData);

		

			
		/* 判断返回是否有数据 */
		if(empty($AdminData)) {
			$this->SchoolCMSPrompt('管理员名不存在！', __ROOT__.'/admin.php?action=login');
			die();
		}
				
		/* 下面判断是否等于 */
		if($_POST['aname'] == $AdminData['NAME']) {
			$adminnamestatu = true;
		} else {
			$this->SchoolCMSPrompt('管理员名不存在！', __ROOT__.'/admin.php?action=login');
			die();

		}
				
		if(trim($_POST['apwd']) == $AdminData['PASSWORD']) {
				$passwordstatu = true;
		} else {
			$this->SchoolCMSPrompt('管理密码错误！', __ROOT__.'/admin.php?action=login');
			die();
		}
		// var_dump($_POST);
		// die();
			
				
		$Str = strtolower($_POST['verify']);
		if($Str == $_SESSION['verify']) {
			$verifystatu = true;
		} else {
			$this->SchoolCMSPrompt('验证码错误！', __ROOT__.'/admin.php?action=login');
		}
		//$verifystatu = true;
				
		/* 验证管理员是否正确 */
		if((true == $adminnamestatu) && (true == $passwordstatu) && (true == $verifystatu)) {
			/* 验证成功种SESSION */
			//$_SESSION['schoolcms_admin']['aid'] = $AdminData[0]['aid'];
			$_SESSION['schoolcms_admin']['aname'] = $AdminData['NAME'];
			$_SESSION['schoolcms_admin']['apwd'] = $AdminData['PASSWORD'];

					
			/* 验证成功，进入管理首页 */
			$this->SchoolCMSPrompt('登录成功！', __ROOT__.'/admin.php','进入管理首页');
		}
	}
	
	
	/*
		验证码生成
	*/
	public function Verify() {
		$img = imagecreatetruecolor(63, 22); //创建一个画布（真色彩）
		//$bgcolor = imagecolorallocate($img, 120, 200, 0); //用调色板调用一个颜色
		//imagefill($img, 0, 0, $bgcolor); //填充颜色
		
		//加入干扰，画出多条线
		for($i=0; $i<5; $i++) {
		$bgcolor=imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));  //产生随机的颜色
		imageline($img, rand(10,90), 0, rand(10,90), 20, $bgcolor);
		}
		
		//加入干扰，画出点    
		for($i=0; $i<200; $i++){ 
			$bgcolor = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));  //产生随机的颜色
			imagesetpixel($img, rand()%90, rand()%30, $bgcolor); 
		}
		
		//这部分是处理随机生成验证码数字
		$origstr = '3456789abxdefghijkmnprstuvwxy';
		$verifystring = '';
		$len = strlen($origstr);
		for($i=0; $i<6; $i++) {
			$index = mt_rand(0, $len-1);
			$char = $origstr[$index];
			$verifystring .= $char;
			$str = strtoupper($verifystring);
		}

		//将上面生成好的字符串写入图像
		$fgcolor = imagecolorallocate($img, 255, 255, 255);
		imagestring($img, 5, 5, 4, $str, $fgcolor);

		//输出图像
		header('Content-Type: image/gif');
		imagegif($img);

		//销毁图像
		imagedestroy($img);

		//验证码调用
		$strverify = strtolower($str);
		session_start();
		$_SESSION['verify'] = $strverify;
	}
	
	/*
		Ajax输出验证码
	*/
	public function GetVerify() {
		if(!empty($_POST['verify'])) {
			$Str = strtolower($_POST['verify']);
			if($Str == $_SESSION['verify']) {
				echo 1;
			} else {
				echo 0;
			}
		}
	}
	
	/*
		提示跳转页面
		$Prompt	：	提示信息（必传参数）
		$Url	：	url地址
		$Text	：	返回上一页按钮名称
		$Texts	：	附加按钮名称
		$Urls	：	附加按钮url地址
		$date	：	跳转的时间、默认3秒钟
	*/
	public function SchoolCMSPrompt($Prompt, $Url = null, $Text = null, $Texts = null, $Urls = null, $date = null)
	{
		/* 判断提示原因和url是否有值 */
		if(false == empty($Prompt)) {
			/* 如果时间为空，就使用默认3秒 */
			if(true == empty($date)) $date = 3;
			if(true == empty($Text)) $Text = '返回上一页';
			if(true == empty($Url)) $Url = $_SERVER['HTTP_REFERER'];

			/* 自动跳转和返回上一页代码 */
			$Html = '<meta http-equiv="refresh" content="'.$date.';url='.$Url.'"><p class="schoolcmsprompt">'.$Prompt.'</p><a href="'.$Url.'" class="adminsubmit">'.$Text.'</a>';
			
			/* 判断第二项是否有值 */
			if(false == empty($Texts) && false == empty($Urls)) {
				$Html .= '<a href="'.$Urls.'" class="adminsubmit schoolcmspromptright">'.$Texts.'</a>';
			}

			/* 解析模板引擎 */
			$this->assign('schoolcmsprompthtml', $Html);
			$this->assign('schoolcmspromptdate', $date);
			$this->display('Admin/schoolcmsprompt');
			exit();
		} else {
			exit('传参错误！');
		}
	}
	
	/*
		sql语句拼接处理
	*/
	public function GetSqlSplice($ArrayData = array(), $Type = null)
	{
		$DataFileArray = array(
			'sid' => 'student',
			'sname' => 'student',
			'sbirthdate' => 'student',
			'ssex' => 'student',
			'ssreatedate' => 'student',
			'stuitionstatu' => 'student',
			'seffectivetime' => 'student',
			'stheendtime' => 'student',
			'smobile' => 'student',
			'shomephone' => 'student',
			
			'atid' => 'achievement',
			'atfraction' => 'achievement',
			'atstatu' => 'achievement',
			'atscoretype' => 'achievement',
			
			'atcid' => 'achievementclass',
			'atcname' => 'achievementclass',
			'atcstatu' => 'achievementclass',
			'atcsort' => 'achievementclass',
			
			'cid' => 'class',
			'cname' => 'class',
			'csort' => 'class',
			'cstatu' => 'class',
			
			'cmid' => 'curriculum',
			'cmname' => 'curriculum',
			'cmstatu' => 'curriculum',
			'cmsort' => 'curriculum',
			
			'ptid' => 'payment',
			'ptmoney' => 'payment',
			'ptremarks' => 'payment',
			'ptupdate' => 'payment',
			'ptdate' => 'payment',
			
			'srid' => 'semester',
			'srname' => 'semester',
			'srsort' => 'semester',
			'srstatu' => 'semester',
			
			'cyid' => 'studentclassify',
			'cypid' => 'studentclassify',
			'cyname' => 'studentclassify',
			'cystatu' => 'studentclassify',
			'cysort' => 'studentclassify',
			
			'ttid' => 'tcwct',
			
			'tid' => 'teacher',
			'tname' => 'teacher',
			'tbirthdate' => 'teacher',
			'tmobile' => 'teacher',
			'thomephone' => 'teacher',
			'tmail' => 'teacher',
			'tsex' => 'teacher',
			'tsreatedate' => 'teacher',
			
			'teid' => 'time',
			'tename' => 'time',
			'tesort' => 'time',
			'testatu' => 'time',
			
			'wid' => 'week',
			'wname' => 'week',
			'wsort' => 'week',
			'wstatu' => 'week',
		);
		
		$FileArray = array(
			'like' => 'like',
			'eq' => '=',
			'neq' => '<>',
			'gt' => '>',
			'egt' => '>=',
			'lt' => '<',
			'elt' => '<=',
		);
		
		$Sql = null;
		$PREFIX = C('DB_PREFIX');
		if(false == empty($ArrayData)) {
			foreach($ArrayData as $FileKey=>$FileValue) {
				if(true == isset($FileKey) && true == isset($FileValue)) {
					if(true == is_array($FileValue)) {
						foreach($FileValue as $FileKeys=>$FileValues) {
							if(true == is_array($FileValues)) {
								foreach($FileValues as $FileKeyss=>$FileValuess) {
									if(true == isset($FileValuess) && false == is_array($FileValuess)) {
										if(true == array_key_exists($FileKey, $DataFileArray)) {
											if(true == array_key_exists($FileValuess, $FileArray)) {
												$Sql .= '(`'.$PREFIX.$DataFileArray[$FileKey].'`.`'.$FileKey.'` '.$FileArray[$FileValuess].' ';
											} else {
												$Sql .= '\''.$FileValuess.'\') and ';
											}
										}
									}
								}
							} else {
								if(true == isset($FileValues)) {
									if(true == array_key_exists($FileKey, $DataFileArray)) {
										if(true == array_key_exists($FileValues, $FileArray)) {
											$Sql .= '(`'.$PREFIX.$DataFileArray[$FileKey].'`.`'.$FileKey.'` '.$FileArray[$FileValues].' ';
										} else {
											$Sql .= '\''.$FileValues.'\') and ';
										}
									}
								}
							}
						}
					} else {
						if(true == array_key_exists($FileKey, $DataFileArray)) {
							$Sql .= '(`'.$PREFIX.$DataFileArray[$FileKey].'`.`'.$FileKey.'` = \''.$FileValue.'\') and ';
						}
					}
				}
			}
		}
		if($Type == 1) {
			return substr($Sql, 0, -5);
		} else {
			return $Sql;
		}
	}
	
	/*
		判断表是否存在，如果存在则直接 return true，不存在返回 false
	*/
	public function TableName($TableName)
	{
		$Statu = false;
		if(false == empty($TableName)) {
			$TableNameObj = M("$TableName");
			$TableNameArray = $TableNameObj->query('show TABLES');
			foreach($TableNameArray as $TableKey=>$TableValue) {
				foreach($TableValue as $TableKeys=>$TableValues) {
					if($TableValues == $TableName) {
						return true;
					} else {
						$Statu = false;
					}
				}
			}
		}
		return $Statu;
	}

	
}
?>