<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
	<meta http-equiv='content-type' content='text/html;charset=utf-8' />
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<title>SchoolCMS学校管理系统 - Powered by SchoolCMS</title>
	<script type="text/javascript" src="__ROOT__/Static/Js/Common/jquery.js"></script>
	<script type="text/javascript" src="__ROOT__/Static/Js/Common/common.js"></script>
	<link rel="stylesheet" type="text/css" href="__ROOT__/Static/Css/Common/common.css" />
	<script type="text/javascript" src="__ROOT__/Static/Js/Admin/admin.js"></script>
	<link rel="stylesheet" type="text/css" href="__ROOT__/Static/Css/Admin/admin.css" />
	<script type='text/javascript' >
		var SITE_PATH = '<?php echo ($SITE_ROOT); ?>';  //定义项目所在绝对地址
		var tplroot = '__ROOT__/admin.php';  //定义js变量（项目根目录）
		var root = '__ROOT__';  //定义js变量（网站根目录）
		var windowheight = $(window).height()-91;
	</script>
</head>
<body>
<div id="bodywindows">
<div id="bodywindow">
	<div id="header">
		<div id="logo"></div>
		<div class="navigation">
			<ul>
				<li class="navigationtop">
					<a>
						<span>首页</span>
					</a>
				</li>
				<span class="displaynone">adminindex</span>
				<li>
					<a>
						<span>转专业</span>
					</a>
				</li>
				<span class="displaynone">adminstudent</span>
				<!-- <li>
					<a>
						<span>成绩管理</span>
					</a>
				</li>
				<span class="displaynone">adminachievement</span>
				<li>
					<a>
						<span>教师管理</span>
					</a>
				</li>
				<span class="displaynone">adminteacher</span>
				<li>
					<a>
						<span>财务管理</span>
					</a>
				</li>
				<span class="displaynone">adminpayment</span>
				<li>
					<a>
						<span>分类管理</span>
					</a>
				</li>
				<span class="displaynone">adminclassification</span> -->
			</ul>
			
		</div>
		<div class="topcontent">
			<div class="topcontents">
				<span class="current">您的位置:</span>
				<span class="location">基本设置 > <span class="locations">欢迎页面</span></span>
			</div>
			<span class="adminright">
				当前用户:<span ><?php echo ($admin); ?></span>
				&nbsp;<a href="__ROOT__/admin.php?action=logout" title="退出" class="adminrightslogout">退出</a>
			</span>
		</div>
	</div>
	<div id="content">
		<div id="contentleft">
			<ul class="adminindex">
				<li class="contentlefttop">欢迎页面</li>
				<span class="displaynone">/Admin/Index/Welcome</span>
				<!-- <li>系统设置</li>
				<span class="displaynone">/Admin/BasicSettings/Index</span>
				<li>资料设置</li>
				<span class="displaynone">/Admin/Admin/AdminEditor</span>
				<li>添加管理员</li>
				<span class="displaynone">/Admin/Admin/AddAdminInfo</span> -->
			</ul>
			<ul class="adminstudent displaynone">
				<li class="contentlefttop">转专业</li>
				<span class="displaynone">/Admin/Student/TurnMajor</span>
				<!-- <li>新增学员</li>
				<span class="displaynone">/Admin/Student/StudentAddInfo</span> -->
			</ul>
			<!-- <ul class="adminachievement displaynone">
				<li class="contentlefttop">成绩管理</li>
				<span class="displaynone">/Admin/Achievement/AchievementMt</span>
			</ul>
			<ul class="adminteacher displaynone">
				<li class="contentlefttop">教师管理</li>
				<span class="displaynone">/Admin/Teacher/TeacherManagement</span>
				<li>新增教师</li>
				<span class="displaynone">/Admin/Teacher/TeacherAddInfo</span>
				<li>课程安排</li>
				<span class="displaynone">/Admin/TeacherCurriculum/TeacherCurriculumManagement</span>
			</ul>
			<ul class="adminpayment displaynone">
				<li class="contentlefttop">缴费管理</li>
				<span class="displaynone">/Admin/Finance/PaymentManagement</span>
				<li>缴费明细</li>
				<span class="displaynone">/Admin/FinancialDetails/PaymentDetailsManagement</span>
			</ul>
			<ul class="adminclassification displaynone">
				<li class="contentlefttop">学期分类管理</li>
				<span class="displaynone">/Admin/Classification/SemesterClass</span>
				<li>班级分类管理</li>
				<span class="displaynone">/Admin/Classification/SchoolCMSClass</span>
				<li>成绩分类管理</li>
				<span class="displaynone">/Admin/Classification/AchievementClass</span>
				<li>科目分类管理</li>
				<span class="displaynone">/Admin/Classification/CurriculumClass</span>
				<li>时段分类管理</li>
				<span class="displaynone">/Admin/Classification/TimeClass</span>
				<li>周分类管理</li>
				<span class="displaynone">/Admin/Classification/WeekClass</span>
				<li>地区分类管理</li>
				<span class="displaynone">/Admin/Classification/StudentclassifyClass</span>
			</ul> -->
			<div id="adminsession">
				<p>Powered By 内蒙古科技大学教务处
				</p>
				
			</div>
		</div>
		<div id="contentright">
			<iframe class='ifcontent' src='__ROOT__/admin.php/Admin/Index/Welcome'></iframe>
		</div>
	</div>	
</div>
</div>
</body>
</html>