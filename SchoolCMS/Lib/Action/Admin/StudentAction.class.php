<?php

/***
 *	copyright	:	Copyright (c) 2007-2013 SchoolCMS Inc. (http://www.schoolcms.cn/)
 *	license		:	http://www.schoolcms.cn/
 *	ContactQQ	:	1655098383
 *	E-mail		:	386392432@qq.com
 *	您可以在完全遵守本许可协议的基础上，将本软件应用于非商业用途，而不必支付软件版权许可费用。
 ***/

if(!$_SESSION['schoolcms_admin']['aname']) Header('Location:'.__ROOT__.'/admin.php?action=login');

class StudentAction extends CommonAction
{
	private $m_ObjCsvExport;
	//转专业方法
	public function TurnMajor(){
		if($this->isPost()){
			$xh=$_SESSION['schoolcms_admin']['aname'];
			$info=M("XsXjb")->where("xh='$xh'")->find();
			$data["XH"]=$xh;
			$data["XM"]=$info["XM"];
			$data["XBJ"]=$info["BJH"];
			$data["NJDM"]=$info["NJDM"];
			$data["XXSM"]=$info["XSM"];
			$data["XZYM"]=$info["ZYM"];
			$data["NZRXSM"]=$_POST["xueyuan"];
			$data["NZRZYM"]=$_POST["zhuanye"];
			//判读数据库中是否已经有转专业数据也
			$only=M("Zzyb")->where("xh={$xh}")->find();
			if($only){
				$this->error("您已经提交转专业申请，不能重复提交！");
				die();
			}
			//转专业数据插入数据库
			$model=M();
			$sql="insert into ZZYB values ('{$xh}','{$data["XM"]}','{$data["XBJ"]}','{$data["NJDM"]}','{$data["XXSM"]}','{$data["XZYM"]}','{$data["NZRXSM"]}','{$data["NZRZYM"]}','','')";
			//echo $sql;
			$res=$model->execute($sql);
			if($res){
				$this->success("转专业申请成功，我们会尽快审核！");

			}else{
				$this->error("转专业申请失败，请重试或者联系管理员！");
			}
			die();

		}

		$xh=$_SESSION['schoolcms_admin']['aname'];
		//查询数据库中是否已经有数据
		$only=M("Zzyb")->where("xh={$xh}")->find();
		if($only){
			$this->info=$only;
			$this->display("TurnList");
			die();
		}

		$info=M("XsXjb")->where("xh='$xh'")->find();
		$xueyuan=M("Zydzb")->distinct(true)->field('XSM')->select();
		$this->xueyuan=$xueyuan;
		$this->info=$info;
		$this->display();


	}
	//转专业信息删除
	public function delete(){
		if(IS_POST){
			if($_POST['delete']==1){
				$xh=$_SESSION['schoolcms_admin']['aname'];
				$model=M("Zzyb");
				
				$res=$model->where("xh='{$xh}'")->delete();
				if($res){
					$this->success("删除成功！");

				}else{
					$this->error("删除失败，请重试或者联系管理员！");
				}

			}


		}else{
			$this->error("非法请求！");
		}

	}
	public function TurnAjax(){
		//var_dump(IS_AJAX);
		if(IS_AJAX){

			$xueyuan=$_POST["xueyuan"];


			$zhuanye=M("Zydzb")->where("xsm='$xueyuan'")->field('ZYM')->select();
			echo json_encode($zhuanye);


		}else{
			$this->error("非法操作");
		}
	}


	/*
		公共使用
	*/
	public function Index()
	{
		/* 判断网格的操作类型，1：编辑，2：删除 */
		if(false == empty($_REQUEST['type'])) {
			if($_REQUEST['type'] == 1) {
				if(false == empty($_REQUEST['sid'])) {
					$this->StudentEditorInfo($_REQUEST['sid']);
				}
			} else if($_REQUEST['type'] == 2) {
				if(false == empty($_REQUEST['sid'])) {
					$this->StudentDeleteInfo(array($_REQUEST['sid']));
				}

			}
		}

		/* form 学员数据删除判断，如果不为空就是form提交的多个学员数据删除 */
		if(false == empty($_REQUEST['submitdelete'])) {
			if(false == empty($_REQUEST['checkbox'])) {
				$this->StudentDeleteInfo($_REQUEST['checkbox']);
			}
		}
	}


