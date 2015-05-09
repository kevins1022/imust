<?php

/***
 *	copyright	:	Copyright (c) 2007-2013 SchoolCMS Inc. (http://www.schoolcms.cn/)
 *	license		:	http://www.schoolcms.cn/
 *	ContactQQ	:	1655098383
 *	E-mail		:	386392432@qq.com
 *	您可以在完全遵守本许可协议的基础上，将本软件应用于非商业用途，而不必支付软件版权许可费用。
 ***/

if(!$_SESSION['schoolcms_admin']['aname']) Header('Location:'.__ROOT__.'/admin.php?action=login');

class BasicSettingsAction extends CommonAction
{
	/*
		系统基本设置界面显示方法
	*/
	public function Index()
	{
		if(true == empty($_POST['submit'])) {
			$this->Initialization();
		} else {
			$BasicsetupObj = M('basicsetup');
			
			/* 删除当前数组中的submit和sid键值对 */
			unset($_POST['submit']);
			
			$StatuS = 0;
			if(false == empty($_POST)) {
				foreach($_POST as $BKey=>$BValue) {
					if(false == empty($BKey)) {
						$Statu = $BasicsetupObj->where("btype='$BKey'")->setField('bdatavalue', $BValue);
						if($Statu) {
							$StatuS++;
						}
					}
				}
			}
			if($StatuS) {
				$this->SchoolCMSPrompt('成功更新 '.$StatuS.' 项！');
			} else {
				$this->SchoolCMSPrompt('更新失败！');
			}
		}
	}
	
	/*
		系统基本设置初始化方法
	*/
	private function Initialization()
	{
		//实例化基本设置表
		$BasicsetupObj = M('basicsetup');
		
		/* 从基本设置表读取参数 */
		/* 学期设置 */
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="semester"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$SrValue = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$SrValue = null;
		}
		$SemesterObj = M('semester');
		$SemesterData = $SemesterObj->select();
		$Configuration = array(
			'title' => '当前学期：',
			'selectid' => 'srid',
			'selectname' => 'srname',
			'name' => 'semester',
			'width' => '180px',
			'required' => 1,
			'default' => $SrValue,
		);
		$SelectObj = new SelectModel($SemesterData, $Configuration);
		$this->assign('semester', $SelectObj->GetSelectHtmlInfo());
		
		/* csv编码设置 */
		$CSVModelData = $BasicsetupObj->field('bdatavalue')->where('btype="modelcsv"')->select();
		if(false == empty($CSVModelData[0]['bdatavalue'])) {
			$CSVModelValue = intval($CSVModelData[0]['bdatavalue']);
		} else {
			$CSVModelValue = 1;
		}
		$SemesterData = array(array('id'=>'1','name'=>'utf-8编码'),array('id'=>'2','name'=>'gbk编码'));
		$Configuration = array(
			'title' => 'CSV编码： ',
			'selectid' => 'id',
			'selectname' => 'name',
			'name' => 'modelcsv',
			'width' => '180px',
			'required' => 1,
			'default' => $CSVModelValue,
		);
		$SelectObj = new SelectModel($SemesterData, $Configuration);
		$this->assign('modelcsv', $SelectObj->GetSelectHtmlInfo());
		
