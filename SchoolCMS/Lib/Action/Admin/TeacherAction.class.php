<?php

/***
 *	copyright	:	Copyright (c) 2007-2013 SchoolCMS Inc. (http://www.schoolcms.cn/)
 *	license		:	http://www.schoolcms.cn/
 *	ContactQQ	:	1655098383
 *	E-mail		:	386392432@qq.com
 *	您可以在完全遵守本许可协议的基础上，将本软件应用于非商业用途，而不必支付软件版权许可费用。
 ***/

if(!$_SESSION['schoolcms_admin']['aname']) Header('Location:'.__ROOT__.'/admin.php?action=login');

class TeacherAction extends CommonAction
{
	/*
		公共使用
	*/
	public function Index()
	{
		/* 判断网格的操作类型，1：编辑，2：删除 */
		if(false == empty($_REQUEST['type'])) {
			if($_REQUEST['type'] == 1) { 
				if(false == empty($_REQUEST['tid'])) {
					$this->TeacherEditorInfo($_REQUEST['tid']);
				}
			} else if($_REQUEST['type'] == 2) {
				if(false == empty($_REQUEST['tid'])) {
					$this->TeacherDeleteInfo(array($_REQUEST['tid']));
				}
				
			}
		}
		
		/* form 学员数据删除判断，如果不为空就是form提交的多个学员数据删除 */
		if(false == empty($_REQUEST['submitdelete'])) {
			if(false == empty($_REQUEST['checkbox'])) {
				$this->TeacherDeleteInfo($_REQUEST['checkbox']);
			}
		}
	}
		
	/*
		教师管理
	*/
	public function TeacherManagement()
	{
		date_default_timezone_set('Asia/Shanghai');
		
		//调用初始化方法
		$this->TeacherInitializationInfo();
		
		//调用查询条件处理方法
		$ConditionArray = $this->TeacherInquiryCondition($_REQUEST);
		
		$TeacherObj = M('teacher');
		/* 按照条件获取数据的总条数 */
		$TeacherCount = count($TeacherObj->where($ConditionArray)->select());
		
		/* 从基本设置表读取分页参数 */
		$BasicsetupObj = M('basicsetup');
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="teacherpage"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$TeacherPage = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$TeacherPage = 8;
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
			'total' => $TeacherCount,
			'traction' => $TeacherPage,  //显示条数
			'custompage' => $CustomPage,  //开启自定义跳转页码
			'detaildata' => $DetailData,  //开启详情数据
			'pagecoent' => 3,  //分页显示的个数
			'url' => './TeacherManagement',
		);
		$PageingObj = new PagingModel($_REQUEST, $Configuration);
		$this->assign('pageing', $PageingObj->GetPagingHtmlInfo());

		
		//起始页码值和每页显示条数从分页模块里面获取
		$TeacherData = $TeacherObj->limit($PageingObj->GetStarting(), $PageingObj->GetFraction())->where($ConditionArray)->select();
		//print_r($TeacherData);//exit();
		
		/* 调用方法处理数据 */
		$TeacherData = $this->GetTeacherDataArray($TeacherData);
		
		/* 调用网格和CSV模块 */
		$this->assign('gridtable', $this->GetGridInitializationInfo($TeacherData));
		$this->assign('subcsvexport', $this->GetCSVInitializationInfo());

