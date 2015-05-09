<?php

/***
 *	copyright	:	Copyright (c) 2007-2013 SchoolCMS Inc. (http://www.schoolcms.cn/)
 *	license		:	http://www.schoolcms.cn/
 *	ContactQQ	:	1655098383
 *	E-mail		:	386392432@qq.com
 *	您可以在完全遵守本许可协议的基础上，将本软件应用于非商业用途，而不必支付软件版权许可费用。
 ***/

if(!$_SESSION['schoolcms_admin']['aname']) Header('Location:'.__ROOT__.'/admin.php?action=login');

class TeacherCurriculumAction extends CommonAction
{
	/*
		公共使用
	*/
	public function Index()
	{
		/* 判断网格的操作类型，1：编辑，2：删除 */
		if(false == empty($_REQUEST['type'])) {
			if($_REQUEST['type'] == 1) { 
				if(false == empty($_REQUEST['ttid'])) {
					$this->TeacherCurriculumEditorInfo($_REQUEST['ttid']);
				}
			} else if($_REQUEST['type'] == 2) {
				if(false == empty($_REQUEST['ttid'])) {
					$this->TeacherCurriculumDeleteInfo(array($_REQUEST['ttid']));
				}
				
			}
		}
		
		/* form 教师数据删除判断，如果不为空就是form提交的多个学员数据删除 */
		if(false == empty($_REQUEST['submitdelete'])) {
			if(false == empty($_REQUEST['checkbox'])) {
				$this->TeacherCurriculumDeleteInfo($_REQUEST['checkbox']);
			}
		}
	}
		
	/*
		教师课程管理
	*/
	public function TeacherCurriculumManagement()
	{
		date_default_timezone_set('Asia/Shanghai');
		/* 表前缀名 */
		$PREFIX = C('DB_PREFIX');
		
		/* 判断当前表是否存在 */
		$TableStatu = $this->TableName($PREFIX.'view_schoolteachers');
		
		/* 实例化表对象 */
		$TeacherCurriculumObj = M('view_schoolteachers');
		
		//调用初始化方法
		$this->TeacherCurriculumInitializationInfo();
		
		//调用查询条件处理方法
		$ConditionArray = $this->TeacherCurriculumInquiryCondition($_REQUEST);
		
		/* 没有视图的时候使用多表查询 */
		if(true == $TableStatu) {
			/* 按照条件获取数据的总条数 */
			$TeacherCurriculumCount = count($TeacherCurriculumObj->where($ConditionArray)->select());
		} else {
			$AchievementCountData = $TeacherCurriculumObj->query("select count(".$PREFIX."tcwct.ttid) AS ttid from (((((`".$PREFIX."tcwct` join `".$PREFIX."teacher` on((`".$PREFIX."tcwct`.`tid` = `".$PREFIX."teacher`.`tid`))) join `".$PREFIX."class` on((`".$PREFIX."tcwct`.`cid` = `".$PREFIX."class`.`cid`))) join `".$PREFIX."curriculum` on((`".$PREFIX."curriculum`.`cmid` = `".$PREFIX."tcwct`.`cmid`))) join `".$PREFIX."week` on((`".$PREFIX."week`.`wid` = `".$PREFIX."tcwct`.`wid`))) join `".$PREFIX."time` on((`".$PREFIX."tcwct`.`teid` = `".$PREFIX."time`.`teid`))) where (".$this->GetSqlSplice($ConditionArray)."(`".$PREFIX."time`.`testatu` = 1) and (`".$PREFIX."class`.`cstatu` = 1) and (`".$PREFIX."curriculum`.`cmstatu` = 1) and (`".$PREFIX."week`.`wstatu` = 1))");
			$TeacherCurriculumCount = $AchievementCountData[0]['ttid'];
		}
		
		/* 从基本设置表读取分页参数 */
		$BasicsetupObj = M('basicsetup');
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="teachercurriculumpage"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$TeacherCurriculumPage = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$TeacherCurriculumPage = 8;
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
			'total' => $TeacherCurriculumCount,
			'traction' => $TeacherCurriculumPage,  //显示条数
			'custompage' => $CustomPage,  //开启自定义跳转页码
			'detaildata' => $DetailData,  //开启详情数据
			'pagecoent' => 3,  //分页显示的个数
			'url' => './TeacherCurriculumManagement',
		);
		$PageingObj = new PagingModel($_REQUEST, $Configuration);
		$this->assign('pageing', $PageingObj->GetPagingHtmlInfo());

