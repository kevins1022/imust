<?php

/***
 *	copyright	:	Copyright (c) 2007-2013 SchoolCMS Inc. (http://www.schoolcms.cn/)
 *	license		:	http://www.schoolcms.cn/
 *	ContactQQ	:	1655098383
 *	E-mail		:	386392432@qq.com
 *	您可以在完全遵守本许可协议的基础上，将本软件应用于非商业用途，而不必支付软件版权许可费用。
 ***/

if(!$_SESSION['schoolcms_admin']['aname']) Header('Location:'.__ROOT__.'/admin.php?action=login');

class FinancialDetailsAction extends CommonAction
{
	/*
		缴费明细管理
	*/
	public function PaymentDetailsManagement()
	{
		/* 实例化财务明细表 */
		$FinancialDetailsObj = M('paymentmodifylog');

		/* 调用初始化方法 */
		$this->FinancialDetailsInitializationInfo();

		/* 调用查询条件处理方法 */
		$ConditionArray = $this->FinancialDetailsInquiryCondition($_REQUEST);

		/* 按照条件获取数据的总条数 */
		$FinancialDetailsCount = count($FinancialDetailsObj->where($ConditionArray)->select());
		
		/* 从基本设置表读取分页参数 */
		$BasicsetupObj = M('basicsetup');
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="financialdetailspage"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$FinancialDetailsPage = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$FinancialDetailsPage = 8;
		}

		$PageData = $BasicsetupObj->field('bdatavalue')->where('btype="modelpage"')->select();
		if(false == empty($PageData[0]['bdatavalue'])) {
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
			'total' => $FinancialDetailsCount,
			'traction' => $FinancialDetailsPage,  //显示条数
			'custompage' => $CustomPage,  //开启自定义跳转页码
			'detaildata' => $DetailData,  //开启详情数据
			'pagecoent' => 3,  //分页显示的个数
			'url' => './PaymentDetailsManagement',
		);
		$PageingObj = new PagingModel($_REQUEST, $Configuration);
		$this->assign('pageing', $PageingObj->GetPagingHtmlInfo());
		
	
		/* 起始页码值和每页显示条数从分页模块里面获取 */
		$FinancialDetailsData = $FinancialDetailsObj->limit($PageingObj->GetStarting(), $PageingObj->GetFraction())->where($ConditionArray)->order("pgoperatingdate desc")->select();
		
		/* 调用网格和CSV模块 */
		$this->assign('gridtable', $this->GetGridInitializationInfo($FinancialDetailsData));
		$this->assign('subcsvexport', $this->GetCSVInitializationInfo());
		