		/* model分页参数设置 */
		$PageModelData = $BasicsetupObj->field('bdatavalue')->where('btype="modelpage"')->select();
		if(false == empty($PageModelData[0]['bdatavalue'])) {
			$PageModelValue = intval($PageModelData[0]['bdatavalue']);
		} else {
			$PageModelValue = 1;
		}
		$SemesterData = array(array('id'=>'1','name'=>'简洁分页'),array('id'=>'2','name'=>'分页+自定义跳转'),array('id'=>'3','name'=>'分页+自定义跳转+详情'),array('id'=>'4','name'=>'分页+详情'));
		$Configuration = array(
			'title' => '分页显示：',
			'selectid' => 'id',
			'selectname' => 'name',
			'name' => 'modelpage',
			'width' => '180px',
			'required' => 1,
			'default' => $PageModelValue,
		);
		$SelectObj = new SelectModel($SemesterData, $Configuration);
		$this->assign('modelpage', $SelectObj->GetSelectHtmlInfo());
		
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="studentpage"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$StudentPage = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$StudentPage = null;
		}
		$FileDataArray = array(
			'textname' => '教师分页：',
			'name' => 'studentpage',
			'prompttype' => 1,
			'lengthsmall' => 1,
			'lengthlarge' => 2,
			'required' => 1,
			'default' => $StudentPage,
			'mousedown' => '输入1至2位的纯数字！',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('studentpage', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="teacherpage"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$TeacherPage = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$TeacherPage = null;
		}
		$FileDataArray = array(
			'textname' => '课程分页：',
			'name' => 'teacherpage',
			'prompttype' => 1,
			'lengthsmall' => 1,
			'lengthlarge' => 2,
			'required' => 1,
			'default' => $TeacherPage,
			'mousedown' => '输入1至2位的纯数字！',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('teacherpage', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="teachercurriculumpage"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$TeacherCurriculumPage = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$TeacherCurriculumPage = null;
		}
		$FileDataArray = array(
			'textname' => '学员分页：',
			'name' => 'teachercurriculumpage',
			'prompttype' => 1,
			'lengthsmall' => 1,
			'lengthlarge' => 2,
			'required' => 1,
			'default' => $TeacherCurriculumPage,
			'mousedown' => '输入1至2位的纯数字！',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('teachercurriculumpage', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="achievementpage"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$AchievementPage = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$AchievementPage = null;
		}
		$FileDataArray = array(
			'textname' => '成绩分页：',
			'name' => 'achievementpage',
			'prompttype' => 1,
			'lengthsmall' => 1,
			'lengthlarge' => 2,
			'required' => 1,
			'default' => $AchievementPage,
			'mousedown' => '输入1至2位的纯数字！',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('achievementpage', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="financepage"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$FinancePage = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$FinancePage = null;
		}
		$FileDataArray = array(
			'textname' => '缴费分页：',
			'name' => 'financepage',
			'prompttype' => 1,
			'lengthsmall' => 1,
			'lengthlarge' => 2,
			'required' => 1,
			'default' => $FinancePage,
			'mousedown' => '输入1至2位的纯数字！',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('financepage', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="financialdetailspage"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$FinancialDetailsPage = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$FinancialDetailsPage = null;
		}
		$FileDataArray = array(
			'textname' => '缴费明细：',
			'name' => 'financialdetailspage',
			'prompttype' => 1,
			'lengthsmall' => 1,
			'lengthlarge' => 2,
			'required' => 1,
			'default' => $FinancialDetailsPage,
			'mousedown' => '输入1至2位的纯数字！',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('financialdetailspage', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="poor"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$Poor = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$Poor = null;
		}
		$FileDataArray = array(
			'textname' => '成绩类型：',
			'name' => 'poor',
			'prompttype' => 3,
			'lengthsmall' => 1,
			'lengthlarge' => 3,
			'required' => 1,
			'default' => $Poor,
			'width' => '35px',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('poor', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="cypoor"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$CyPoor = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$CyPoor = null;
		}
		$FileDataArray = array(
			'name' => 'cypoor',
			'prompttype' => 3,
			'lengthsmall' => 1,
			'lengthlarge' => 3,
			'required' => 1,
			'default' => $CyPoor,
			'width' => '35px',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('cypoor', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="medium"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$Medium = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$Medium = null;
		}
		$FileDataArray = array(
			'name' => 'medium',
			'prompttype' => 3,
			'lengthsmall' => 1,
			'lengthlarge' => 3,
			'required' => 1,
			'default' => $Medium,
			'width' => '35px',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('medium', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="good"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$Good = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$Good = null;
		}
		$FileDataArray = array(
			'name' => 'good',
			'prompttype' => 3,
			'lengthsmall' => 1,
			'lengthlarge' => 3,
			'required' => 1,
			'default' => $Good,
			'width' => '35px',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('good', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="excellent"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$Excellent = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$Excellent = null;
		}
		$FileDataArray = array(
			'name' => 'excellent',
			'prompttype' => 3,
			'lengthsmall' => 1,
			'lengthlarge' => 3,
			'required' => 1,
			'default' => $Excellent,
			'width' => '35px',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('excellent', $FileDataArrayObj->GetInputTextHtmlInfo());
		
		
		$this->display('BasicSettings/initialization');
	}
}