		/* 没有视图的时候使用多表查询 */
		if(true == $TableStatu) {
			//起始页码值和每页显示条数从分页模块里面获取
			$TeacherCurriculumData = $TeacherCurriculumObj->limit($PageingObj->GetStarting(), $PageingObj->GetFraction())->where($ConditionArray)->select();
		} else {
			$Limit = $PageingObj->GetStarting();
			$Limits = $PageingObj->GetFraction();
			$TeacherCurriculumData = $TeacherCurriculumObj->query("select `".$PREFIX."teacher`.`tname` AS `tname`,`".$PREFIX."class`.`cname` AS `cname`,`".$PREFIX."curriculum`.`cmname` AS `cmname`,`".$PREFIX."week`.`wname` AS `wname`,`".$PREFIX."time`.`tename` AS `tename`,`".$PREFIX."tcwct`.`ttid` AS `ttid`,`".$PREFIX."tcwct`.`tid` AS `tid`,`".$PREFIX."tcwct`.`cmid` AS `cmid`,`".$PREFIX."tcwct`.`wid` AS `wid`,`".$PREFIX."tcwct`.`cid` AS `cid` from (((((`".$PREFIX."tcwct` join `".$PREFIX."teacher` on((`".$PREFIX."tcwct`.`tid` = `".$PREFIX."teacher`.`tid`))) join `".$PREFIX."class` on((`".$PREFIX."tcwct`.`cid` = `".$PREFIX."class`.`cid`))) join `".$PREFIX."curriculum` on((`".$PREFIX."curriculum`.`cmid` = `".$PREFIX."tcwct`.`cmid`))) join `".$PREFIX."week` on((`".$PREFIX."week`.`wid` = `".$PREFIX."tcwct`.`wid`))) join `".$PREFIX."time` on((`".$PREFIX."tcwct`.`teid` = `".$PREFIX."time`.`teid`))) where (".$this->GetSqlSplice($ConditionArray)."(`".$PREFIX."time`.`testatu` = 1) and (`".$PREFIX."class`.`cstatu` = 1) and (`".$PREFIX."curriculum`.`cmstatu` = 1) and (`".$PREFIX."week`.`wstatu` = 1)) limit $Limit, $Limits");
		}
		
		/* 调用网格和CSV模块 */
		$this->assign('gridtable', $this->GetGridInitializationInfo($TeacherCurriculumData));
		$this->assign('subcsvexport', $this->GetCSVInitializationInfo());

