<?php

/***
 *	copyright	:	Copyright (c) 2007-2013 SchoolCMS Inc. (http://www.schoolcms.cn/)
 *	license		:	http://www.schoolcms.cn/
 *	ContactQQ	:	1655098383
 *	E-mail		:	386392432@qq.com
 *	您可以在完全遵守本许可协议的基础上，将本软件应用于非商业用途，而不必支付软件版权许可费用。
 ***/

if(!$_SESSION['schoolcms_admin']['aname']) Header('Location:'.__ROOT__.'/admin.php?action=login');

class ClassificationAction extends CommonAction
{
	/*
		学期分类管理
	*/
	public function SemesterClass()
	{
		$FileArray = array(
			'fileid' => 'srid',
			'filesort' => 'srsort',
			'filename' => 'srname',
			'tablename' => 'semester',
			'treeurl' => './SemesterClass',  //ajax的url地址
			'checkbox' => 1,  //是否开启复选框
			'operating' => 1,  //操作
			'sort' => 1,  //排序
			'name' => 1,  //名称
			'form' => 1,  //form表单
			'dbeditor' => 1,  //双击编辑名称
		);
		$Linked = array('student'=>'srid');
		$Linkeds = array(
			'student' => array(
				array('fileid'=>'sid','tablename'=>'achievement'),
				array('fileid'=>'sid','tablename'=>'payment'),
			)
		);
		$TreeObj = new TreeModel($FileArray, $Linked, $Linkeds);

		$this->assign('semesterclass', $TreeObj->GetTreeDataHtml());
		$this->display('Classification/semesterclass');
	}
	
	/*
		班级分类管理
	*/
	public function SchoolCMSClass()
	{
		$FileArray = array(
			'fileid' => 'cid',
			'filename' => 'cname',
			'filesort' => 'csort',
			'tablename' => 'class',
			'treeurl' => './SchoolCMSClass',  //url地址
			'checkbox' => 1,  //是否开启复选框
			'operating' => 1,  //操作
			'sort' => 1,  //排序
			'name' => 1,  //名称
			'form' => 1,  //form表单
			'dbeditor' => 1,  //双击编辑名称
		);
		$Linked = array('student'=>'cid','tcwct'=>'cid');
		$Linkeds = array(
			'student' => array(
				array('fileid'=>'sid','tablename'=>'achievement'),
				array('fileid'=>'sid','tablename'=>'payment'),
			)
		);
		$TreeObj = new TreeModel($FileArray, $Linked, $Linkeds);

		$this->assign('schoolcmsclass', $TreeObj->GetTreeDataHtml());
		$this->display('Classification/schoolcmsclass');
	}
	
	/*
		成绩分类管理
	*/
	public function AchievementClass()
	{
		$FileArray = array(
			'fileid' => 'atcid',
			'filename' => 'atcname',
			'filesort' => 'atcsort',
			'tablename' => 'achievementclass',
			'treeurl' => './AchievementClass',  //url地址
			'checkbox' => 1,  //是否开启复选框
			'operating' => 1,  //操作
			'sort' => 1,  //排序
			'name' => 1,  //名称
			'form' => 1,  //form表单
			'dbeditor' => 1,  //双击编辑名称
		);
		$TreeObj = new TreeModel($FileArray, array('achievement'=>'atcid'));

		$this->assign('achievementclass', $TreeObj->GetTreeDataHtml());
		$this->display('Classification/achievementclass');
	}
	
	/*
		科目分类管理
	*/
	public function CurriculumClass()
	{
		$FileArray = array(
			'fileid' => 'cmid',
			'filename' => 'cmname',
			'filesort' => 'cmsort',
			'tablename' => 'curriculum',
			'treeurl' => './CurriculumClass',  //url地址
			'checkbox' => 1,  //是否开启复选框
			'operating' => 1,  //操作
			'sort' => 1,  //排序
			'name' => 1,  //名称
			'form' => 1,  //form表单
			'dbeditor' => 1,  //双击编辑名称
		);
		$TreeObj = new TreeModel($FileArray, array('achievement'=>'cmid','tcwct'=>'cmid'));

		$this->assign('curriculumclass', $TreeObj->GetTreeDataHtml());
		$this->display('Classification/curriculumclass');
	}
	
	/*
		时段分类管理
	*/
	public function TimeClass()
	{
		$FileArray = array(
			'fileid' => 'teid',
			'filename' => 'tename',
			'filesort' => 'tesort',
			'tablename' => 'time',
			'treeurl' => './TimeClass',  //url地址
			'checkbox' => 1,  //是否开启复选框
			'operating' => 1,  //操作
			'sort' => 1,  //排序
			'name' => 1,  //名称
			'form' => 1,  //form表单
			'dbeditor' => 1,  //双击编辑名称
		);
		$TreeObj = new TreeModel($FileArray, array('achievement'=>'cmid','tcwct'=>'cmid'));

		$this->assign('timeclass', $TreeObj->GetTreeDataHtml());
		$this->display('Classification/timeclass');
	}
	
	/*
		周分类管理
	*/
	public function WeekClass()
	{
		$FileArray = array(
			'fileid' => 'wid',
			'filename' => 'wname',
			'filesort' => 'wsort',
			'tablename' => 'week',
			'treeurl' => './WeekClass',  //url地址
			'checkbox' => 1,  //是否开启复选框
			'operating' => 1,  //操作
			'sort' => 1,  //排序
			'name' => 1,  //名称
			'form' => 1,  //form表单
			'dbeditor' => 1,  //双击编辑名称
		);
		$TreeObj = new TreeModel($FileArray, array('achievement'=>'cmid','tcwct'=>'cmid'));

		$this->assign('weekclass', $TreeObj->GetTreeDataHtml());
		$this->display('Classification/weekclass');
	}
	
	/*
		地区分类管理
	*/
	public function StudentclassifyClass()
	{
		$FileArray = array(
			'fileid' => 'cyid',
			'filepid' => 'cypid',
			'filename' => 'cyname',
			'filesort' => 'cysort',
			'tablename' => 'studentclassify',
			'treeurl' => './StudentclassifyClass',  //url地址
			'checkbox' => 1,  //是否开启复选框
			'addnode' => 1,  //是否开启新增下级
			'operating' => 1,  //操作
			'sort' => 1,  //排序
			'name' => 1,  //名称
			'form' => 1,  //form表单
			'dbeditor' => 1,  //双击编辑名称
		);
		$Linked = array('student'=>'cyid');
		$Linkeds = array('student' => array('fileid'=>'sid','tablename'=>'achievement'));
		$TreeObj = new TreeModel($FileArray, $Linked, $Linkeds);

		$this->assign('studentclassifyclass', $TreeObj->GetTreeDataHtml());
		$this->display('Classification/studentclassifyclass');
	}
}