	/*
		学员管理
	*/
	public function StudentManagement()
	{
		date_default_timezone_set('Asia/Shanghai');
		
		/* 表前缀名 */
		$PREFIX = C('DB_PREFIX');
		
		/* 判断当前表是否存在 */
		$TableStatu = $this->TableName($PREFIX.'view_student');
		
		/* 实例化表对象 */
		$StudentObj = M('view_student');

		//调用初始化方法
		$this->StudentInitializationInfo();

		//调用查询条件处理方法
		$ConditionArray = $this->StudentInquiryCondition($_REQUEST);

		/* 没有视图的时候使用多表查询 */
		if(true == $TableStatu) {
			/* 按照条件获取数据的总条数 */
			$StudentCount = count($StudentObj->where($ConditionArray)->select());
		} else {
			$StudentCountData = $StudentObj->query("select count(".$PREFIX."student.sid) AS sid from (((`".$PREFIX."student` join `".$PREFIX."class` on((`".$PREFIX."class`.`cid` = `".$PREFIX."student`.`cid`))) join `".$PREFIX."studentclassify` on((`".$PREFIX."studentclassify`.`cyid` = `".$PREFIX."student`.`cyid`))) join `".$PREFIX."semester` on((`".$PREFIX."student`.`srid` = `".$PREFIX."semester`.`srid`))) where (".$this->GetSqlSplice($ConditionArray)."(`".$PREFIX."class`.`cstatu` = 1) and (`".$PREFIX."studentclassify`.`cystatu` = 1) and (`".$PREFIX."semester`.`srstatu` = 1))");
			$StudentCount = $StudentCountData[0]['sid'];
		}

		/* 从基本设置表读取分页参数 */
		$BasicsetupObj = M('basicsetup');
		$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="studentpage"')->select();
		if(false == empty($BasicsetupData[0]['bdatavalue'])) {
			$StudentPage = intval($BasicsetupData[0]['bdatavalue']);
		} else {
			$StudentPage = 8;
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
			'total' => $StudentCount,
			'traction' => $StudentPage,  //显示条数
			'custompage' => $CustomPage,  //开启自定义跳转页码
			'detaildata' => $DetailData,  //开启详情数据
			'pagecoent' => 3,  //分页显示的个数
			'url' => './StudentManagement',
		);
		$PageingObj = new PagingModel($_REQUEST, $Configuration);
		$this->assign('pageing', $PageingObj->GetPagingHtmlInfo());

		/* 没有视图的时候使用多表查询 */
		if(true == $TableStatu) {
			//起始页码值和每页显示条数从分页模块里面获取
			$StudentData = $StudentObj->limit($PageingObj->GetStarting(), $PageingObj->GetFraction())->where($ConditionArray)->select();
		} else {
			$Limit = $PageingObj->GetStarting();
			$Limits = $PageingObj->GetFraction();
			$StudentData = $StudentObj->query("select `".$PREFIX."student`.`sid` AS `sid`,`".$PREFIX."student`.`cid` AS `cid`,`".$PREFIX."student`.`sname` AS `sname`,`".$PREFIX."student`.`ssex` AS `ssex`,`".$PREFIX."student`.`ssreatedate` AS `ssreatedate`,`".$PREFIX."class`.`cname` AS `cname`,`".$PREFIX."student`.`sbirthdate` AS `sbirthdate`,`".$PREFIX."student`.`stuitionstatu` AS `stuitionstatu`,`".$PREFIX."student`.`smobile` AS `smobile`,`".$PREFIX."student`.`shomephone` AS `shomephone`,`".$PREFIX."studentclassify`.`cyname` AS `cyname`,`".$PREFIX."student`.`cyid` AS `cyid`,`".$PREFIX."student`.`stheendtime` AS `stheendtime`,`".$PREFIX."student`.`seffectivetime` AS `seffectivetime`,`".$PREFIX."student`.`srid` AS `srid`,`".$PREFIX."semester`.`srname` AS `srname` from (((`".$PREFIX."student` join `".$PREFIX."class` on((`".$PREFIX."class`.`cid` = `".$PREFIX."student`.`cid`))) join `".$PREFIX."studentclassify` on((`".$PREFIX."studentclassify`.`cyid` = `".$PREFIX."student`.`cyid`))) join `".$PREFIX."semester` on((`".$PREFIX."student`.`srid` = `".$PREFIX."semester`.`srid`))) where (".$this->GetSqlSplice($ConditionArray)."(`".$PREFIX."class`.`cstatu` = 1) and (`".$PREFIX."studentclassify`.`cystatu` = 1) and (`".$PREFIX."semester`.`srstatu` = 1)) limit $Limit, $Limits");
		}

		/* 调用方法处理数据 */
		$StudentData = $this->GetStudentDataArray($StudentData);

		/* 调用网格和CSV模块 */
		$this->assign('gridtable', $this->GetGridInitializationInfo($StudentData));
		$this->assign('subcsvexport', $this->GetCSVInitializationInfo());

		$this->display('Student/studentmanagement');
	}