		$this->display('TeacherCurriculum/teachercurriculummanagement');
	}
	
	/*
		初始化CSV方法
	*/
	public function GetCSVInitializationInfo()
	{
		$CsvExportObj = new CSVModel($this->GetFieldArray(), 'subcsvexport', '教师课程数据', './GetCSVInitializationInfo');
		//判断是不是到处CSV
		if(false == empty($_REQUEST['type'])) {
			if($_REQUEST['type'] == 'csvexport') {
				/* 表前缀名 */
				$PREFIX = C('DB_PREFIX');
				
				/* 判断当前表是否存在 */
				$TableStatu = $this->TableName($PREFIX.'view_schoolteachers');
				
				/* 实例化表对象 */
				$TeacherCurriculumObj = M('view_schoolteachers');
				
				/* 没有视图的时候使用多表查询 */
				if(true == $TableStatu) {
					$TeacherCurriculumData = $TeacherCurriculumObj->select();
				} else {
					$TeacherCurriculumData = $TeacherCurriculumObj->query("select `".$PREFIX."teacher`.`tname` AS `tname`,`".$PREFIX."class`.`cname` AS `cname`,`".$PREFIX."curriculum`.`cmname` AS `cmname`,`".$PREFIX."week`.`wname` AS `wname`,`".$PREFIX."time`.`tename` AS `tename`,`".$PREFIX."tcwct`.`ttid` AS `ttid`,`".$PREFIX."tcwct`.`tid` AS `tid`,`".$PREFIX."tcwct`.`cmid` AS `cmid`,`".$PREFIX."tcwct`.`wid` AS `wid`,`".$PREFIX."tcwct`.`cid` AS `cid` from (((((`".$PREFIX."tcwct` join `".$PREFIX."teacher` on((`".$PREFIX."tcwct`.`tid` = `".$PREFIX."teacher`.`tid`))) join `".$PREFIX."class` on((`".$PREFIX."tcwct`.`cid` = `".$PREFIX."class`.`cid`))) join `".$PREFIX."curriculum` on((`".$PREFIX."curriculum`.`cmid` = `".$PREFIX."tcwct`.`cmid`))) join `".$PREFIX."week` on((`".$PREFIX."week`.`wid` = `".$PREFIX."tcwct`.`wid`))) join `".$PREFIX."time` on((`".$PREFIX."tcwct`.`teid` = `".$PREFIX."time`.`teid`))) where ((`".$PREFIX."time`.`testatu` = 1) and (`".$PREFIX."class`.`cstatu` = 1) and (`".$PREFIX."curriculum`.`cmstatu` = 1) and (`".$PREFIX."week`.`wstatu` = 1))");
				}
				$CsvExportObj->CsvExportData($TeacherCurriculumData);  //处理页面传递过来的数据
			}
		} else {
			return $CsvExportObj->GetTitltDataHtml();
		}
	}
	
	/*
		初始化网格方法
	*/
	private function GetGridInitializationInfo($TeacherCurriculumData)
	{
		$Configuration = array(
			'id' => 'ttid',
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
		$GridObj = new GridModel($TeacherCurriculumData, $this->GetFieldArray(), $Configuration, $Operating);
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
			'cname' => '班级',
			'cmname' => '科目',
			'wname' => '周天',
			'tename' => '时段',
		);
	}
	
	/*
		教师课程信息查询条件处理方法
	*/
	private function TeacherCurriculumInquiryCondition($Inquiry = array())
	{
		/* 设置当前时间地区 */
		date_default_timezone_set('Asia/Shanghai');
		/* 条件查询拼接 */
		$ConditionArray = array();
		if(false == empty($Inquiry)) {
			foreach($Inquiry as $Ckey=>$Cvalue) {
				if((false == empty($Cvalue)) && (false == is_array($Ckey))) {
					switch($Ckey) {
						//教师姓名
						case 'tname':
							$ConditionArray['tname'] = array('like','%'.$Cvalue.'%');
						break;
						//班级
						case 'cid':
							$ConditionArray['cid'] = intval($Cvalue);
						break;
						//科目
						case 'cmid':
							$ConditionArray['cmid'] = intval($Cvalue);
						break;
						//时段
						case 'teid':
							$ConditionArray['teid'] = intval($Cvalue);
						break;
						//周天
						case 'wid':
							$ConditionArray['wid'] = intval($Cvalue);
						break;


						default:
						//没有这个条件
					}
				}
			}
		}
		
		return $ConditionArray;
	}
	
	/*
		条件查询初始化方法
	*/
	private function TeacherCurriculumInitializationInfo()
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
		
		if(false == empty($_REQUEST['cid'])) {
			$Cid = $_REQUEST['cid'];
		} else {
			$Cid = null;
		}
		$ClassObj = M('class');
		$ClassData = $ClassObj->select();
		$Configuration = array(
			'title' => '班级：',
			'selectid' => 'cid',
			'selectname' => 'cname',
			'name' => 'cid',
			'width' => '180px',
			'default' => $Cid,
			'width' => '100px',
			'whole' => '全部',
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('cid', $SelectObj->GetSelectHtmlInfo());
		
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
			'default' => $CmID,
			'width' => '100px',
			'whole' => '全部',
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('cmid', $SelectObj->GetSelectHtmlInfo());
		
		if(false == empty($_REQUEST['wid'])) {
			$WID = $_REQUEST['wid'];
		} else {
			$WID = null;
		}
		$DataObj = M('week');
		$ClassData = $DataObj->select();
		$Configuration = array(
			'title' => '周天：',
			'selectid' => 'wid',
			'selectname' => 'wname',
			'name' => 'wid',
			'default' => $WID,
			'width' => '100px',
			'whole' => '全部',
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('wid', $SelectObj->GetSelectHtmlInfo());
		
		if(false == empty($_REQUEST['teid'])) {
			$TeID = $_REQUEST['teid'];
		} else {
			$TeID = null;
		}
		$DataObj = M('time');
		$ClassData = $DataObj->select();
		$Configuration = array(
			'title' => '时段：',
			'selectid' => 'teid',
			'selectname' => 'tename',
			'name' => 'teid',
			'default' => $TeID,
			'width' => '100px',
			'whole' => '全部',
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('teid', $SelectObj->GetSelectHtmlInfo());
	}
	
	/*
		教师课程编辑方法
	*/
	public function TeacherCurriculumEditorInfo($TeacherCurriculumID = null)
	{
		date_default_timezone_set('Asia/Shanghai');
		$TeacherCurriculumObj = M('tcwct');
		if(false == empty($_POST['submit'])) {
			
			/* 将学员出生日期转换为时间戳 */
			$_POST['tbirthdate'] = strtotime($_POST['tbirthdate']);

			/* 教师课程id */
			if(false == empty($_POST['ttid'])) {
				$Ttid = $_POST['ttid'];
			} else {
				$Ttid = null;
			}
			/* 删除当前数组中的submit和tid键值对 */
			unset($_POST['submit']);
			unset($_POST['tid']);
			
			/* 更新数据操作 */
			$Statu = $TeacherCurriculumObj->where('ttid='.$Ttid)->save($_POST);
			if($Statu) {
				$this->SchoolCMSPrompt('更新成功！', 'TeacherCurriculumManagement');
			} else {
				$this->SchoolCMSPrompt('更新失败！', 'TeacherCurriculumManagement');
			}
		} else {
			if(false == empty($TeacherCurriculumID)) {
				$TeacherCurriculumData = $TeacherCurriculumObj->select(intval($TeacherCurriculumID));
				$this->TeacherCurriculumInterfaceInfo('./TeacherCurriculumEditorInfo', $TeacherCurriculumData[0]['tid'], $TeacherCurriculumData[0]);
			}
		}
	}
	
		/*
		删除教师课程数据方法（支持批量处理）一维数组
	*/
	public function TeacherCurriculumDeleteInfo($TeacherArraySID)
	{
		//判断id是数组还是一个，implode 将一个数组的键值拼接成一个字符串
		if((true == is_array($TeacherArraySID)) && (false == empty($TeacherArraySID))){
			$TeacherCurriculumObj = M('tcwct');

			$Where = 'ttid in('.implode(',', $TeacherArraySID).')';
			
			/* 删除教师课程数据 */
			$Statu = $TeacherCurriculumObj->where($Where)->delete();

			/* 删除状态 */
			if(true == $Statu) {
				$this->SchoolCMSPrompt("成功删除 {$Statu} 条！");
			} else {
				$this->SchoolCMSPrompt('删除失败！');
			}
		}
	}
	
	/*
		新增教师课程方法
	*/
	public function TeacherCurriculumAddInfo()
	{
		if(false == empty($_POST['submit'])) {
			/* 删除当前数组中的submit和tid键值对 */
			unset($_POST['submit']);
			unset($_POST['ttid']);

			/* 新增数据操作 */
			$TeacherCurriculumObj = M('tcwct');
			$Statu = $TeacherCurriculumObj->add($_POST);
			if($Statu) {
				$this->SchoolCMSPrompt('新增成功！', 'TeacherCurriculumManagement');
			} else {
				$this->SchoolCMSPrompt('新增失败！', 'TeacherCurriculumManagement');
			}
		} else {
			if(false == empty($_REQUEST['tid'])) {
				$TID = $_REQUEST['tid'];
			} else {
				$TID = null;
			}
			$this->TeacherCurriculumInterfaceInfo('./TeacherCurriculumAddInfo', $TID);
		}
	}
	
	/*
		新增教师课程和编辑数据界面方法
	*/
	private function TeacherCurriculumInterfaceInfo($Url, $TID, $TeacherCurriculumDataArray = array())
	{
		if(false == empty($TeacherCurriculumDataArray['cid'])) {
			$Cid = $TeacherCurriculumDataArray['cid'];
		} else {
			$Cid = null;
		}
		$ClassObj = M('class');
		$ClassData = $ClassObj->select();
		$Configuration = array(
			'title' => '班级：',
			'selectid' => 'cid',
			'selectname' => 'cname',
			'name' => 'cid',
			'width' => '180px',
			'required' => 1,
			'default' => $Cid,
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('cid', $SelectObj->GetSelectHtmlInfo());
		
		if(false == empty($TeacherCurriculumDataArray['cmid'])) {
			$CmID = $TeacherCurriculumDataArray['cmid'];
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
			'default' => $CmID,
			'required' => 1,
			'width' => '180px',
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('cmid', $SelectObj->GetSelectHtmlInfo());
		
		if(false == empty($TeacherCurriculumDataArray['wid'])) {
			$WID = $TeacherCurriculumDataArray['wid'];
		} else {
			$WID = null;
		}
		$DataObj = M('week');
		$ClassData = $DataObj->select();
		$Configuration = array(
			'title' => '周天：',
			'selectid' => 'wid',
			'selectname' => 'wname',
			'name' => 'wid',
			'default' => $WID,
			'required' => 1,
			'width' => '180px',
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('wid', $SelectObj->GetSelectHtmlInfo());
		
		if(false == empty($TeacherCurriculumDataArray['teid'])) {
			$TeID = $TeacherCurriculumDataArray['teid'];
		} else {
			$TeID = null;
		}
		$DataObj = M('time');
		$ClassData = $DataObj->select();
		$Configuration = array(
			'title' => '时段：',
			'selectid' => 'teid',
			'selectname' => 'tename',
			'name' => 'teid',
			'default' => $TeID,
			'required' => 1,
			'width' => '180px',
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('teid', $SelectObj->GetSelectHtmlInfo());

		
		/* 使用类型，url地址 */
		$this->assign('url', $Url);
		
		/* 教师课程id */
		if(false == empty($TeacherCurriculumDataArray['ttid'])) {
			$Ttid = $TeacherCurriculumDataArray['ttid'];
		} else {
			$Ttid = null;
		}
		$this->assign('ttid', $Ttid);
		
		/* 教师名称查询 */
		if(false == empty($TID)) {
			$TeacherObj = M('teacher');
			$TeacherData = $TeacherObj->field('tname')->select($TID);
			if(false == empty($TeacherData[0]['tname'])) {
				$TName = $TeacherData[0]['tname'];
			} else {
				$TName = null;
			}
			$this->assign('tname', $TName);
			$this->assign('tid', $TID);
		}
		
		$this->display('TeacherCurriculum/teachercurriculuminterface');
	}
	
}
?>