		$this->display('FinancialDetails/paymentdetailsmanagement');
	}
	
	/*
		数据字段数组方法
	*/
	private function GetFieldArray()
	{
		//调用网格显示数据
		return $FieldArray = array(
			'aname' => '管理员名',
			'sname' => '学员姓名',
			'pgoriginalmoney' => '原始金额',
			'pgupdatemoney' => '变更后金额',
			'pgupdatetype' => '变更类型',
			'pgoperatingdate' => '操作时间',
			'pgoperatingtype' => '操作类型',
			'pgremark' => '缴费备注',
		);
	}
	
	/*
		初始化CSV方法
	*/
	public function GetCSVInitializationInfo()
	{
		$CsvExportObj = new CSVModel($this->GetFieldArray(), 'subcsvexport', '财务明细数据', './GetCSVInitializationInfo');
		//判断是不是到处CSV
		if(false == empty($_REQUEST['type'])) {
			if($_REQUEST['type'] == 'csvexport') {
				/* 实例化财务明细表 */
				$FinancialDetailsObj = M('paymentmodifylog');
				
				$CsvExportObj->CsvExportData($FinancialDetailsObj->select());  //处理页面传递过来的数据
			}
		} else {
			return $CsvExportObj->GetTitltDataHtml();
		}
	}
	
	/*
		初始化网格方法
	*/
	private function GetGridInitializationInfo($FinancialDetailsData)
	{
		$Configuration = array('id' => 'pgid',);
		$GridObj = new GridModel($FinancialDetailsData, $this->GetFieldArray(), $Configuration);
		return $GridObj->GetGridHtmlInfo();
	}
	
	/*
		财务信息查询条件处理方法
	*/
	private function FinancialDetailsInquiryCondition($Inquiry = array())
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
						//管理员名
						case 'aname':
							$ConditionArray['aname'] = array('like','%'.$Cvalue.'%');
						break;
						//学生姓名
						case 'sname':
							$ConditionArray['sname'] = array('like','%'.$Cvalue.'%');
						break;
						//操作时间
						case 'pgoperatingdate1':
							array_push($ConditionArrayDaate, array('egt', $Cvalue));
						break;
						//操作时间
						case 'pgoperatingdate2':
							array_push($ConditionArrayDaate, array('elt', $Cvalue));
						break;

						default:
						//没有这个条件
					}
				}
			}
		}
		if(false == empty($ConditionArrayDaate)) {
			$ConditionArray['pgoperatingdate'] = $ConditionArrayDaate;
		}

		return $ConditionArray;
	}
	
	/*
		财务明细管理条件初始化方法
	*/
	public function FinancialDetailsInitializationInfo()
	{
		if(false == empty($_REQUEST['aname'])) {
			$AName = $_REQUEST['aname'];
		} else {
			$AName = null;
		}
		$FileDataArray = array(
			'textname' => '管理员名：',
			'name' => 'aname',
			'width' => '100px',
			'default' => $AName,
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('aname', $FileDataArrayObj->GetInputTextHtmlInfo());
		
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
		
		if(false == empty($_REQUEST['pgoperatingdate1'])) {
			$PgoperatingDate1 = $_REQUEST['pgoperatingdate1'];
		} else {
			$PgoperatingDate1 = null;
		}
		if(false == empty($_REQUEST['pgoperatingdate2'])) {
			$PgoperatingDate2 = $_REQUEST['pgoperatingdate2'];
		} else {
			$PgoperatingDate2 = null;
		}
		$CreateDate = array(
			'datename' => '操作时间：',
			'name' => 'pgoperatingdate',
			'type' => 2,
			'width' => '100px',
			'default' => $PgoperatingDate1,
			'default2' => $PgoperatingDate2,
		);
		$SelectObj = new InputDateModel($CreateDate);
		$this->assign('pgoperatingdate', $SelectObj->GetInputDateHtmlInfo());
	}
	
	/*
		编辑缴费表数据方法
	*/
	public function FinancialDetailsEditorInfo()
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
				$PaymentDetailsObj = M('payment');
				$PaymentDetailsObj->startTrans();  /* 启动事务 */
				
				/* 查询原始金额和原始备注信息 */
				$PaymentDetailsData = $PaymentDetailsObj->field('ptmoney,ptremarks')->select($PtID);
				$PaymentDetailsDataPtmoney = false == empty($PaymentDetailsData[0]['ptmoney']) ? $PaymentDetailsData[0]['ptmoney'] : null;
				$PaymentDetailsDataPtremarks = false == empty($PaymentDetailsData[0]['ptremarks']) ? $PaymentDetailsData[0]['ptremarks'] : null;
				
				/* 判断当前值和数据库的是否一致，如果一致就不执行更新操作 */
				if($PaymentDetailsDataPtremarks != $_POST['ptremarks'] || $PaymentDetailsDataPtmoney != $_POST['ptmoney']) {
					/* 更新学员缴费表 */
					$PStatu = $PaymentDetailsObj->where('ptid='.$PtID)->save($_POST);
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
					if($PaymentDetailsDataPtmoney == $_POST['ptmoney']) {
						$PgupdateType = '金额未变更';
					} else if($PaymentDetailsDataPtmoney > $_POST['ptmoney']) {
						$PgupdateType = '减少<span style="color:#F00;">'.($PaymentDetailsDataPtmoney-$_POST['ptmoney']).'</span>元';
					} else if($PaymentDetailsDataPtmoney < $_POST['ptmoney']) {
						$PgupdateType = '增加<span style="color:#F00;">'.($_POST['ptmoney']-$PaymentDetailsDataPtmoney).'</span>元';
					}
					
					/* 写入学员缴费日志表 */
					$PaymentDetailsModifylogData = array(
						'aid' => $_SESSION['schoolcms_admin']['aid'],
						'aname' => $_SESSION['schoolcms_admin']['aname'],
						'sid' => $SID,
						'sname' => $StudentDatName,
						'pgoriginalmoney' => $PaymentDetailsDataPtmoney,
						'pgupdatemoney' => $_POST['ptmoney'],
						'pgupdatetype' => $PgupdateType,
						'pgoperatingtype' => '更新',
						'pgoperatingdate' => date('Y-m-d H:i:s',time()),
						'pgremark' => $_POST['ptremarks'],
					);
					$PaymentDetailsModifylogObj = M('paymentmodifylog');
					$PaymentDetailsModifylogObj->add($PaymentDetailsModifylogData);
					
				}

				/* 如果操作成功就提交事务 */
				if($PStatu) {
					$PaymentDetailsObj->commit();  /* 提交事务 */
					$this->SchoolCMSPrompt('操作成功！', 'PaymentDetailsManagement');
				} else {
					$PaymentDetailsObj->rollback();  /* 回滚事务 */
					$this->SchoolCMSPrompt('操作失败！', 'PaymentDetailsManagement');
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
			$FinancialDetailsObj = M('view_payment_student');
			
			if(false == empty($_GET['ptid'])) {
				//缴费id
				if(true == $TableStatu) {
					$FinancialDetailsDataArray = $FinancialDetailsObj->where('ptid='.$_GET['ptid'])->select();
				} else {
					$FinancialDetailsDataArray = $FinancialDetailsObj->query("select `".$PREFIX."student`.`sname` AS `sname`,`".$PREFIX."payment`.`ptmoney` AS `ptmoney`,`".$PREFIX."payment`.`ptid` AS `ptid`,`".$PREFIX."payment`.`ptremarks` AS `ptremarks`,`".$PREFIX."payment`.`ptdate` AS `ptdate`,`".$PREFIX."payment`.`sid` AS `sid`,`".$PREFIX."payment`.`ptupdate` AS `ptupdate`,`".$PREFIX."semester`.`srid` AS `srid` from ((`".$PREFIX."student` join `".$PREFIX."payment` on((`".$PREFIX."payment`.`sid` = `".$PREFIX."student`.`sid`))) join `".$PREFIX."semester` on((`".$PREFIX."semester`.`srid` = `".$PREFIX."student`.`srid`))) where ((`".$PREFIX."semester`.`srstatu` = 1) and `".$PREFIX."payment`.`ptid`=".$_GET['ptid'].")");
				}
			} else {
				if(false == empty($_GET['sid'])) {
					//学员id
					if(true == $TableStatu) {
						$FinancialDetailsDataArray = $FinancialDetailsObj->where('sid='.$_GET['sid'])->select();
					} else {
						$FinancialDetailsDataArray = $FinancialDetailsObj->query("select `".$PREFIX."student`.`sname` AS `sname`,`".$PREFIX."payment`.`ptmoney` AS `ptmoney`,`".$PREFIX."payment`.`ptid` AS `ptid`,`".$PREFIX."payment`.`ptremarks` AS `ptremarks`,`".$PREFIX."payment`.`ptdate` AS `ptdate`,`".$PREFIX."payment`.`sid` AS `sid`,`".$PREFIX."payment`.`ptupdate` AS `ptupdate`,`".$PREFIX."semester`.`srid` AS `srid` from ((`".$PREFIX."student` join `".$PREFIX."payment` on((`".$PREFIX."payment`.`sid` = `".$PREFIX."student`.`sid`))) join `".$PREFIX."semester` on((`".$PREFIX."semester`.`srid` = `".$PREFIX."student`.`srid`))) where ((`".$PREFIX."semester`.`srstatu` = 1) and `".$PREFIX."payment`.`sid`=".$_GET['sid'].")");
					}
				}
			}
			$this->FinancialDetailsInterfaceInfo('./FinancialDetailsEditorInfo', $FinancialDetailsDataArray);
		}
	}
	
	/*
		缴费编辑界面方法
	*/
	private function FinancialDetailsInterfaceInfo($Url, $FinancialDetailsDataArray = array())
	{
		if(false == empty($FinancialDetailsDataArray[0]['ptmoney'])) {
			$Ptmoney = $FinancialDetailsDataArray[0]['ptmoney'];
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
			'mousedown' => '请输入1至6位之间的字符！',
			'default' => $Ptmoney,
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('ptmoney', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		if(false == empty($FinancialDetailsDataArray[0]['ptremarks'])) {
			$Ptremarks = $FinancialDetailsDataArray[0]['ptremarks'];
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
		if(false == empty($FinancialDetailsDataArray[0]['ptid'])) {
			$PtID = $FinancialDetailsDataArray[0]['ptid'];
		} else {
			$PtID = null;
		}
		$this->assign('ptid', $PtID);
		
		/* 学员id */
		if(false == empty($FinancialDetailsDataArray[0]['sid'])) {
			$SID = $FinancialDetailsDataArray[0]['sid'];
		} else {
			$SID = null;
		}
		$this->assign('sid', $SID);
		
		/* 学员姓名 */
		if(false == empty($FinancialDetailsDataArray[0]['sname'])) {
			$SName = $FinancialDetailsDataArray[0]['sname'];
		} else {
			$SName = null;
		}
		$this->assign('sname', $SName);
		
		/* 使用类型，url地址 */
		$this->assign('url', $Url);
		
		$this->display('FinancialDetails/financeinterface');
	}
	
	/*
		缴费明细
	*/
	public function PaymentDetailsDetailsInfo()
	{
		echo '缴费明细';
	}

}
?>