	/*
		最终的数据处理字段
	*/
	private function GetStudentDataArray($StudentData)
	{
		if(false == empty($StudentData) && true == is_array($StudentData)) {
			foreach($StudentData as $SKey=>$SValue) {
				foreach($SValue as $SKeys=>$SValues) {
					/* 将时间戳转为日期 */
					if($SKeys == 'sbirthdate') {
						$SValue['sbirthdate'] = date('Y-m-d', $SValues);

						/* 获取当前时间减去数据库的出生日期等于学员的实际年龄 */
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

					/* 缴费状态处理 */
					if($SValue['stuitionstatu'] == 2) {
						$SValue['stuitionstatu'] = '<span style="color:#5EBB29;">已缴费</span>';
					} elseif($SValue['stuitionstatu'] == 1) {
						$SValue['stuitionstatu'] = '<a href="../Finance/FinanceEditorInfo?sid='.$SValue['sid'].'" style="color:#2D64B3;" class="gridtablea " title="点击缴费">未缴费</a>';
					}

					/* 时间戳处理 */
					if($SKeys == 'seffectivetime') {
						$SValue['seffectivetime'] = date('Y-m-d', $SValue['seffectivetime']);
					}
					if($SKeys == 'stheendtime') {
						$SValue['stheendtime'] = date('Y-m-d', $SValue['stheendtime']);
					}
				}
				$StudentData[$SKey] = $SValue;
			}
		}
		return $StudentData;
	}


	/*
		数据字段数组方法
	*/
	private function GetFieldArray()
	{
		//调用网格显示数据
		return $FieldArray = array(
			'sname' => '姓名',
			'sage' => '年龄',
			'ssex' => '性别',
			'sbirthdate' => '出生',
			'cyname' => '地区',
			'cname' => '班级',
			'stuitionstatu' => '缴费状态',
			'smobile' => '联系手机',
			'shomephone' => '家庭电话',
			'ssreatedate' => '报名时间',
			'seffectivetime' => '生效时间',
			'stheendtime' => '终止时间',
		);
	}

	/*
		初始化CSV方法
	*/
	public function GetCSVInitializationInfo()
	{
		$CsvExportObj = new CSVModel($this->GetFieldArray(), 'subcsvexport', '学员数据', './GetCSVInitializationInfo');
		//判断是不是到处CSV
		if(false == empty($_REQUEST['type'])) {
			if($_REQUEST['type'] == 'csvexport') {
				/* 表前缀名 */
				$PREFIX = C('DB_PREFIX');

				/* 判断当前表是否存在 */
				$TableStatu = $this->TableName($PREFIX.'view_student');
				
				/* 实例化表对象 */
					$StudentObj = M('view_student');

				/* 没有视图的时候使用多表查询 */
				if(true == $TableStatu) {
					$StudentData = $StudentObj->select();
				} else {
					$StudentData = $StudentObj->query("select `".$PREFIX."student`.`sid` AS `sid`,`".$PREFIX."student`.`cid` AS `cid`,`".$PREFIX."student`.`sname` AS `sname`,`".$PREFIX."student`.`ssex` AS `ssex`,`".$PREFIX."student`.`ssreatedate` AS `ssreatedate`,`".$PREFIX."class`.`cname` AS `cname`,`".$PREFIX."student`.`sbirthdate` AS `sbirthdate`,`".$PREFIX."student`.`stuitionstatu` AS `stuitionstatu`,`".$PREFIX."student`.`smobile` AS `smobile`,`".$PREFIX."student`.`shomephone` AS `shomephone`,`".$PREFIX."studentclassify`.`cyname` AS `cyname`,`".$PREFIX."student`.`cyid` AS `cyid`,`".$PREFIX."student`.`stheendtime` AS `stheendtime`,`".$PREFIX."student`.`seffectivetime` AS `seffectivetime`,`".$PREFIX."student`.`srid` AS `srid`,`".$PREFIX."semester`.`srname` AS `srname` from (((`".$PREFIX."student` join `".$PREFIX."class` on((`".$PREFIX."class`.`cid` = `".$PREFIX."student`.`cid`))) join `".$PREFIX."studentclassify` on((`".$PREFIX."studentclassify`.`cyid` = `".$PREFIX."student`.`cyid`))) join `".$PREFIX."semester` on((`".$PREFIX."student`.`srid` = `".$PREFIX."semester`.`srid`))) where ((`".$PREFIX."class`.`cstatu` = 1) and (`".$PREFIX."studentclassify`.`cystatu` = 1) and (`".$PREFIX."semester`.`srstatu` = 1))");
				}
				$CsvExportObj->CsvExportData($this->GetStudentDataArray($StudentData));  //处理页面传递过来的数据
			}
		} else {
			return $CsvExportObj->GetTitltDataHtml();
		}
	}

	/*
		初始化网格方法
	*/
	private function GetGridInitializationInfo($StudentData)
	{
		$Configuration = array(
			'id' => 'sid',
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
				'name' => '录成绩',
				'returntype' => 3,
				'url' => '../Achievement/Index',
			),
		);
		$GridObj = new GridModel($StudentData, $this->GetFieldArray(), $Configuration, $Operating);
		return $GridObj->GetGridHtmlInfo();
	}

