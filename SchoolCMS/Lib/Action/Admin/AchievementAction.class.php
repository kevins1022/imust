<?php

/***
 *	copyright	:	Copyright (c) 2007-2013 SchoolCMS Inc. (http://www.schoolcms.cn/)
 *	license		:	http://www.schoolcms.cn/
 *	ContactQQ	:	1655098383
 *	E-mail		:	386392432@qq.com
 *	您可以在完全遵守本许可协议的基础上，将本软件应用于非商业用途，而不必支付软件版权许可费用。
 ***/

if(!$_SESSION['schoolcms_admin']['aname']) Header('Location:'.__ROOT__.'/admin.php?action=login');

class AchievementAction extends CommonAction
{
	public function Index()
	{
		/* 判断是否是成绩录入操作, 3：添加，2：删除，1：编辑 */
		if(false == empty($_REQUEST['type'])) {
			/* 数据从学员管理，录数据操作传递过来的 */
			if($_REQUEST['type'] == 3) { 
				if(false == empty($_REQUEST['sid'])) {
					$this->AchievementAddInfo($_REQUEST['sid']);
				}
				
			/* 数据从学员成绩管理，编辑操作传递过来的 */
			} else if($_REQUEST['type'] == 1) {
				if(false == empty($_REQUEST['atid'])) {
					$this->AchievementEditorInfo($_REQUEST['atid']);
				}
				
			/* 数据从学员成绩管理，删除操作传递过来的 */
			} else if($_REQUEST['type'] == 2) {
				if(false == empty($_REQUEST['atid'])) {
					$this->AchievementDeleteInfo(array($_REQUEST['atid']));
				}
				
			}
		}
		
		/* form 学员成绩数据删除判断，如果不为空就是form提交的多个学员数据删除 */
		if(false == empty($_REQUEST['submitdelete'])) {
			if(false == empty($_REQUEST['checkbox'])) {
				$this->AchievementDeleteInfo($_REQUEST['checkbox']);
			}
		}
	}
	
	/*
		学员成绩管理
	*/
	public function AchievementMt()
	{
		/* 表前缀名 */
		$PREFIX = C('DB_PREFIX');
		
		/* 判断当前表是否存在 */
		$TableStatu = $this->TableName($PREFIX.'view_studentachievement');
		
		/* 实例化表对象 */
		$AchievementObj = M('view_studentachievement');
		
		//调用初始化方法
		$this->AchievementInitializationInfo();
		
		//调用查询条件处理方法
		$ConditionArray = $this->AchievementInquiryCondition($_REQUEST);
		
		/* 没有视图的时候使用多表查询 */
		if(true == $TableStatu) {
			/* 按照条件获取数据的总条数 */
			$AchievementCount = count($AchievementObj->where($ConditionArray)->select());
		} else {
			$AchievementCountData = $AchievementObj->query("select count(".$PREFIX."achievement.atid) AS atid from (((((`".$PREFIX."achievement` join `".$PREFIX."achievementclass` on((`".$PREFIX."achievement`.`atcid` = `".$PREFIX."achievementclass`.`atcid`))) join `".$PREFIX."curriculum` on((`".$PREFIX."curriculum`.`cmid` = `".$PREFIX."achievement`.`cmid`))) join `".$PREFIX."student` on((`".$PREFIX."student`.`sid` = `".$PREFIX."achievement`.`sid`))) join `".$PREFIX."class` on((`".$PREFIX."class`.`cid` = `".$PREFIX."student`.`cid`))) join `".$PREFIX."semester` on((`".$PREFIX."achievement`.`srid` = `".$PREFIX."semester`.`srid`))) where (".$this->GetSqlSplice($ConditionArray)."(`".$PREFIX."curriculum`.`cmstatu` = 1) and (`".$PREFIX."achievement`.`atstatu` = 1) and (`".$PREFIX."class`.`cstatu` = 1) and (`".$PREFIX."semester`.`srstatu` = 1) and (`".$PREFIX."achievementclass`.`atcstatu` = 1))");
			$AchievementCount = $AchievementCountData[0]['atid'];
		}
		
		/* 从基本设置表读取分页参数 */
		$BasicsetupObj = M('basicsetup');
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="achievementpage"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$AchievementPage = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$AchievementPage = 8;
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
			'total' => $AchievementCount,
			'traction' => $AchievementPage,  //显示条数
			'custompage' => $CustomPage,  //开启自定义跳转页码
			'detaildata' => $DetailData,  //开启详情数据
			'pagecoent' => 3,  //分页显示的个数
			'url' => './AchievementMt',
		);
		$PageingObj = new PagingModel($_REQUEST, $Configuration);
		$this->assign('pageing', $PageingObj->GetPagingHtmlInfo());