		$this->display('Teacher/teachermanagement');
	}
	
	/*
		初始化CSV方法
	*/
	public function GetCSVInitializationInfo()
	{
		$CsvExportObj = new CSVModel($this->GetFieldArray(), 'subcsvexport', '教师数据', './GetCSVInitializationInfo');
		//判断是不是到处CSV
		if(false == empty($_REQUEST['type'])) {
			if($_REQUEST['type'] == 'csvexport') {
				$TeacherObj = M('teacher');
				$CsvExportObj->CsvExportData($this->GetTeacherDataArray($TeacherObj->select()));  //处理页面传递过来的数据
			}
		} else {
			return $CsvExportObj->GetTitltDataHtml();
		}
	}
	
	/*
		初始化网格方法
	*/
	private function GetGridInitializationInfo($TeacherData)
	{
		$Configuration = array(
			'id' => 'tid',
			'checkbox' => 1,  //是否开启复选框
			'name' => 'checkbox',
		);
		$Operating = array(
			array(
				'type' => 1,
				'name' => '编辑',
				'returntype' => 1,
				'url' => './Index',
			),
			array(
				'type' => 2,
				'name' => '删除',
				'event' => 'gridmodeldelete',
			),
			array(
				'type' => 1,
				'name' => '添加课程',
				'returntype' => 3,
				'url' => '../TeacherCurriculum/TeacherCurriculumAddInfo',
			),
		);
		$GridObj = new GridModel($TeacherData, $this->GetFieldArray(), $Configuration, $Operating);
		return $GridObj->GetGridHtmlInfo();
	}
	
	/*
		数据字段数组方法
	*/
	private function GetFieldArray()
	{
		//调用网格显示数据
		return $FieldArray = array(
			'tname' => '姓名',
			'tage' => '年龄',
			'tsex' => '性别',
			'tbirthdate' => '出生',
			'tmobile' => '联系手机',
			'thomephone' => '家庭电话',
			'tmail' => '电子邮箱',
			'tsreatedate' => '注册时间',
		);
	}
	
	/*
		最终的数据处理字段
	*/
	private function GetTeacherDataArray($TeacherData)
	{
		if(false == empty($TeacherData) && true == is_array($TeacherData)) {
			foreach($TeacherData as $SKey=>$SValue) {
				foreach($SValue as $SKeys=>$SValues) {
					/* 将时间戳转为日期 */
					if($SKeys == 'tbirthdate') {
						$SValue['tbirthdate'] = date('Y-m-d', $SValues);
						
						/* 获取当前时间减去数据库的出生日期等于学员的实际年龄 */
						$SValue['tage'] = date('Y-m-d', time())-date('Y-m-d', $SValues).' 周岁';
					}
					if($SKeys == 'tsex') {
						switch($SValues) {
							//保密
							case 1:
								$Tsex = '保密';
							break;
							//女
							case 2:
								$Tsex = '女';
							break;
							//男
							case 3:
								$Tsex = '男';
							break;

							default:
							//这里报错，没有这个条件
						}
						$SValue['tsex'] = $Tsex;
					}
				}
				$TeacherData[$SKey] = $SValue;
			}
		}
		return $TeacherData;
	}
	
	/*
		教师信息查询条件处理方法
	*/
	private function TeacherInquiryCondition($Inquiry = array())
	{
		/* 设置当前时间地区 */
		date_default_timezone_set('Asia/Shanghai');
		/* 条件查询拼接 */
		$ConditionArray = array();
		$ConditionArrayAge = array();
		$ConditionArrayDaate = array();
		if(false == empty($Inquiry)) {
			foreach($Inquiry as $Ckey=>$Cvalue) {
				if((false == empty($Cvalue)) && (false == is_array($Ckey))) {
					switch($Ckey) {
						//教师姓名
						case 'tname':
							$ConditionArray['tname'] = array('like','%'.$Cvalue.'%');
						break;
						//性别
						case 'tsex':
							$ConditionArray['tsex'] = intval($Cvalue);
						break;
						//年龄起始
						case 'tage':
							if(0 != intval($Cvalue)) {
								array_push($ConditionArrayAge, array('elt', strtotime(date('Y',time())-$Cvalue)));
							}
						break;
						//年龄终止
						case 'tages':
							if(0 != intval($Cvalue)) {
								array_push($ConditionArrayAge, array('egt', strtotime(date('Y',time())-$Cvalue)));
							}
						break;
						//出生日期
						case 'tbirthdate':
							array_push($ConditionArrayAge, array('eq', strtotime($Cvalue)));
						break;
						//创建时间的起始时间
						case 'tsreatedate1':
							array_push($ConditionArrayDaate, array('egt', $Cvalue));
						break;
						//创建时间的终止时间
						case 'tsreatedate2':
							array_push($ConditionArrayDaate, array('elt', $Cvalue));
						break;

						default:
						//没有这个条件
					}
				}
			}
		}
		if(false == empty($ConditionArrayAge)) {
			$ConditionArray['tbirthdate'] = $ConditionArrayAge;
		}
		if(false == empty($ConditionArrayDaate)) {
			$ConditionArray['tsreatedate'] = $ConditionArrayDaate;
		}
		
		return $ConditionArray;
	}
	
	/*
		条件查询初始化方法
	*/
	private function TeacherInitializationInfo()
	{
		if(false == empty($_REQUEST['tname'])) {
			$TName = $_REQUEST['tname'];
		} else {
			$TName = null;
		}
		$FileDataArray = array(
			'textname' => '姓名：',
			'name' => 'tname',
			'width' => '100px',
			'default' => $TName,
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('tname', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		if(false == empty($_REQUEST['tage'])) {
			$Age = $_REQUEST['tage'];
		} else {
			$Age = null;
		}
		$FileDataArray = array(
			'textname' => '年龄：',
			'name' => 'tage',
			'width' => '36px',
			'default' => $Age,
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('tage', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		if(false == empty($_REQUEST['tages'])) {
			$Ages = $_REQUEST['tages'];
		} else {
			$Ages = null;
		}
		$FileDataArray = array(
			'textname' => ' 至 ',
			'name' => 'tages',
			'width' => '36px',
			'default' => $Ages,
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('tages', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		if(false == empty($_REQUEST['tbirthdate'])) {
			$TBirthdate = $_REQUEST['tbirthdate'];
		} else {
			$TBirthdate = null;
		}
		$BirthDate = array(
			'datename' => '出生日期：',
			'name' => 'tbirthdate',
			'type' => 1,
			'width' => '100px',
			'default' => $TBirthdate,
		);
		$SelectObj = new InputDateModel($BirthDate);
		$this->assign('tbirthdate', $SelectObj->GetInputDateHtmlInfo());
		
		if(false == empty($_REQUEST['tsreatedate1'])) {
			$TSreateDate1 = $_REQUEST['tsreatedate1'];
		} else {
			$TSreateDate1 = null;
		}
		if(false == empty($_REQUEST['tsreatedate2'])) {
			$TSreateDate2 = $_REQUEST['tsreatedate2'];
		} else {
			$TSreateDate2 = null;
		}
		$CreateDate = array(
			'datename' => '注册时间：',
			'name' => 'tsreatedate',
			'type' => 2,
			'width' => '100px',
			'default' => $TSreateDate1,
			'default2' => $TSreateDate2,
		);
		$SelectObj = new InputDateModel($CreateDate);
		$this->assign('tsreatedate', $SelectObj->GetInputDateHtmlInfo());
		
		if(false == empty($_REQUEST['tsex'])) {
			$TSex = $_REQUEST['tsex'];
		} else {
			$TSex = null;
		}
		$SexConfiguration = array(
			'title' => '性别：',
			'selectid' => 'id',
			'selectname' => 'name',
			'name' => 'tsex',
			'whole' => '全部',
			'width' => '100px',
			'default' => $TSex,
		);
		$SexDataArray = array(
			array('id' => 1, 'name' => '保密',),
			array('id' => 2, 'name' => '女',),
			array('id' => 3, 'name' => '男',),
		);
		$SelectObj = new SelectModel($SexDataArray, $SexConfiguration);
		$this->assign('tsex', $SelectObj->GetSelectHtmlInfo());
	}
	
	
	/*
		删除教师数据方法（支持批量处理）一维数组
	*/
	public function TeacherDeleteInfo($TeacherArraySID)
	{
		//判断id是数组还是一个，implode 将一个数组的键值拼接成一个字符串
		if((true == is_array($TeacherArraySID)) && (false == empty($TeacherArraySID))){
			$TeacherDeleteObj = M('teacher');
			$TeacherDeleteObj->startTrans();  /* 启动事务 */

			$Where = 'tid in('.implode(',', $TeacherArraySID).')';
			
			/* 循环删除当前教师的课程数据 */
			foreach($TeacherArraySID as $SValue) {
				$TeacherCurriculumDeleteObj = M('tcwct');
				$TeacherCurriculumDeleteObj->where('tid='.intval($SValue))->delete();
			}

			/* 删除教师数据 */
			$Statu = $TeacherDeleteObj->where($Where)->delete();

			/* 删除状态 */
			if(true == $Statu) {
				$TeacherDeleteObj->commit();  /* 提交事务 */
				$this->SchoolCMSPrompt("成功删除 {$Statu} 条！");
			} else {
				$TeacherDeleteObj->rollback();  /* 回滚事务 */
				$this->SchoolCMSPrompt('删除失败！');
			}
		}
	}
	
	/*
		教师编辑方法
	*/
	public function TeacherEditorInfo($TeacherID = null)
	{
		date_default_timezone_set('Asia/Shanghai');
		$TeacherObj = M('teacher');
		if(false == empty($_POST['submit'])) {
			
			/* 将学员出生日期转换为时间戳 */
			$_POST['tbirthdate'] = strtotime($_POST['tbirthdate']);

			/* 教师id */
			if(false == empty($_POST['tid'])) {
				$Tid = $_POST['tid'];
			} else {
				$Tid = null;
			}
			/* 删除当前数组中的submit和tid键值对 */
			unset($_POST['submit']);
			unset($_POST['tid']);
			
			/* 更新数据操作 */
			$Statu = $TeacherObj->where('tid='.$Tid)->save($_POST);
			if($Statu) {
				$this->SchoolCMSPrompt('更新成功！', 'TeacherManagement');
			} else {
				$this->SchoolCMSPrompt('更新失败！', 'TeacherManagement');
			}
		} else {
			if(false == empty($TeacherID)) {
				$TeacherData = $TeacherObj->select(intval($TeacherID));
				$this->TeacherInterfaceInfo('./TeacherEditorInfo', $TeacherData[0]);
			}
		}
	}
	
	/*
		新增教师方法
	*/
	public function TeacherAddInfo()
	{
		if(false == empty($_POST['submit'])) {
			/* 删除当前数组中的submit和tid键值对 */
			unset($_POST['submit']);
			unset($_POST['tid']);
			
			/* 创建时间 */
			$_POST['tsreatedate'] = date('Y-m-d H:i:s',time());

			/* 将学员出生日期转换为时间戳 */
			$_POST['tbirthdate'] = strtotime($_POST['tbirthdate']);

			/* 新增数据操作 */
			$TeacherObj = M('teacher');
			$Statu = $TeacherObj->add($_POST);
			if($Statu) {
				$this->SchoolCMSPrompt('新增成功！', 'TeacherManagement', null, '继续新增教师', 'TeacherAddInfo');
			} else {
				$this->SchoolCMSPrompt('新增失败！');
			}
		} else {
			$this->TeacherInterfaceInfo('./TeacherAddInfo');
		}
	}
	
	/*
		新增教师和编辑数据界面方法
	*/
	private function TeacherInterfaceInfo($Url, $TeacherDataArray = array())
	{
		if(false == empty($TeacherDataArray['tname'])) {
			$TName = $TeacherDataArray['tname'];
		} else {
			$TName = null;
		}
		$FileDataArray = array(
			'textname' => '教师姓名：',
			'name' => 'tname',
			'prompt' => '输入学员姓名',
			'prompttype' => 1,
			'lengthsmall' => 2,
			'lengthlarge' => 6,
			'required' => 1,
			'default' => $TName,
			'mousedown' => '请输入2至6位之间的字符！',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('tname', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		if(false == empty($TeacherDataArray['tmobile'])) {
			$TMobile = $TeacherDataArray['tmobile'];
		} else {
			$TMobile = null;
		}
		$FileDataArray = array(
			'textname' => '联系手机：',
			'name' => 'tmobile',
			'prompt' => '输入联系手机',
			'prompttype' => 1,
			'required' => 1,
			'verificationtype' => 2,
			'default' => $TMobile,
			'mousedown' => '请输入11位数字手机号！',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('tmobile', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		if(false == empty($TeacherDataArray['thomephone'])) {
			$THomephone = $TeacherDataArray['thomephone'];
		} else {
			$THomephone = null;
		}
		$FileDataArray = array(
			'textname' => '家庭电话：',
			'name' => 'thomephone',
			'prompt' => '输入家庭电话',
			'prompttype' => 1,
			'required' => 1,
			'verificationtype' => 3,
			'default' => $THomephone,
			'mousedown' => '请输入座机电话、格式:010-00000000',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('thomephone', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		if(false == empty($TeacherDataArray['tmail'])) {
			$TMail = $TeacherDataArray['tmail'];
		} else {
			$TMail = null;
		}
		$FileDataArray = array(
			'textname' => '电子邮箱：',
			'name' => 'tmail',
			'prompt' => '输入常用邮箱',
			'prompttype' => 1,
			'required' => 1,
			'verificationtype' => 4,
			'default' => $TMail,
			'mousedown' => '请输入常用邮箱、格式:admin@admin.com',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('tmail', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		if(false == empty($TeacherDataArray['tsex'])) {
			$TSex = $TeacherDataArray['tsex'];
		} else {
			$TSex = null;
		}
		$SexConfiguration = array(
			'title' => '性　　别：',
			'selectid' => 'id',
			'selectname' => 'name',
			'name' => 'tsex',
			'width' => '180px',
			'required' => 1,
			'default' => $TSex,
		);
		$SexDataArray = array(
			array('id' => 1, 'name' => '保密',),
			array('id' => 2, 'name' => '女',),
			array('id' => 3, 'name' => '男',),
		);
		$SelectObj = new SelectModel($SexDataArray, $SexConfiguration);
		$this->assign('tsex', $SelectObj->GetSelectHtmlInfo());
		
		if(false == empty($TeacherDataArray['tbirthdate'])) {
			$TBirthdate = date('Y-m-d', $TeacherDataArray['tbirthdate']);
		} else {
			$TBirthdate = null;
		}
		$BirthDate = array(
			'default' => $TBirthdate,
			'datename' => '出生日期：',
			'name' => 'tbirthdate',
			'type' => 1,
			'width' => '180px',
			'required' => 1,
			'verification' => 1,
		);
		$SelectObj = new InputDateModel($BirthDate);
		$this->assign('tbirthdate', $SelectObj->GetInputDateHtmlInfo());
		
		/* 使用类型，url地址 */
		$this->assign('url', $Url);
		
		/* 教师id */
		if(false == empty($TeacherDataArray['tid'])) {
			$Tid = $TeacherDataArray['tid'];
		} else {
			$Tid = null;
		}
		$this->assign('tid', $Tid);
		
		$this->display('Teacher/teacherinterface');
	}
	
}
?>