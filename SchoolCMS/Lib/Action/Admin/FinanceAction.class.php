<?php

/***
 *	copyright	:	Copyright (c) 2007-2013 SchoolCMS Inc. (http://www.schoolcms.cn/)
 *	license		:	http://www.schoolcms.cn/
 *	ContactQQ	:	1655098383
 *	E-mail		:	386392432@qq.com
 *	您可以在完全遵守本许可协议的基础上，将本软件应用于非商业用途，而不必支付软件版权许可费用。
 ***/

if(!$_SESSION['schoolcms_admin']['aname']) Header('Location:'.__ROOT__.'/admin.php?action=login');

class FinanceAction extends CommonAction
{
	/*
		缴费管理
	*/
	public function PaymentManagement()
	{
		date_default_timezone_set('Asia/Shanghai');
		
		/* 表前缀名 */
		$PREFIX = C('DB_PREFIX');
		
		/* 判断当前表是否存在 */
		$TableStatu = $this->TableName($PREFIX.'view_payment_student');
		
		/* 实例化表对象 */
		$FinanceObj = M('view_payment_student');

		//调用初始化方法
		$this->FinanceInitializationInfo();

		//调用查询条件处理方法
		$ConditionArray = $this->FinanceInquiryCondition($_REQUEST);

		/* 没有视图的时候使用多表查询 */
		if(true == $TableStatu) {
			/* 按照条件获取数据的总条数 */
			$FinanceCount = count($FinanceObj->where($ConditionArray)->select());
		} else {
			$FinanceCountData = $FinanceObj->query("select count(".$PREFIX."payment.ptid) AS ptid from ((`".$PREFIX."student` join `".$PREFIX."payment` on((`".$PREFIX."payment`.`sid` = `".$PREFIX."student`.`sid`))) join `".$PREFIX."semester` on((`".$PREFIX."semester`.`srid` = `".$PREFIX."student`.`srid`))) where (".$this->GetSqlSplice($ConditionArray)."(`".$PREFIX."semester`.`srstatu` = 1))");
			$FinanceCount = $FinanceCountData[0]['ptid'];
		}
		
		/* 从基本设置表读取分页参数 */
		$BasicsetupObj = M('basicsetup');
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="financepage"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$FinancePage = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$FinancePage = 8;
		}

		$PageData = $BasicsetupObj->field('bdatavalue')->where('btype="modelpage"')->select();
		if(false == empty($PageData[0]['bdatavalue'])) {
			//$PageDataValue = intval($PageData[0]['bdatavalue']);
			if($PageData[0]['bdatavalue'] == 1) {
				$CustomPage = 0;
				$DetailData = 0;
			} else if($PageData[0]['bdatavalue'] == 2) {
				$CustomPage = 1;
				$DetailData = 0;
			} else if($PageData[0]['bdatavalue'] == 3) {
				$CustomPage = 1;
				$DetailData = 1;
			} else if($PageData[0]['bdatavalue'] == 4) {
				$CustomPage = 0;
				$DetailData = 1;
			}
		} else {
			$CustomPage = 1;
			$DetailData = 1;
		}
		/* 调用分页模块 */
		$Configuration = array(
			'total' => $FinanceCount,
			'traction' => $FinancePage,  //显示条数
			'custompage' => $CustomPage,  //开启自定义跳转页码
			'detaildata' => $DetailData,  //开启详情数据
			'pagecoent' => 3,  //分页显示的个数
			'url' => './PaymentManagement',
		);
		$PageingObj = new PagingModel($_REQUEST, $Configuration);
		$this->assign('pageing', $PageingObj->GetPagingHtmlInfo());
		
		
		
