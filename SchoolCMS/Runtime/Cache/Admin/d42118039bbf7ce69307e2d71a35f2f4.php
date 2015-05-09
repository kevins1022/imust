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
	<form action="__SELF__" method="post" >
		<table>
			<tr>
				<td>学号：<?php  echo $info["XH"]; ?></td>
				<td>姓名：<?php  echo $info["XM"]; ?></td>
			</tr>
			<tr>
				<td>现在所在学院：<?php echo $info["XSM"]; ?></td>
				<td>现在所在专业:<?php echo $info["ZYM"]; ?></td>
			</tr>
			<tr>
				<td>年级：<?php echo $info["NJDM"]; ?></td>
				<td>班级:<?php echo $info["BJH"]; ?></td>
			</tr>

			<tr >
				<td colspan="2">拟转入学院及专业：
				<select name="xueyuan" id="xueyuan">
				<option value="0">
					请选择学院
				</option>

				<?php foreach ($xueyuan as $key => $value):?>
					<option value="<?php echo $value['XSM']; ?>">
					<?php echo $value['XSM']; ?>
					</option>
				<?php endforeach; ?>
				</select>

				<select name="zhuanye" id="zhuanye">
					<option value="0">
						请选择专业
					</option>

				
				</select>

				</td>
				<script>
					$(function(){
						
						$("#xueyuan").change(function(){
							var xueyuan=$("#xueyuan").val();
							$.ajax({
								url:"<?php echo U('TurnAjax');?>",
								data:{'xueyuan':xueyuan},
								type:"POST",
								dataType:"json",
								success:function(msg){
									$("#zhuanye").html("");
									$("#zhuanye").append("<option name='0'>请选择专业</option>");
									//console.log(msg);
									$.each(msg,function(key,value){
										$("#zhuanye").append("<option value='"+value.ZYM+"'>"+value.ZYM+"</option>");
										//console.log(value.ZYM);

									});
									

								}

							});
							//alert(xueyuan);
						});

					});
				</script>
				

			</tr>
			
		</table>
		
		<input type="submit" class="adminsubmit" id="submit" name="submit" title="提交" value="提交" />
	</form>
</div>
</body>
</html>