	/*
		学员管理初始化方法
	*/
	public function StudentInitializationInfo()
	{
		if(false == empty($_REQUEST['sname'])) {
			$SName = $_REQUEST['sname'];
		} else {
			$SName = null;
		}
		$FileDataArray = array(
			'textname' => '姓名：',
			'name' => 'sname',
			'width' => '100px',
			'default' => $SName,
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('sname', $FileDataArrayObj->GetInputTextHtmlInfo());

		if(false == empty($_REQUEST['sage'])) {
			$SAge = $_REQUEST['sage'];
		} else {
			$SAge = null;
		}
		$FileDataArray = array(
			'textname' => '年龄：',
			'name' => 'sage',
			'width' => '36px',
			'default' => $SAge,
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('sage', $FileDataArrayObj->GetInputTextHtmlInfo());

		if(false == empty($_REQUEST['sages'])) {
			$SAges = $_REQUEST['sages'];
		} else {
			$SAges = null;
		}
		$FileDataArray = array(
			'textname' => ' 至 ',
			'name' => 'sages',
			'width' => '36px',
			'default' => $SAges,
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('sages', $FileDataArrayObj->GetInputTextHtmlInfo());

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
			'whole' => '全部',
			'width' => '100px',
			'default' => $Cid,
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('cid', $SelectObj->GetSelectHtmlInfo());

		if(false == empty($_REQUEST['cyid'])) {
			$Cyid = $_REQUEST['cyid'];
		} else {
			$Cyid = null;
		}
		$ClassObj = M('studentclassify');
		$ClassData = $ClassObj->select();
		$Configuration = array(
			'title' => '地区：',
			'selectid' => 'cyid',
			'selectname' => 'cyname',
			'name' => 'cyid',
			'whole' => '全部',
			'width' => '100px',
			'default' => $Cyid,
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('cyid', $SelectObj->GetSelectHtmlInfo());

		if(false == empty($_REQUEST['sbirthdate'])) {
			$SBirthdate = $_REQUEST['sbirthdate'];
		} else {
			$SBirthdate = null;
		}
		$BirthDate = array(
			'datename' => '出生：',
			'name' => 'sbirthdate',
			'type' => 1,
			'width' => '100px',
			'default' => $SBirthdate,
		);
		$SelectObj = new InputDateModel($BirthDate);
		$this->assign('sbirthdate', $SelectObj->GetInputDateHtmlInfo());

		if(false == empty($_REQUEST['ssreatedate1'])) {
			$SSreateDate1 = $_REQUEST['ssreatedate1'];
		} else {
			$SSreateDate1 = null;
		}
		if(false == empty($_REQUEST['ssreatedate2'])) {
			$SSreateDate2 = $_REQUEST['ssreatedate2'];
		} else {
			$SSreateDate2 = null;
		}
		$CreateDate = array(
			'datename' => '报名时间：',
			'name' => 'ssreatedate',
			'type' => 2,
			'width' => '100px',
			'default' => $SSreateDate1,
			'default2' => $SSreateDate2,
		);
		$SelectObj = new InputDateModel($CreateDate);
		$this->assign('ssreatedate', $SelectObj->GetInputDateHtmlInfo());

		if(false == empty($_REQUEST['seffectivetime1'])) {
			$SEffectiveTime = $_REQUEST['seffectivetime1'];
		} else {
			$SEffectiveTime = null;
		}
		if(false == empty($_REQUEST['seffectivetime2'])) {
			$STheendTime = $_REQUEST['seffectivetime2'];
		} else {
			$STheendTime = null;
		}
		$CreateDate = array(
			'datename' => '有效时间：',
			'name' => 'seffectivetime',
			'type' => 2,
			'width' => '100px',
			'default' => $SEffectiveTime,
			'default2' => $STheendTime,
		);
		$SelectObj = new InputDateModel($CreateDate);
		$this->assign('seffectivetime', $SelectObj->GetInputDateHtmlInfo());

		if(false == empty($_REQUEST['ssex'])) {
			$SSex = $_REQUEST['ssex'];
		} else {
			$SSex = null;
		}
		$SexConfiguration = array(
			'title' => '性别：',
			'selectid' => 'id',
			'selectname' => 'name',
			'name' => 'ssex',
			'whole' => '全部',
			'width' => '100px',
			'default' => $SSex,
		);
		$SexDataArray = array(
			array('id' => 1, 'name' => '保密',),
			array('id' => 2, 'name' => '女',),
			array('id' => 3, 'name' => '男',),
		);
		$SelectObj = new SelectModel($SexDataArray, $SexConfiguration);
		$this->assign('ssex', $SelectObj->GetSelectHtmlInfo());

		if(false == empty($_REQUEST['stuitionstatu'])) {
			$STuitionStatu = $_REQUEST['stuitionstatu'];
		} else {
			$STuitionStatu = null;
		}
		$SexConfiguration = array(
			'title' => '缴费状态：',
			'selectid' => 'id',
			'selectname' => 'name',
			'name' => 'stuitionstatu',
			'whole' => '全部',
			'width' => '100px',
			'default' => $STuitionStatu,
		);
		$SexDataArray = array(
			array('id' => 1, 'name' => '未缴费',),
			array('id' => 2, 'name' => '已缴费',),
		);
		$SelectObj = new SelectModel($SexDataArray, $SexConfiguration);
		$this->assign('stuitionstatu', $SelectObj->GetSelectHtmlInfo());
	}

	/*
		学员信息查询条件处理方法
	*/
	private function StudentInquiryCondition($Inquiry = array())
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
						//学生姓名
						case 'sname':
							//$ConditionArray['sname'] = $Cvalue;
							$ConditionArray['sname'] = array('like','%'.$Cvalue.'%');
						break;
						//班级id
						case 'cid':
							$ConditionArray['cid'] = intval($Cvalue);
						break;
						//性别
						case 'ssex':
							$ConditionArray['ssex'] = intval($Cvalue);
						break;
						//地区
						case 'cyid':
							$ConditionArray['cyid'] = intval($Cvalue);
						break;
						//缴费状态
						case 'stuitionstatu':
							$ConditionArray['stuitionstatu'] = intval($Cvalue);
						break;
						//年龄起始
						case 'sage':
							if(0 != intval($Cvalue)) {
								array_push($ConditionArrayAge, array('elt', strtotime(date('Y',time())-$Cvalue)));
							}
						break;
						//年龄终止
						case 'sages':
							if(0 != intval($Cvalue)) {
								array_push($ConditionArrayAge, array('egt', strtotime(date('Y',time())-$Cvalue)));
							}
						break;
						//出生日期
						case 'sbirthdate':
							array_push($ConditionArrayAge, array('eq', strtotime($Cvalue)));
						break;
						//创建时间的起始时间
						case 'ssreatedate1':
							array_push($ConditionArrayDaate, array('egt', $Cvalue));
						break;
						//创建时间的终止时间
						case 'ssreatedate2':
							array_push($ConditionArrayDaate, array('elt', $Cvalue));
						break;
						//生效时间
						case 'seffectivetime1':
							$ConditionArray['seffectivetime'] = array('egt', strtotime($Cvalue));
						break;
						//终止时间
						case 'seffectivetime2':
							$ConditionArray['stheendtime'] = array('elt', strtotime($Cvalue));
						break;

						default:
						//没有这个条件
					}
				}
			}
		}
		if(false == empty($ConditionArrayAge)) {
			$ConditionArray['sbirthdate'] = $ConditionArrayAge;
		}
		if(false == empty($ConditionArrayDaate)) {
			$ConditionArray['ssreatedate'] = $ConditionArrayDaate;
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
		删除学员数据方法（支持批量处理）一维数组
	*/
	public function StudentDeleteInfo($StudentArraySID)
	{
		//判断id是数组还是一个，implode 将一个数组的键值拼接成一个字符串
		if((true == is_array($StudentArraySID)) && (false == empty($StudentArraySID))){
			$StudentDeleteObj = M('student');
			$StudentDeleteObj->startTrans();  /* 启动事务 */

			$Where = 'sid in('.implode(',', $StudentArraySID).')';

			/* 循环删除当前学员的成绩数据 */
			foreach($StudentArraySID as $SValue) {
				$AchievementDeleteObj = M('achievement');
				$AchievementDeleteObj->where('sid='.intval($SValue))->delete();
			}

			/* 删除学员缴费表的数据 */
			$PaymentObj = M('payment');
			$PaymentObj->where('sid='.intval($SValue))->delete();
			
			/* 删除学员数据 */
			$Statu = $StudentDeleteObj->where($Where)->delete();

			/* 删除状态 */
			if(true == $Statu) {
				$StudentDeleteObj->commit();  /* 提交事务 */
				$this->SchoolCMSPrompt("成功删除 {$Statu} 条！");
			} else {
				$StudentDeleteObj->rollback();  /* 回滚事务 */
				$this->SchoolCMSPrompt('删除失败！');
			}
		}
	}

	/*
		编辑学员数据方法
	*/
	public function StudentEditorInfo($StudentSID = null)
	{
		date_default_timezone_set('Asia/Shanghai');

		$StudentObj = M('student');
		if(false == empty($_POST['submit'])) {

			/* 将学员出生日期转换为时间戳 */
			$_POST['sbirthdate'] = strtotime($_POST['sbirthdate']);

			/* 学员id */
			if(false == empty($_POST['sid'])) {
				$Sid = $_POST['sid'];
			} else {
				$Sid = null;
			}
			/* 删除当前数组中的不属于学员表字段的键值对 */
			unset($_POST['submit']);
			unset($_POST['sid']);
			unset($_POST['ptmoney']);
			unset($_POST['ptremarks']);

			/* 生效时间和终止时间，转为时间戳 */
			if(false == empty($_POST['seffectivetime'])) {
				$_POST['seffectivetime'] = strtotime($_POST['seffectivetime']);
			}
			if(false == empty($_POST['stheendtime'])) {
				$_POST['stheendtime'] = strtotime($_POST['stheendtime']);
			}

			/* 更新数据操作 */
			$Statu = $StudentObj->where('sid='.$Sid)->save($_POST);
			if($Statu) {
				$this->SchoolCMSPrompt('更新成功！', 'StudentManagement');
			} else {
				$this->SchoolCMSPrompt('更新失败！', 'StudentManagement');
			}
		} else {
			if(false == empty($StudentSID)) {
				$StudentData = $StudentObj->select(intval($StudentSID));
				$this->StudentInterfaceInfo('./StudentEditorInfo', 2, $StudentData);
			}
		}

	}

	/*
		新增学员数据方法
	*/
	public function StudentAddInfo()
	{
		date_default_timezone_set('Asia/Shanghai');

		/* 如果$_POST['submit']为空就调用新增方法 */
		if(false == empty($_POST['submit'])) {
			/* 创建时间 */
			$_POST['ssreatedate'] = date('Y-m-d H:i:s',time());

			/* 将学员出生日期转换为时间戳 */
			$_POST['sbirthdate'] = strtotime($_POST['sbirthdate']);

			/* 生效时间和终止时间，转为时间戳 */
			if(false == empty($_POST['seffectivetime'])) {
				$_POST['seffectivetime'] = strtotime($_POST['seffectivetime']);
			}
			if(false == empty($_POST['stheendtime'])) {
				$_POST['stheendtime'] = strtotime($_POST['stheendtime']);
			}

			/* 缴费金额判断，如果金额不为空，就将缴费状态设置为已缴费，数据库默认未缴费状态 */
			if(false == empty($_POST['ptmoney'])) {
				$_POST['stuitionstatu'] = 2;
			}

			/* 缴费表数据 */
			$PaymentData = array(
				'ptmoney' => $_POST['ptmoney'],
				'ptremarks' => $_POST['ptremarks'],
				'ptdate' => date('Y-m-d H:i:s',time()),
				'ptupdate' => date('Y-m-d H:i:s',time()),
			);
			
			/* 删除当前数组中不属于学员表的键值对 */
			unset($_POST['submit']);
			unset($_POST['ptmoney']);
			unset($_POST['ptremarks']);

			/* 新增数据操作 */
			$StudentObj = M('student');
			$StudentObj->startTrans();  /* 启动事务 */
			$SID = $StudentObj->add($_POST);
			
			/* 学员数据写入成功，则返回当前写入数据的自增id */
			$PaymentData['sid'] = $SID;
			
			/* 实例化缴费表，新增缴费数据 */
			$PaymentObj = M('payment');
			$Statu = $PaymentObj->add($PaymentData);
			
			/* 写入学员缴费日志表 */
			$PaymentModifylogData = array(
				'aid' => $_SESSION['schoolcms_admin']['aid'],
				'aname' => $_SESSION['schoolcms_admin']['aname'],
				'sid' => $SID,
				'sname' => $_POST['sname'],
				'pgoriginalmoney' => $_POST['ptmoney'],
				'pgoperatingdate' => date('Y-m-d H:i:s',time()),
				'pgoperatingtype' => '创建',
				'pgremark' => $_POST['ptremarks'].'(新增学员)',
			);
			$PaymentModifylogObj = M('paymentmodifylog');
			$Statu = $PaymentModifylogObj->add($PaymentModifylogData);
			
			/* 判断学员数据是否写入成功，如果不成功就回滚事务 */
			if($Statu) {
				$StudentObj->commit();  /* 提交事务 */
				$this->SchoolCMSPrompt('新增成功！', 'StudentManagement', null, '继续新增学员', 'StudentAddInfo');
			} else {
				$StudentObj->rollback();  /* 回滚事务 */
				$this->SchoolCMSPrompt('新增失败！');
			}
		} else {
			$this->StudentInterfaceInfo('./StudentAddInfo');
		}
	}

	/*
		新增学员数据界面方法
	*/
	private function StudentInterfaceInfo($Url, $Type = 1, $StudentDataArray = array())
	{
		if(false == empty($StudentDataArray[0]['srid'])) {
			$Srid = $StudentDataArray[0]['srid'];
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
		$SemesterData = $SemesterObj->select($Srid);
		if(true == empty($SemesterData[0]['srid'])) {
			$Srid = 0;
		}
		$Configuration = array(
			'title' => '所属学期：',
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



		if(false == empty($StudentDataArray[0]['sname'])) {
			$SName = $StudentDataArray[0]['sname'];
		} else {
			$SName = null;
		}
		$FileDataArray = array(
			'textname' => '学员姓名：',
			'name' => 'sname',
			'prompt' => '输入学员姓名',
			'prompttype' => 1,
			'lengthsmall' => 2,
			'lengthlarge' => 6,
			'required' => 1,
			'default' => $SName,
			'mousedown' => '请输入2至6位之间的字符！',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('sname', $FileDataArrayObj->GetInputTextHtmlInfo());

		if(false == empty($StudentDataArray[0]['smobile'])) {
			$SMobile = $StudentDataArray[0]['smobile'];
		} else {
			$SMobile = null;
		}
		$FileDataArray = array(
			'textname' => '联系手机：',
			'name' => 'smobile',
			'prompt' => '输入联系手机',
			'prompttype' => 1,
			'required' => 1,
			'verificationtype' => 2,
			'default' => $SMobile,
			'mousedown' => '请输入11位数字手机号！',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('smobile', $FileDataArrayObj->GetInputTextHtmlInfo());

		if(false == empty($StudentDataArray[0]['shomephone'])) {
			$SHomephone = $StudentDataArray[0]['shomephone'];
		} else {
			$SHomephone = null;
		}
		$FileDataArray = array(
			'textname' => '家庭电话：',
			'name' => 'shomephone',
			'prompt' => '输入家庭电话',
			'prompttype' => 1,
			'required' => 1,
			'verificationtype' => 3,
			'default' => $SHomephone,
			'mousedown' => '请输入座机电话、格式:010-00000000',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('shomephone', $FileDataArrayObj->GetInputTextHtmlInfo());

		if(false == empty($StudentDataArray[0]['ssex'])) {
			$SSex = $StudentDataArray[0]['ssex'];
		} else {
			$SSex = null;
		}
		$SexConfiguration = array(
			'title' => '性　　别：',
			'selectid' => 'id',
			'selectname' => 'name',
			'name' => 'ssex',
			'width' => '180px',
			'required' => 1,
			'default' => $SSex,
		);
		$SexDataArray = array(
			array('id' => 1, 'name' => '保密',),
			array('id' => 2, 'name' => '女',),
			array('id' => 3, 'name' => '男',),
		);
		$SelectObj = new SelectModel($SexDataArray, $SexConfiguration);
		$this->assign('ssex', $SelectObj->GetSelectHtmlInfo());

		if(false == empty($StudentDataArray[0]['cid'])) {
			$Cid = $StudentDataArray[0]['cid'];
		} else {
			$Cid = null;
		}
		$ClassObj = M('class');
		$ClassData = $ClassObj->select();
		$Configuration = array(
			'title' => '所属班级：',
			'selectid' => 'cid',
			'selectname' => 'cname',
			'name' => 'cid',
			'width' => '180px',
			'required' => 1,
			'default' => $Cid,
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('cid', $SelectObj->GetSelectHtmlInfo());

		if(false == empty($StudentDataArray[0]['cyid'])) {
			$Cyid = $StudentDataArray[0]['cyid'];
		} else {
			$Cyid = null;
		}
		$ClassObj = M('studentclassify');
		$ClassData = $ClassObj->select();
		$Configuration = array(
			'title' => '所属地区：',
			'selectid' => 'cyid',
			'selectname' => 'cyname',
			'name' => 'cyid',
			'width' => '180px',
			'required' => 1,
			'default' => $Cyid,
		);
		$SelectObj = new SelectModel($ClassData, $Configuration);
		$this->assign('cyid', $SelectObj->GetSelectHtmlInfo());

		if(false == empty($StudentDataArray[0]['sbirthdate'])) {
			$SBirthdate = date('Y-m-d', $StudentDataArray[0]['sbirthdate']);
		} else {
			$SBirthdate = null;
		}
		$BirthDate = array(
			'default' => $SBirthdate,
			'datename' => '出生日期：',
			'name' => 'sbirthdate',
			'type' => 1,
			'width' => '180px',
			'required' => 1,
			'verification' => 1,
		);
		$SelectObj = new InputDateModel($BirthDate);
		$this->assign('sbirthdate', $SelectObj->GetInputDateHtmlInfo());

		if(false == empty($StudentDataArray[0]['seffectivetime'])) {
			$SEffectivetime = date('Y-m-d', $StudentDataArray[0]['seffectivetime']);
		} else {
			$SEffectivetime = null;
		}
		$BirthDate = array(
			'default' => $SEffectivetime,
			'datename' => '生效时间：',
			'name' => 'seffectivetime',
			'type' => 1,
			'width' => '180px',
			'required' => 1,
			'verification' => 1,
		);
		$SelectObj = new InputDateModel($BirthDate);
		$this->assign('seffectivetime', $SelectObj->GetInputDateHtmlInfo());

		if(false == empty($StudentDataArray[0]['stheendtime'])) {
			$STheendtime = date('Y-m-d', $StudentDataArray[0]['stheendtime']);
		} else {
			$STheendtime = null;
		}
		$BirthDate = array(
			'default' => $STheendtime,
			'datename' => '终止时间：',
			'name' => 'stheendtime',
			'type' => 1,
			'width' => '180px',
			'required' => 1,
			'verification' => 1,
		);
		$SelectObj = new InputDateModel($BirthDate);
		$this->assign('stheendtime', $SelectObj->GetInputDateHtmlInfo());


		$FileDataArray = array(
			'textname' => '学费金额：',
			'name' => 'ptmoney',
			'prompt' => '输入缴费金额',
			'prompttype' => 1,
			'lengthsmall' => 1,
			'lengthlarge' => 6,
			'verificationtype' => 7,
			'mousedown' => '请输入1至6位之间的字符！',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('ptmoney', $FileDataArrayObj->GetInputTextHtmlInfo());


		$FileDataArray = array(
			'textname' => '缴费备注：',
			'name' => 'ptremarks',
			'prompt' => '输入缴费备注',
			'prompttype' => 1,
			'lengthsmall' => 1,
			'lengthlarge' => 255,
			'mousedown' => '请输入1至255位之间的字符！',
		);
		$FileDataArrayObj = new InputTextModel($FileDataArray);
		$this->assign('ptremarks', $FileDataArrayObj->GetInputTextHtmlInfo());

		/* 使用类型，url地址 */
		$this->assign('url', $Url);

		/* 学员id */
		if(false == empty($StudentDataArray[0]['sid'])) {
			$Sid = $StudentDataArray[0]['sid'];
		} else {
			$Sid = null;
		}
		$this->assign('sid', $Sid);

		/* 类型1：新增，2：编辑 */
		$this->assign('type', $Type);

		$this->display('Student/studentinterface');
	}

}
?>