		/* 没有视图的时候使用多表查询 */
		if(true == $TableStatu) {
			//起始页码值和每页显示条数从分页模块里面获取
			$FinanceData = $FinanceObj->limit($PageingObj->GetStarting(), $PageingObj->GetFraction())->where($ConditionArray)->select();
		} else {
			$Limit = $PageingObj->GetStarting();
			$Limits = $PageingObj->GetFraction();
			$FinanceData = $FinanceObj->query("select `".$PREFIX."student`.`sname` AS `sname`,`".$PREFIX."payment`.`ptmoney` AS `ptmoney`,`".$PREFIX."payment`.`ptid` AS `ptid`,`".$PREFIX."payment`.`ptremarks` AS `ptremarks`,`".$PREFIX."payment`.`ptdate` AS `ptdate`,`".$PREFIX."payment`.`sid` AS `sid`,`".$PREFIX."payment`.`ptupdate` AS `ptupdate`,`".$PREFIX."semester`.`srid` AS `srid` from ((`".$PREFIX."student` join `".$PREFIX."payment` on((`".$PREFIX."payment`.`sid` = `".$PREFIX."student`.`sid`))) join `".$PREFIX."semester` on((`".$PREFIX."semester`.`srid` = `".$PREFIX."student`.`srid`))) where (".$this->GetSqlSplice($ConditionArray)."(`".$PREFIX."semester`.`srstatu` = 1)) limit $Limit, $Limits");
		}
		
		/* 调用网格和CSV模块 */
		$this->assign('gridtable', $this->GetGridInitializationInfo($FinanceData));
		$this->assign('subcsvexport', $this->GetCSVInitializationInfo());
		
		/* 获取总金额 */
		if(true == $TableStatu) {
			$this->assign('ptmoneysum', $FinanceObj->sum('ptmoney'));
		} else {
			$DataArray = $FinanceObj->query("select SUM(".$PREFIX."payment.ptmoney) AS ptmoney from ((`".$PREFIX."student` join `".$PREFIX."payment` on((`".$PREFIX."payment`.`sid` = `".$PREFIX."student`.`sid`))) join `".$PREFIX."semester` on((`".$PREFIX."semester`.`srid` = `".$PREFIX."student`.`srid`))) where (".$this->GetSqlSplice($ConditionArray)."(`".$PREFIX."semester`.`srstatu` = 1))");
			$SumData = false == empty($DataArray[0]['ptmoney']) ? $DataArray[0]['ptmoney'] : null;
			$this->assign('ptmoneysum', $SumData);
		}