		/* 没有视图的时候使用多表查询 */
		if(true == $TableStatu) {
			/* 起始页码值和每页显示条数从分页模块里面获取 */
			$AchievementData = $AchievementObj->limit($PageingObj->GetStarting(), $PageingObj->GetFraction())->where($ConditionArray)->select();
		} else {
			$Limit = $PageingObj->GetStarting();
			$Limits = $PageingObj->GetFraction();
			$AchievementData = $AchievementObj->query("select `".$PREFIX."achievementclass`.`atcname` AS `atcname`,`".$PREFIX."achievement`.`atfraction` AS `atfraction`,`".$PREFIX."achievement`.`sid` AS `sid`,`".$PREFIX."curriculum`.`cmname` AS `cmname`,`".$PREFIX."student`.`sname` AS `sname`,`".$PREFIX."student`.`ssex` AS `ssex`,`".$PREFIX."class`.`cname` AS `cname`,`".$PREFIX."student`.`cid` AS `cid`,`".$PREFIX."achievement`.`atcid` AS `atcid`,`".$PREFIX."achievement`.`cmid` AS `cmid`,`".$PREFIX."achievement`.`atid` AS `atid`,`".$PREFIX."achievement`.`atscoretype` AS `atscoretype`,`".$PREFIX."student`.`sbirthdate` AS `sbirthdate`,`".$PREFIX."achievement`.`srid` AS `srid`,`".$PREFIX."semester`.`srname` AS `srname` from (((((`".$PREFIX."achievement` join `".$PREFIX."achievementclass` on((`".$PREFIX."achievement`.`atcid` = `".$PREFIX."achievementclass`.`atcid`))) join `".$PREFIX."curriculum` on((`".$PREFIX."curriculum`.`cmid` = `".$PREFIX."achievement`.`cmid`))) join `".$PREFIX."student` on((`".$PREFIX."student`.`sid` = `".$PREFIX."achievement`.`sid`))) join `".$PREFIX."class` on((`".$PREFIX."class`.`cid` = `".$PREFIX."student`.`cid`))) join `".$PREFIX."semester` on((`".$PREFIX."achievement`.`srid` = `".$PREFIX."semester`.`srid`))) where (".$this->GetSqlSplice($ConditionArray)."(`".$PREFIX."curriculum`.`cmstatu` = 1) and (`".$PREFIX."achievement`.`atstatu` = 1) and (`".$PREFIX."class`.`cstatu` = 1) and (`".$PREFIX."semester`.`srstatu` = 1) and (`".$PREFIX."achievementclass`.`atcstatu` = 1)) limit $Limit, $Limits");
		}
		
		$AchievementData = $this->GetAchievementData($AchievementData);
		
		/* 调用网格和CSV模块 */
		$this->assign('gridtable', $this->GetGridInitializationInfo($AchievementData));
		$this->assign('subcsvexport', $this->GetCSVInitializationInfo());
		
