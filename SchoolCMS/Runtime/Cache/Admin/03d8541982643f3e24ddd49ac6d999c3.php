<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
	<meta http-equiv='content-type' content='text/html;charset=utf-8'>
	<link rel="stylesheet" type="text/css" href="__ROOT__/Static/Css/Common/common.css" />
	<link rel="stylesheet" type="text/css" href="__ROOT__/Static/Css/Admin/welcome.css" />
</head>
<body>
<div class="welcome">
	<div id="home_toptip"></div>
	<h2 class="h_a">系统信息</h2>
	<div class="home_info">
		<ul>
			<li>
				<em>学号</em>
				<span>  <?php echo $info["XH"]; ?> </span>
			</li>
			<li>
				<em>姓名</em>
				<span><?php echo $info["XM"]; ?></span>
			</li>
			<li>
				<em>性别</em>
				<span><?php echo $info["XB"]; ?></span>
			</li>

			<li>
				<em>学院</em>
				<span><?php echo $info["XSM"]; ?></span>
			</li>
			<li>
				<em>专业</em>
				<span><?php echo $info["ZYM"]; ?></span>
			</li>
			<li>
				<em>年级</em>
				<span><?php echo $info["NJDM"]; ?></span>
			</li>
			<li>
				<em>班级</em>
				<span><?php echo $info["BJH"]; ?></span>
			</li>
			
		</ul>
	</div>
	<h2 class="h_a">开发信息</h2>
	<div class="home_info" id="home_devteam">
		<ul>
			<li>
				<em>版权所有</em>
				<span>内蒙古科技大学教务处</span>
			</li>

		</ul>
	</div>
</div>
</body>
</html>