		$this->display('Finance/paymentmanagement');
	}
	
	/*
		数据字段数组方法
	*/
	private function GetFieldArray()
	{
		//调用网格显示数据
		return $FieldArray = array(
			'sname' => '学员姓名',
			'ptmoney' => '缴费金额',
			'ptdate' => '缴费时间',
			'ptupdate' => '更新时间',
			'ptremarks' => '缴费备注',
		);
	}
	
	/*
		初始化CSV方法
	*/
	public function GetCSVInitializationInfo()
	{
		$CsvExportObj = new CSVModel($this->GetFieldArray(), 'subcsvexport', '财务数据', './GetCSVInitializationInfo');
		//判断是不是到处CSV
		if(false == empty($_REQUEST['type'])) {
			if($_REQUEST['type'] == 'csvexport') {
				/* 表前缀名 */
				$PREFIX = C('DB_PREFIX');
				
				/* 判断当前表是否存在 */
				$TableStatu = $this->TableName($PREFIX.'view_payment_student');
				
				/* 实例化表对象 */
				$FinanceObj = M('view_payment_student');

				/* 没有视图的时候使用多表查询 */
				if(true == $TableStatu) {
					$FinanceData = $FinanceObj->where('ptmoney>0')->select();
				} else {
					$PREFIX = C('DB_PREFIX');
					$FinanceData = $FinanceObj->query("select `".$PREFIX."student`.`sname` AS `sname`,`".$PREFIX."payment`.`ptmoney` AS `ptmoney`,`".$PREFIX."payment`.`ptid` AS `ptid`,`".$PREFIX."payment`.`ptremarks` AS `ptremarks`,`".$PREFIX."payment`.`ptdate` AS `ptdate`,`".$PREFIX."payment`.`sid` AS `sid`,`".$PREFIX."payment`.`ptupdate` AS `ptupdate`,`".$PREFIX."semester`.`srid` AS `srid` from ((`".$PREFIX."student` join `".$PREFIX."payment` on((`".$PREFIX."payment`.`sid` = `".$PREFIX."student`.`sid`))) join `".$PREFIX."semester` on((`".$PREFIX."semester`.`srid` = `".$PREFIX."student`.`srid`))) where ((`".$PREFIX."semester`.`srstatu` = 1) and (".$PREFIX."payment.ptmoney > 0))");
				}
				$CsvExportObj->CsvExportData($FinanceData);  //处理页面传递过来的数据
			}
		} else {
			return $CsvExportObj->GetTitltDataHtml();
		}
	}
	
	/*
		初始化网格方法
	*/
	private function GetGridInitializationInfo($FinanceData)
	{
		$Configuration = array('id' => 'ptid',);
		$Operating = array(
			array(
				'type' => 1,
				'name' => '编辑',
				'returntype' => 1,
				'url' => './FinanceEditorInfo',
			),
		);
		$GridObj = new GridModel($FinanceData, $this->GetFieldArray(), $Configuration, $Operating);
		return $GridObj->GetGridHtmlInfo();
	}
	
	/*
		财务信息查询条件处理方法
	*/
	private function FinanceInquiryCondition($Inquiry = array())
	{
		/* 设置当前时间地区 */
		date_default_timezone_set('Asia/Shanghai');
		/* 条件查询拼接 */
		$ConditionArray = array();
		$ConditionArrayDaate = array();
		if(false == empty($Inquiry)) {
			foreach($Inquiry as $Ckey=>$Cvalue) {
				if((false == empty($Cvalue)) && (false == is_array($Ckey))) {
					switch($Ckey) {
						//学生姓名
						case 'sname':
							$ConditionArray['sname'] = array('like','%'.$Cvalue.'%');
						break;
						//缴费时间
						case 'ptdate1':
							array_push($ConditionArrayDaate, array('egt', $Cvalue));
						break;
						//缴费时间
						case 'ptdate2':
							array_push($ConditionArrayDaate, array('elt', $Cvalue));
						break;

						default:
						//没有这个条件
					}
				}
			}
		}
		if(false == empty($ConditionArrayDaate)) {
			$ConditionArray['ptdate'] = $ConditionArrayDaate;
		}

		/* 从基本设置表读取参数，根据学期号读取数据 */
		$BasicsetupObj = M('basicsetup');
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="semester"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$ConditionArray['srid'] = intval($BasicsetupData[0]['bdatavalue']);
		}
		
		/* 查询缴费金额大于0的数据 */
		$ConditionArray['ptmoney'] = array('gt', 0);

		return $ConditionArray;
	}
	
	/*
		财务管理条件初始化方法
	*/
	public function FinanceInitializationInfo()
	{
		if(false == empty($_REQUEST['sname'])) {
			$SName = $_REQUEST['sname'];
		} else {
			$SName = null;
		}
		$FileDataArray = array(
			'textname' => '学员姓名：',
			'name' => 'sname',
			'width' => '100px',
			'default' => $SName,
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('sname', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		if(false == empty($_REQUEST['ptdate1'])) {
			$PtDate1 = $_REQUEST['ptdate1'];
		} else {
			$PtDate1 = null;
		}
		if(false == empty($_REQUEST['ptdate2'])) {
			$PtDate2 = $_REQUEST['ptdate2'];
		} else {
			$PtDate2 = null;
		}
		$CreateDate = array(
			'datename' => '缴费时间：',
			'name' => 'ptdate',
			'type' => 2,
			'width' => '100px',
			'default' => $PtDate1,
			'default2' => $PtDate2,
		);
		$SelectObj = new InputDateModel($CreateDate);
		$this->assign('ptdate', $SelectObj->GetInputDateHtmlInfo());
	}
	
	/*
		编辑缴费表数据方法
	*/
	public function FinanceEditorInfo()
	{
		date_default_timezone_set('Asia/Shanghai');
		
		/* 判断是否是form提交 */
		if(false == empty($_POST['submit'])) {
			if($_POST['ptmoney'] >= 1) {
				$PtID = $_POST['ptid'];
				$SID = $_POST['sid'];
				
				/* 更新时间 */
				$_POST['ptupdate'] = date('Y-m-d H:i:s',time());
				
				/* 删除当前数组中的不属于学员表字段的键值对 */
				unset($_POST['submit']);
				unset($_POST['ptid']);
				unset($_POST['sid']);
				
				/* 更新数据操作 */
				$PaymentObj = M('payment');
				$PaymentObj->startTrans();  /* 启动事务 */
				
				/* 查询原始金额和原始备注信息 */
				$PaymentData = $PaymentObj->field('ptmoney,ptremarks')->select($PtID);
				$PaymentDataPtmoney = false == empty($PaymentData[0]['ptmoney']) ? $PaymentData[0]['ptmoney'] : null;
				$PaymentDataPtremarks = false == empty($PaymentData[0]['ptremarks']) ? $PaymentData[0]['ptremarks'] : null;
				
				/* 判断当前值和数据库的是否一致，如果一致就不执行更新操作 */
				if($PaymentDataPtremarks != $_POST['ptremarks'] || $PaymentDataPtmoney != $_POST['ptmoney']) {
					/* 更新学员缴费表 */
					$PStatu = $PaymentObj->where('ptid='.$PtID)->save($_POST);
				} else {
					$PStatu = false;
				}

				/* 如果缴费表数据更新成功，就更新学员表的缴费状态 */
				if($PStatu) {
					$StudentObj = M('student');
					$StudentObj->where('sid='.$SID)->setField(array('stuitionstatu'=>2));
					
					/* 查询当前学员的姓名 */
					$StudentData = $StudentObj->field('sname')->select($SID);
					$StudentDatName = false == empty($StudentData[0]['sname']) ? $StudentData[0]['sname'] : null;
					
					/* 判断原始金额与现在的金额的比较 */
					if($PaymentDataPtmoney == $_POST['ptmoney']) {
						$PgupdateType = '金额未变动';
					} else if($PaymentDataPtmoney > $_POST['ptmoney']) {
						$PgupdateType = '减少<span style="font-size:14px;color:#F05648;font-weight:700;"> '.($PaymentDataPtmoney-$_POST['ptmoney']).' </span>元';
					} else if($PaymentDataPtmoney < $_POST['ptmoney']) {
						$PgupdateType = '增加<span style="font-size:14px;color:#F05648;font-weight:700;"> '.($_POST['ptmoney']-$PaymentDataPtmoney).' </span>元';
					}
					
					/* 写入学员缴费日志表 */
					$PaymentModifylogData = array(
						'aid' => $_SESSION['schoolcms_admin']['aid'],
						'aname' => $_SESSION['schoolcms_admin']['aname'],
						'sid' => $SID,
						'sname' => $StudentDatName,
						'pgoriginalmoney' => $PaymentDataPtmoney,
						'pgupdatemoney' => $_POST['ptmoney'],
						'pgupdatetype' => $PgupdateType,
						'pgoperatingtype' => 2,
						'pgoperatingdate' => date('Y-m-d H:i:s',time()),
						'pgoperatingtype' => '更新',
						'pgremark' => $_POST['ptremarks'],
					);
					$PaymentModifylogObj = M('paymentmodifylog');
					$PaymentModifylogObj->add($PaymentModifylogData);
					
				}

				/* 如果操作成功就提交事务 */
				if($PStatu) {
					$PaymentObj->commit();  /* 提交事务 */
					$this->SchoolCMSPrompt('操作成功！', 'PaymentManagement');
				} else {
					$PaymentObj->rollback();  /* 回滚事务 */
					$this->SchoolCMSPrompt('操作失败！', 'PaymentManagement');
				}
			} else {
				$this->SchoolCMSPrompt('金额有误！');
			}
			
		} else {
			/* 表前缀名 */
			$PREFIX = C('DB_PREFIX');
			
			/* 判断当前表是否存在 */
			$TableStatu = $this->TableName($PREFIX.'view_payment_student');
			
			/* 实例化缴费视图表 */
			$FinanceObj = M('view_payment_student');
			
			if(false == empty($_GET['ptid'])) {
				//缴费id
				if(true == $TableStatu) {
					$FinanceDataArray = $FinanceObj->where('ptid='.$_GET['ptid'])->select();
				} else {
					$FinanceDataArray = $FinanceObj->query("select `".$PREFIX."student`.`sname` AS `sname`,`".$PREFIX."payment`.`ptmoney` AS `ptmoney`,`".$PREFIX."payment`.`ptid` AS `ptid`,`".$PREFIX."payment`.`ptremarks` AS `ptremarks`,`".$PREFIX."payment`.`ptdate` AS `ptdate`,`".$PREFIX."payment`.`sid` AS `sid`,`".$PREFIX."payment`.`ptupdate` AS `ptupdate`,`".$PREFIX."semester`.`srid` AS `srid` from ((`".$PREFIX."student` join `".$PREFIX."payment` on((`".$PREFIX."payment`.`sid` = `".$PREFIX."student`.`sid`))) join `".$PREFIX."semester` on((`".$PREFIX."semester`.`srid` = `".$PREFIX."student`.`srid`))) where ((`".$PREFIX."semester`.`srstatu` = 1) and `".$PREFIX."payment`.`ptid`=".$_GET['ptid'].")");
				}
			} else {
				if(false == empty($_GET['sid'])) {
					//学员id
					if(true == $TableStatu) {
						$FinanceDataArray = $FinanceObj->where('sid='.$_GET['sid'])->select();
					} else {
						$FinanceDataArray = $FinanceObj->query("select `".$PREFIX."student`.`sname` AS `sname`,`".$PREFIX."payment`.`ptmoney` AS `ptmoney`,`".$PREFIX."payment`.`ptid` AS `ptid`,`".$PREFIX."payment`.`ptremarks` AS `ptremarks`,`".$PREFIX."payment`.`ptdate` AS `ptdate`,`".$PREFIX."payment`.`sid` AS `sid`,`".$PREFIX."payment`.`ptupdate` AS `ptupdate`,`".$PREFIX."semester`.`srid` AS `srid` from ((`".$PREFIX."student` join `".$PREFIX."payment` on((`".$PREFIX."payment`.`sid` = `".$PREFIX."student`.`sid`))) join `".$PREFIX."semester` on((`".$PREFIX."semester`.`srid` = `".$PREFIX."student`.`srid`))) where ((`".$PREFIX."semester`.`srstatu` = 1) and `".$PREFIX."payment`.`sid`=".$_GET['sid'].")");
					}
				}
			}
			$this->FinanceInterfaceInfo('./FinanceEditorInfo', $FinanceDataArray);
		}
	}
	
	/*
		缴费编辑界面方法
	*/
	private function FinanceInterfaceInfo($Url, $FinanceDataArray = array())
	{
		if(false == empty($FinanceDataArray[0]['ptmoney'])) {
			$Ptmoney = $FinanceDataArray[0]['ptmoney'];
		} else {
			$Ptmoney = null;
		}
		$FileDataArray = array(
			'textname' => '缴费金额：',
			'name' => 'ptmoney',
			'prompt' => '输入缴费金额',
			'prompttype' => 1,
			'lengthsmall' => 1,
			'lengthlarge' => 6,
			'verificationtype' => 7,
			'mousedown' => '请输入1至6位之间的字符！',
			'default' => $Ptmoney,
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('ptmoney', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		if(false == empty($FinanceDataArray[0]['ptremarks'])) {
			$Ptremarks = $FinanceDataArray[0]['ptremarks'];
		} else {
			$Ptremarks = null;
		}
		$FileDataArray = array(
			'textname' => '缴费备注：',
			'name' => 'ptremarks',
			'prompt' => '输入缴费备注',
			'prompttype' => 1,
			'lengthsmall' => 1,
			'lengthlarge' => 255,
			'mousedown' => '请输入1至255位之间的字符！',
			'default' => $Ptremarks,
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('ptremarks', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		/* 缴费id */
		if(false == empty($FinanceDataArray[0]['ptid'])) {
			$PtID = $FinanceDataArray[0]['ptid'];
		} else {
			$PtID = null;
		}
		$this->assign('ptid', $PtID);
		
		/* 学员id */
		if(false == empty($FinanceDataArray[0]['sid'])) {
			$SID = $FinanceDataArray[0]['sid'];
		} else {
			$SID = null;
		}
		$this->assign('sid', $SID);
		
		/* 学员姓名 */
		if(false == empty($FinanceDataArray[0]['sname'])) {
			$SName = $FinanceDataArray[0]['sname'];
		} else {
			$SName = null;
		}
		$this->assign('sname', $SName);
		
		/* 使用类型，url地址 */
		$this->assign('url', $Url);
		
		$this->display('Finance/financeinterface');
	}
	

}
?>