		$this->display('Achievement/achievementmt');
	}
	
	/*
		初始化CSV方法
	*/
	public function GetCSVInitializationInfo()
	{		
		$CsvExportObj = new CSVModel($this->GetFieldArray(), 'subcsvexport', '学员成绩数据', './GetCSVInitializationInfo');
		//判断是不是到处CSV
		if(false == empty($_REQUEST['type'])) {
			if($_REQUEST['type'] == 'csvexport') {
				/* 表前缀名 */
				$PREFIX = C('DB_PREFIX');
				
				/* 判断当前表是否存在 */
				$TableStatu = $this->TableName($PREFIX.'view_studentachievement');
				
				/* 实例化表对象 */
				$AchievementObj = M('view_studentachievement');
				
				/* 没有视图的时候使用多表查询 */
				if(true == $TableStatu) {
					$AchievementData = $AchievementObj->select();
				} else {
					$AchievementData = $AchievementObj->query("select `".$PREFIX."achievementclass`.`atcname` AS `atcname`,`".$PREFIX."achievement`.`atfraction` AS `atfraction`,`".$PREFIX."achievement`.`sid` AS `sid`,`".$PREFIX."curriculum`.`cmname` AS `cmname`,`".$PREFIX."student`.`sname` AS `sname`,`".$PREFIX."student`.`ssex` AS `ssex`,`".$PREFIX."class`.`cname` AS `cname`,`".$PREFIX."student`.`cid` AS `cid`,`".$PREFIX."achievement`.`atcid` AS `atcid`,`".$PREFIX."achievement`.`cmid` AS `cmid`,`".$PREFIX."achievement`.`atid` AS `atid`,`".$PREFIX."achievement`.`atscoretype` AS `atscoretype`,`".$PREFIX."student`.`sbirthdate` AS `sbirthdate`,`".$PREFIX."achievement`.`srid` AS `srid`,`".$PREFIX."semester`.`srname` AS `srname` from (((((`".$PREFIX."achievement` join `".$PREFIX."achievementclass` on((`".$PREFIX."achievement`.`atcid` = `".$PREFIX."achievementclass`.`atcid`))) join `".$PREFIX."curriculum` on((`".$PREFIX."curriculum`.`cmid` = `".$PREFIX."achievement`.`cmid`))) join `".$PREFIX."student` on((`".$PREFIX."student`.`sid` = `".$PREFIX."achievement`.`sid`))) join `".$PREFIX."class` on((`".$PREFIX."class`.`cid` = `".$PREFIX."student`.`cid`))) join `".$PREFIX."semester` on((`".$PREFIX."achievement`.`srid` = `".$PREFIX."semester`.`srid`))) where ((`".$PREFIX."curriculum`.`cmstatu` = 1) and (`".$PREFIX."achievement`.`atstatu` = 1) and (`".$PREFIX."class`.`cstatu` = 1) and (`".$PREFIX."semester`.`srstatu` = 1) and (`".$PREFIX."achievementclass`.`atcstatu` = 1))");
				}
				$CsvExportObj->CsvExportData($this->GetAchievementData($AchievementData));  //处理页面传递过来的数据
			}
		} else {
			return $CsvExportObj->GetTitltDataHtml();
		}
	}
	
	/*
		调用网格初始化
	*/
	private function GetGridInitializationInfo($AchievementData)
	{
		$Configuration = array(
			'id' => 'atid',
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
		);
		$GridObj = new GridModel($AchievementData, $this->GetFieldArray(), $Configuration, $Operating);
		return $GridObj->GetGridHtmlInfo();
	}
	
	/*
		数据字段数组方法
	*/
	private function GetFieldArray()
	{
		return $FieldArray = array(
			'sname' => '姓名',
			'sage' => '年龄',
			'ssex' => '性别',
			'cname' => '班级',
			'cmname' => '科目',
			'atfraction' => '分数',
			'atscoretype' => '类型',
			'atcname' => '期号',
		);
	}
	
	/*
		最终数据处理方法
	*/
	private function GetAchievementData($AchievementData)
	{
		if(false == empty($AchievementData) && true == is_array($AchievementData)) {
			foreach($AchievementData as $SKey=>$SValue) {
				foreach($SValue as $SKeys=>$SValues) {
					if($SKeys == 'atfraction') {
						$SValue['atfraction'] = $SValues.' 分';
					}
					
					/* 获取当前时间减去数据库的出生日期等于学员的实际年龄 */
					if($SKeys == 'sbirthdate') {
						$SValue['sage'] = date('Y-m-d', time())-date('Y-m-d', $SValues).' 周岁';
					}
					if($SKeys == 'ssex') {
						switch($SValues) {
							//保密
							case 1:
								$Ssex = '保密';
							break;
							//女
							case 2:
								$Ssex = '女';
							break;
							//男
							case 3:
								$Ssex = '男';
							break;

							default:
							//这里报错，没有这个条件
						}
						$SValue['ssex'] = $Ssex;
					}
					
					if($SKeys == 'atscoretype') {
						switch($SValues) {
							//差
							case 1:
								$AtScoreType = '<span style="color:#F00;">差</span>';
							break;
							//较差
							case 2:
								$AtScoreType = '<span style="color:#F00;">较差</span>';
							break;
							//中
							case 3:
								$AtScoreType = '中';
							break;
							//良
							case 4:
								$AtScoreType = '良';
							break;
							//优
							case 5:
								$AtScoreType = '优';
							break;
							//未知
							case 6:
								$AtScoreType = '未知';
							break;

							default:
							//这里报错，没有这个条件
						}
						$SValue['atscoretype'] = $AtScoreType;
					}
				}
				$AchievementData[$SKey] = $SValue;
			}
		}
		return $AchievementData;
	}
	
	/*
		学员成绩管理初始化方法
	*/
	private function AchievementInitializationInfo()
	{
		if(false == empty($_REQUEST['sname'])) {
			$SName = $_REQUEST['sname'];
		} else {
			$SName = null;
		}
		$FullName = array(
			'textname' => '姓名：',
			'name' => 'sname',
			'width' => '100px',
			'default' => $SName,
		);
		$FullNameObj = new InputTextModel($FullName);
		$this->assign('sname', $FullNameObj->GetInputTextHtmlInfo());
		
		if(false == empty($_REQUEST['cid'])) {
			$Cid = $_REQUEST['cid'];
		} else {
			$Cid = null;
		}
		$DataObj = M('class');
		$ClassData = $DataObj->select();
		$Configuration = array(
			'title' => '班级：',
			'selectid' => 'cid',
			'selectname' => 'cname',
			'name' => 'cid',
			'whole' => '全部',
			'width' => '100px',
			'default' => $Cid,
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('cid', $SelectObj->GetSelectHtmlInfo());
		
		
		if(false == empty($_REQUEST['atcid'])) {
			$AtcID = $_REQUEST['atcid'];
		} else {
			$AtcID = null;
		}
		$DataObj = M('achievementclass');
		$ClassData = $DataObj->select();
		$Configuration = array(
			'title' => '期号：',
			'selectid' => 'atcid',
			'selectname' => 'atcname',
			'name' => 'atcid',
			'whole' => '全部',
			'width' => '100px',
			'default' => $AtcID,
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('atcid', $SelectObj->GetSelectHtmlInfo());
		
		
		if(false == empty($_REQUEST['cmid'])) {
			$CmID = $_REQUEST['cmid'];
		} else {
			$CmID = null;
		}
		$DataObj = M('curriculum');
		$ClassData = $DataObj->select();
		$Configuration = array(
			'title' => '科目：',
			'selectid' => 'cmid',
			'selectname' => 'cmname',
			'name' => 'cmid',
			'whole' => '全部',
			'width' => '100px',
			'default' => $CmID,
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('cmid', $SelectObj->GetSelectHtmlInfo());

		
		if(false == empty($_REQUEST['atscoretype'])) {
			$AtScoreType = $_REQUEST['atscoretype'];
		} else {
			$AtScoreType = null;
		}
		$Configuration = array(
			'title' => '类型：',
			'selectid' => 'id',
			'selectname' => 'name',
			'name' => 'atscoretype',
			'whole' => '全部',
			'width' => '100px',
			'default' => $AtScoreType,
		);
		$DataArray = array(
			array('id' => 1, 'name' => '差',),
			array('id' => 2, 'name' => '较差',),
			array('id' => 3, 'name' => '中',),
			array('id' => 4, 'name' => '良',),
			array('id' => 5, 'name' => '优',),
			array('id' => 6, 'name' => '未知',),
		);
		$SelectObj = new SelectModel($DataArray, $Configuration);
		$this->assign('atscoretype', $SelectObj->GetSelectHtmlInfo());
		
		
		if(false == empty($_REQUEST['atfraction'])) {
			$ATfraction = $_REQUEST['atfraction'];
		} else {
			$ATfraction = null;
		}
		$FullName = array(
			'textname' => '分数：',
			'name' => 'atfraction',
			'width' => '36px',
			'default' => $ATfraction,
		);
		$FullNameObj = new InputTextModel($FullName);
		$this->assign('atfraction', $FullNameObj->GetInputTextHtmlInfo());
		
		if(false == empty($_REQUEST['atfractions'])) {
			$ATfractions = $_REQUEST['atfractions'];
		} else {
			$ATfractions = null;
		}
		$FullName = array(
			'textname' => ' 至 ',
			'name' => 'atfractions',
			'width' => '36px',
			'default' => $ATfractions,
		);
		$FullNameObj = new InputTextModel($FullName);
		$this->assign('atfractions', $FullNameObj->GetInputTextHtmlInfo());
		
	}
	
	/*
		学员成绩管理条件处理
	*/
	private function AchievementInquiryCondition($Inquiry = array())
	{
		//条件查询拼接
		$ConditionArray = array();
		$ConditionArrayAtfraction = array();
		if(false == empty($Inquiry)) {
			foreach($Inquiry as $AKey=>$AValue) {
				if((false == empty($AValue)) && (false == is_array($AKey))) {
					switch($AKey) {
						//学生姓名
						case 'sname':
							$ConditionArray['sname'] = array('like','%'.$AValue.'%');
						break;
						//期号
						case 'atcid':
							$ConditionArray['atcid'] = intval($AValue);
						break;
						//班级
						case 'cid':
							$ConditionArray['cid'] = intval($AValue);
						break;
						//科目
						case 'cmid':
							$ConditionArray['cmid'] = intval($AValue);
						break;
						//分数类型
						case 'atscoretype':
							$ConditionArray['atscoretype'] = intval($AValue);
						break;
						//分数起始
						case 'atfraction':
							if(0 != intval($AValue)) {
								array_push($ConditionArrayAtfraction, array('egt', intval($AValue)));
							}
						break;
						//分数终止
						case 'atfractions':
							if(0 != intval($AValue)) {
								array_push($ConditionArrayAtfraction, array('elt', intval($AValue)));
							}
						break;
						
						default:
						//没有这个条件
					}
				}
			}
		}
		
		if(false == empty($ConditionArrayAtfraction)) {
			$ConditionArray['atfraction'] = $ConditionArrayAtfraction;
		}
		
		/* 从基本设置表读取参数，根据学期号读取数据 */
		$BasicsetupObj = M('basicsetup');
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="semester"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$ConditionArray['srid'] = intval($BasicsetupData[0]['bdatavalue']);
		}
		
		return $ConditionArray;
	}
	
	/*
		学员成绩录入界面显示和插入数据方法
	*/
	public function AchievementAddInfo($SID = null)
	{
		if(false == empty($_POST['submit'])) {
			/* 删除数组中的submit和type数据 */
			unset($_POST['submit']);
			unset($_POST['type']);
			unset($_POST['atid']);
			
			/* 调用分数类型处理方法，返回类型状态值 */
			$AtScoretype = $this->GetAtFractionSplice($_POST['atfraction']);
			if(false == empty($AtScoretype)) {
				$_POST['atscoretype'] = $AtScoretype;
			}
			
			/* 新增学员成绩操作 */
			$StudentObj = M('achievement');
			$Statu = $StudentObj->add($_POST);
			if($Statu) {
				$this->SchoolCMSPrompt('新增成功！', '../Student/StudentManagement');
			} else {
				$this->SchoolCMSPrompt('新增失败！', '../Student/StudentManagement');
			}
		} else {
			if(false == empty($SID)) {
				$this->AchievementInterfaceInfo('./AchievementAddInfo', $SID);
			}
		}
	}
	
	/*
		学员成绩编辑界面显示和更新数据方法
	*/
	public function AchievementEditorInfo($AtID = null)
	{
		if(false == empty($_POST['submit'])) {
			$Atid = $_POST['atid'];
			/* 删除数组中的submit和type,sid数据 */
			unset($_POST['submit']);
			unset($_POST['type']);
			unset($_POST['sid']);
			unset($_POST['atid']);
			
			/* 调用分数类型处理方法，返回类型状态值 */
			$AtScoretype = $this->GetAtFractionSplice($_POST['atfraction']);
			if(false == empty($AtScoretype)) {
				$_POST['atscoretype'] = $AtScoretype;
			}
			
			/* 更新学员成绩操作 */
			$StudentObj = M('achievement');
			$Statu = $StudentObj->where('atid='.$Atid)->save($_POST);
			if($Statu) {
				$this->SchoolCMSPrompt('更新成功！', 'AchievementMt');
			} else {
				$this->SchoolCMSPrompt('新更新失败！', 'AchievementMt');
			}
		} else {
			if(false == empty($AtID)) {
				$AchievementObj = M('achievement');
				$AchievementData = $AchievementObj->select($AtID);
				if(false == empty($AchievementData[0])) {
					$this->AchievementInterfaceInfo('./AchievementEditorInfo', $AchievementData[0]['sid'], $AchievementData[0]);
				}
			}
		}
		
	}
	
	/*
		学员成绩删除方法
	*/
	private function AchievementDeleteInfo($AchievementArraySID = array())
	{
		//判断id是数组还是一个，implode 将一个数组的键值拼接成一个字符串
		if(true == is_array($AchievementArraySID)){
			$Where = 'atid in('.implode(',',$AchievementArraySID).')';
		}else{
			$Where = 'atid='.$AchievementArraySID;
		}
		$AchievementDeleteObj = M('achievement');
		$Statu = $AchievementDeleteObj->where($Where)->delete();

		 /* 删除状态 */
		if(true == $Statu) {
			$this->SchoolCMSPrompt("成功删除{$Statu}条！");
		} else {
			$this->SchoolCMSPrompt('删除失败！');
		}
	}
	
	/*
		学员成绩类型处理
	*/
	private function GetAtFractionSplice($AtFraction)
	{
		/* 从基本设置表读取分数类型参数，如果数据库没有的就使用默认的计算 */
		$Poor = 20;
		$CyPoor = 40;
		$Medium = 60;
		$Good = 80;
		$Excellent = 100;
		$BasicsetupObj = M('basicsetup');
		$BasicsetupData = $BasicsetupObj->field('bdatavalue, btype')->where('bmark="achievement"')->select();
		if(false == empty($BasicsetupData)) {
			foreach($BasicsetupData as $BKey=>$BValue) {
				if((false == empty($BValue['btype'])) && (false == empty($BValue['bdatavalue']))) {
					switch($BValue['btype']) {
						//差
						case 'poor':
							$Poor = intval($BValue['bdatavalue']);
						break;
						//较差
						case 'cypoor':
							$CyPoor = intval($BValue['bdatavalue']);
						break;
						//中
						case 'medium':
							$Medium = intval($BValue['bdatavalue']);
						break;
						//良
						case 'good':
							$Good = intval($BValue['bdatavalue']);
						break;
						//优
						case 'excellent':
							$Excellent = intval($BValue['bdatavalue']);
						break;
						default:
						//没有这个条件
					}
				}
			}
		}
		/* 如果计算出错，就使用默认的参数（6：未知） */
		$AtScoretype = 6;
		$AtFractionS = max(0, (false == empty($AtFraction) ? intval($AtFraction) : 0));
		if(($AtFractionS > 0) && ($AtFractionS <= $Poor)) {
			$AtScoretype = 1;
		} else if(($AtFractionS > $Poor) && ($AtFractionS <= $CyPoor)) {
			$AtScoretype = 2;
		} else if(($AtFractionS > $CyPoor) && ($AtFractionS <= $Medium)) {
			$AtScoretype = 3;
		} else if(($AtFractionS > $Medium) && ($AtFractionS <= $Good)) {
			$AtScoretype = 4;
		} else if(($AtFractionS > $Good) && ($AtFractionS <= $Excellent) || ($AtFractionS > $Excellent)) {
			$AtScoretype = 5;
		}
		return $AtScoretype;
	}
	
	/*
		学员成绩添加和编辑显示方法
	*/
	private function AchievementInterfaceInfo($Url, $SID, $AchievementArray = array())
	{
		if(false == empty($StudentDataArray['srid'])) {
			$Srid = $StudentDataArray['srid'];
		} else {
			/* 从基本设置表读取参数 */
			$BasicsetupObj = M('basicsetup');
			$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="semester"')->select();
			if(false == empty($BasicsetupData[0]['bdatavalue'])) {
				$Srid = intval($BasicsetupData[0]['bdatavalue']);
			} else {
				$Srid = null;
			}
		}
		$SemesterObj = M('semester');
		$SemesterData = $SemesterObj->select();
		$Configuration = array(
			'title' => '学期：',
			'selectid' => 'srid',
			'selectname' => 'srname',
			'name' => 'srid',
			'width' => '180px',
			'required' => 1,
			'default' => $Srid,
			'ban' => 1,
		);
		$SelectObj = new SelectModel($SemesterData, $Configuration);
		$this->assign('srid', $SelectObj->GetSelectHtmlInfo());
		
		if(false == empty($AchievementArray['atcid'])) {
			$AtcID = $AchievementArray['atcid'];
		} else {
			$AtcID = null;
		}
		$DataObj = M('achievementclass');
		$ClassData = $DataObj->select();
		$Configuration = array(
			'title' => '期号：',
			'selectid' => 'atcid',
			'selectname' => 'atcname',
			'name' => 'atcid',
			'width' => '180px',
			'default' => $AtcID,
			'required' => 1,
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('atcid', $SelectObj->GetSelectHtmlInfo());
		
		if(false == empty($AchievementArray['cmid'])) {
			$CmID = $AchievementArray['cmid'];
		} else {
			$CmID = null;
		}
		$DataObj = M('curriculum');
		$ClassData = $DataObj->select();
		$Configuration = array(
			'title' => '科目：',
			'selectid' => 'cmid',
			'selectname' => 'cmname',
			'name' => 'cmid',
			'width' => '180px',
			'default' => $CmID,
			'required' => 1,
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('cmid', $SelectObj->GetSelectHtmlInfo());

		
		if(false == empty($AchievementArray['atfraction'])) {
			$ATfraction = $AchievementArray['atfraction'];
		} else {
			$ATfraction = null;
		}
		$FullName = array(
			'textname' => '分数：',
			'name' => 'atfraction',
			'default' => $ATfraction,
			'prompttype' => 1,
			'verificationtype' => 1,
			'lengthsmall' => 1,
			'lengthlarge' => 3,
			'required' => 1,
			'mousedown' => '请输入1至3位之间的数字！',
		);
		$FullNameObj = new InputTextModel($FullName);
		$this->assign('atfraction', $FullNameObj->GetInputTextHtmlInfo());
		
		
		$StudentObj = M('student');
		$StudentData = $StudentObj->field('sname')->select($SID);
		if(false == empty($StudentData[0]['sname'])) {
			$this->assign('sid', $SID);
			$this->assign('sname', $StudentData[0]['sname']);
		} else {
			$this->assign('sname', null);
		}
		
		if(false == empty($AchievementArray['atid'])) {
			$this->assign('atid', $AchievementArray['atid']);
		} else {
			$this->assign('atid', null);
		}
		$this->assign('url', $Url);
		
		$this->display('Achievement/achievementmtadd');
	}
	
}
?>