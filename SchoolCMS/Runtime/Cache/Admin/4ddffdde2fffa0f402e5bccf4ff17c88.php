<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
	<meta http-equiv='content-type' content='text/html;charset=utf-8' />
	<title>内蒙古科技大学教务处管理系统</title>
	<script type="text/javascript" src="__ROOT__/Static/Js/Common/jquery.js"></script>
	<script type="text/javascript" src="__ROOT__/Static/Js/Common/common.js"></script>
	<script type="text/javascript" src="__ROOT__/Static/Js/Common/My97DatePicker/WdatePicker.js"></script>
	<link rel="stylesheet" type="text/css" href="__ROOT__/Static/Css/Common/common.css" />
	<script type="text/javascript" src="__ROOT__/Static/Js/Admin/studentinterface.js"></script>
	<link rel="stylesheet" type="text/css" href="__ROOT__/Static/Css/Admin/studentinterface.css" />
	<script type='text/javascript' >
		var SITE_PATH = '<?php echo ($SITE_ROOT); ?>';  //定义项目所在绝对地址
		var tplroot = '__ROOT__/admin.php';  //定义js变量（项目根目录）
		var root = '__ROOT__';  //定义js变量（网站根目录）
	</script>
</head>
<body>
<div id="studentinterface">
	<form action="<?php echo U('delete');?>" method="post" >
		<table>
			<tr>
				<td>学号：<?php  echo $info["XH"]; ?></td>
				<td>姓名：<?php  echo $info["XM"]; ?></td>
			</tr>
			<tr>
				<td>现在所在学院：<?php echo $info["XXSM"]; ?></td>
				<td>现在所在专业:<?php echo $info["XZYM"]; ?></td>
			</tr>
			<tr>
				<td>年级：<?php echo $info["NJDM"]; ?></td>
				<td>班级:<?php echo $info["XBJ"]; ?></td>
			</tr>

			<tr >
				<td >拟转入学院: <?php echo $info["NZRXSM"]; ?></td>
				<td >拟转入专业: <?php echo $info["NZRZYM"]; ?></td>

				
				

			</tr>
			
		</table>
			<input type="hidden" name="delete" value="1">	
			<input type="submit" class="adminsubmit" id="submit" name="submit" title="提交" value="删除" />
		

	</form>
</div>
</body>
</html>