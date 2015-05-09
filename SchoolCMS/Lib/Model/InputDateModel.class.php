<?php

class InputDateModel {
	
	private $m_InputDateDataArray;  //参数配置项数组
	private $m_DateName;  //前面显示的名称
	private $m_Name;  //name名称
	private $m_Prompt;  //input里面提示文字
	private $m_Default;  //默认值
	private $m_Default2;  //默认值2
	private $m_ClassName;  //input的class名称
	private $m_ClassDateName;  //前面显示的名称的class名称
	private $m_Required;  //是否开启红色星号
	private $m_InputBan;  //input是否禁止
	private $m_Width;  //input的宽度
	private $m_Type;  //时间类型
	private $m_Verification;  //验证提示
	
	private $m_DateTime;  //时间戳
	
	/* 构造方法 */
	public function __construct($Configuration = array())
	{
		/* 初始化属性数据 */
		$this->m_InputDateDataArray = array();
		
		/* 基础数据设置 */
		$this->SetInputDateDataArray($Configuration );
		$this->SetBasisData();
	}
	
	/* 设置配置项数据 */
	private function SetInputDateDataArray($Configuration)
	{
		/* 判断配置项的数据是否为空 */
		if(false == empty($Configuration)) {
			$this->m_InputDateDataArray = $Configuration;
		} else {
			$this->m_InputDateDataArray = array();
		}
	}
	
	/* 处理判断数组中是否存在某个键名 */
	private function Setuppase($Property, $Key, $Content)
	{
		/* 判断 $Key 是否在数组中存在的键名 */
		if(true == array_key_exists($Key, $this->m_InputDateDataArray)) {
			$this->$Property = $this->m_InputDateDataArray["$Key"];
		} else {
			$this->$Property = $Content;
		}
	}
	
	/* 基础数据设置 */
	private function SetBasisData()
	{
		$this->SetDateName();
		$this->SetName();
		$this->SetPrompt();
		$this->SetDefaul();
		$this->SetClassName();
		$this->SetClassDateName();
		$this->SetRequired();
		$this->SetInputBan();
		$this->SetWidth();
		$this->SetType();
		$this->SetVerification();
	}

	/* 设置前面显示的名称 */
	private function SetDateName()
	{
		$this->Setuppase('m_DateName', 'datename', null);
	}
	
	/* 设置name名称 */
	private function SetName()
	{
		$this->Setuppase('m_Name', 'name', null);
	}
	
	/* 设置input里面提示文字 */
	private function SetPrompt()
	{
		$this->Setuppase('m_Prompt', 'prompt', null);
	}
	
	/* 设置默认值 */
	private function SetDefaul()
	{
		$this->Setuppase('m_Default', 'default', null);
		$this->Setuppase('m_Default2', 'default2', null);
	}
	
	/* 设置input的class名称 */
	private function SetClassName()
	{
		$this->Setuppase('m_ClassName', 'classname', null);
	}
	
	/* 设置前面显示的名称的class名称 */
	private function SetClassDateName()
	{
		$this->Setuppase('m_ClassDateName', 'classdatename', null);
	}
	
	/* 设置是否开启显示红色星号） */
	private function SetRequired()
	{
		$this->Setuppase('m_Required', 'required', 0);
	}
	
	/* 设置input是否禁止 */
	private function SetInputBan()
	{
		$this->Setuppase('m_InputBan', 'ban', 0);
	}

	/* 设置input的宽度 */
	private function SetWidth()
	{
		$this->Setuppase('m_Width', 'width', '100px');
	}
	
	/* 设置时间类型（默认:单个时间框） */
	private function SetType()
	{
		$this->Setuppase('m_Type', 'type', 1);
	}
	
	/* 设置是否开启验证提示（0:不验证,1:框后面提示,2:弹框提示） */
	private function SetVerification()
	{
		$this->Setuppase('m_Verification', 'verification', 0);
	}
	
	
	/* 返回拼接好的html代码（包括js代码） */
	public function GetInputDateHtmlInfo()
	{
		if($this->m_Type == 1) {
			$Html = '<span class="'.$this->m_ClassDateName.'">'.$this->m_DateName.'</span><input type="text" name="'.$this->m_Name.'" placeholder="'.$this->m_Prompt.'" value="'.$this->m_Default.'" id="'.$this->m_Name.'"  class="Wdate '.$this->m_ClassName.' input_outdate inputdate" style="margin-right:3px;width:'.$this->m_Width.';" onClick="WdatePicker()"';
			
			/* 是否开启禁止 */
			if($this->m_InputBan == 1) {
				$Html .= ' disabled="disabled"/>';
			} elseif($this->m_InputBan == 0) {
				$Html .= ' />';
			}
			
			/* 是否开启红色星号 */
			if($this->m_Required == 1) {
				$Html .= '<span style="color:#F00;">*</span>';
			}
			
			/* 是否开启提示信息 */
			if($this->m_Verification != 0) {
				$Html .= '<span class="'.$this->m_Name.'verifytips"></span>';
			}
			/* 判断是否开启验证，处理状态值 */
			if($this->m_Verification == 0) {
				$Statu = 1;
			} else {
				$Statu = 0;
			}
			$Html .= '<span class="statu'.$this->m_Name.'" style="display:none;">'.$Statu.'</span><span class="datetime'.$this->m_Name.'" style="display:none;">0</span>';
			
		} else if($this->m_Type == 2) {
			$Html = '<span class="'.$this->m_ClassDateName.'">'.$this->m_DateName.'</span><input id="'.$this->m_Name.'d5221" name="'.$this->m_Name.'1" value="'.$this->m_Default.'" class="Wdate '.$this->m_Name.'1 '.$this->m_ClassName.' input_outdate inputdate" type="text" style="width:'.$this->m_Width.';" onFocus="var '.$this->m_Name.'d5222=$dp.$(\''.$this->m_Name.'d5222\');WdatePicker({onpicked:function(){'.$this->m_Name.'d5222.focus();},maxDate:\'#F{$dp.$D(\\\''.$this->m_Name.'d5222\\\')}\'})"';
			if($this->m_InputBan == 1) {
				$Html .= ' disabled="disabled"/>';
			} elseif($this->m_InputBan == 0) {
				$Html .= ' />';
			}

			
			$Html .= ' 至 <input id="'.$this->m_Name.'d5222" name="'.$this->m_Name.'2" value="'.$this->m_Default2.'" class="Wdate '.$this->m_Name.'2 '.$this->m_ClassName.' input_outdate inputdate" type="text" style="margin-right:3px;width:'.$this->m_Width.';" onFocus="WdatePicker({minDate:\'#F{$dp.$D(\\\''.$this->m_Name.'d5221\\\')}\'})"';
			if($this->m_InputBan == 1) {
				$Html .= ' disabled="disabled"/>';
			} elseif($this->m_InputBan == 0) {
				$Html .= ' />';
			}
		}
		
		$ScriptJs = '<style type="text/css">
						.input_outdate{ padding:3px; background:#F5F5F5; border-style:solid; border-width:1px; border-color:#ABADB3 #E2E3EA #E2E3EA #ABADB3; outline:none; -moz-border-radius:4px; -webkit-border-radius:4px; border-radius:4px; resize:none; }
					</style>
		
					<script type="text/javascript" charset="utf8">
					$(document).ready(function (){
						
						//事件样式处理
						$(".input_outdate").mousedown(function() {
							$(this).addClass("Wdatess");
						}).blur(function() {
							$(this).removeClass("Wdatess");
						});
						
						$(".inputdate").mouseover(function() {
							$(this).addClass("Wdates");
						}).mouseout(function() {
							$(this).removeClass("Wdates");
						});
						
						$(".inputdate").focus(function() {
							$(this).addClass("Wdates");
						}).focusout(function() {
							$(this).removeClass("Wdates");
						});
						
						//提示方法 classname:class，content:提示内容，type:提示类型（0:失败，1:成功）
						function jsprompt(classname, content, type, inputthiscss) {
							if(type == 0) {
								$(classname).html("<img src=\''.__ROOT__.'/Static/Img/Admin/form2.png\' style=\"margin:0px 3px -3px 0px;border:0px;\" /><span style=\"color:#F33101;font-size:12px;\">"+content+"</span>");
								$(inputthiscss).css("border", "1px solid #F00");
							} else if(type == 1) {
								$(classname).html("<img src=\''.__ROOT__.'/Static/Img/Admin/form1.png\' style=\"margin:0px 3px -3px 0px;border:0px;\" /><span style=\"color:#5EBB29;font-size:12px;\">"+content+"</span>");
								$(inputthiscss).css("border", "1px solid #5EBB29");
							} else if(type == 2) {
								$(classname).html(content);
							}
						}
						
						var verificationstatu = '.$this->m_Verification.';
						if(verificationstatu != 0) {
							$("#'.$this->m_Name.'").blur(function() {
								var value = $(this).val();
								if(value != "") {
									var telsy = new RegExp(/^[0-9]{4}[-]{1}[0-9]{2}[-]{1}[0-9]{2}$/);
									if(telsy.test(value)) {
										if(verificationstatu == 1) {
											jsprompt(".'.$this->m_Name.'verifytips", "格式正确！", 1, "#'.$this->m_Name.'");
										} else if(verificationstatu == 2) {
											alert("格式正确！");
										}
										$(".statu'.$this->m_Name.'").text(1);
										
										//获取时间的值转成时间戳
										var inputdatetime = $("#'.$this->m_Name.'").val();
										var inputdatetime = inputdatetime.replace(/-/g,"/"); // 将-替换成/，因为下面这个构造函数只支持/分隔的日期字符串
										var inputdatetime = new Date(inputdatetime); // 构造一个日期型数据，值为传入的字符串
										var inputdatetime = inputdatetime.getTime();
										if(inputdatetime == "" || isNaN(inputdatetime)) {
											$(".datetime'.$this->m_Name.'").text(0);
										} else {
											$(".datetime'.$this->m_Name.'").text(inputdatetime);
										}
									} else {
										if(verificationstatu == 1) {
											jsprompt(".'.$this->m_Name.'verifytips", "格式错误！", 0, "#'.$this->m_Name.'");
										} else if(verificationstatu == 2) {
											alert("格式错误！");
										}
										$(".statu'.$this->m_Name.'").text(0);
									}
								} else {
									if(verificationstatu == 1) {
										jsprompt(".'.$this->m_Name.'verifytips", "不能为空！", 0, "#'.$this->m_Name.'");
									} else if(verificationstatu == 2) {
										alert("不能为空！");
									}
									$(".statu'.$this->m_Name.'").text(0);
								}
							});
						}
						
					});
			</script>';
			
		return $Html.$ScriptJs;
	}
	
}