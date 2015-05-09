<?php

require SITE_PATH.'/SchoolCMS/Const/Admin/Model/InputText.const';
class InputTextModel
{
	
	private $m_InputTextDataArray;  //参数配置项数组
	private $m_TextName;  //前面显示的名称
	private $m_Name;  //name名称
	private $m_Prompt;  //input里面提示文字
	private $m_Default;  //默认值
	private $m_ClassName;  //input的class名称
	private $m_ClassTextName;  //前面显示的名称的class名称
	private $m_PromptType;  //验证提示类型（0:不验证，1:框后面提示，2:弹窗提示）
	private $m_VerificationType;  //验证内容类型（0:只验证长度,1:纯数字,2:手机,3:座机,4:邮箱,5:URL,6:ip,7:验证价格）
	private $m_LengthSmall;  //设置验证长度（最小长度的数值）
	private $m_LengthLarge;  //设置验证长度（最大长度的数值）
	private $m_Required;  //是否开启红色星号
	private $m_Type;  //input类型
	private $m_InputBan;  //input是否禁止
	private $m_Mousedown;  //mousedown事件提示信息
	private $m_Width;  //input的宽度
	
	/* 构造方法 */
	public function __construct($Configuration = array())
	{
		/* 初始化属性数据 */
		$this->m_InputTextDataArray = array();
		
		/* 基础数据设置 */
		$this->SetInputTextDataArray($Configuration );
		$this->SetBasisData();
	}
	
	/* 设置配置项数据 */
	private function SetInputTextDataArray($Configuration)
	{
		/* 判断配置项的数据是否为空 */
		if(false == empty($Configuration)) {
			$this->m_InputTextDataArray = $Configuration;
		} else {
			$this->m_InputTextDataArray = array();
		}
	}
	
	/* 处理判断数组中是否存在某个键名 */
	private function Setuppase($Property, $Key, $Content)
	{
		/* 判断 $Key 是否在数组中存在的键名 */
		if(true == array_key_exists($Key, $this->m_InputTextDataArray)) {
			$this->$Property = $this->m_InputTextDataArray["$Key"];
		} else {
			$this->$Property = $Content;
		}
	}
	
	/* 基础数据设置 */
	private function SetBasisData()
	{
		$this->SetTextName();
		$this->SetName();
		$this->SetPrompt();
		$this->SetDefaul();
		$this->SetClassName();
		$this->SetClassTextName();
		$this->SetPromptTypee();
		$this->SetVerificationType();
		$this->SetLengthSmall();
		$this->SetLengthLarge();
		$this->SetRequired();
		$this->SetType();
		$this->SetInputBan();
		$this->SetMousedown();
		$this->SetWidth();
	}

	/* 设置前面显示的名称 */
	private function SetTextName()
	{
		$this->Setuppase('m_TextName', 'textname', null);
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
	}
	
	/* 设置input的class名称 */
	private function SetClassName()
	{
		$this->Setuppase('m_ClassName', 'classname', null);
	}
	
	/* 设置前面显示的名称的class名称 */
	private function SetClassTextName()
	{
		$this->Setuppase('m_ClassTextName', 'classtextname', null);
	}
	
	/* 设置验证提示类型（0:不验证，1:框后面提示，2:弹窗提示 | 用于用户自定义：10:不验证(后面提示), 20:不验证(弹窗提示)） */
	private function SetPromptTypee()
	{
		$this->Setuppase('m_PromptType', 'prompttype', 0);
	}
	
	/* 设置验证内容类型（0:只验证长度,1:纯数字,2:手机,3:座机,4:邮箱,5:URL,6:ip,7:验证价格） */
	private function SetVerificationType()
	{
		$this->Setuppase('m_VerificationType', 'verificationtype', 0);
	}
	
	/* 设置验证长度（最小长度的数值） */
	private function SetLengthSmall()
	{
		$this->Setuppase('m_LengthSmall', 'lengthsmall', 0);
	}
	
	/* 设置验证长度（最大长度的数值） */
	private function SetLengthLarge()
	{
		$this->Setuppase('m_LengthLarge', 'lengthlarge', 0);
	}
	
	/* 设置是否开启显示红色星号） */
	private function SetRequired()
	{
		$this->Setuppase('m_Required', 'required', 0);
	}
	
	/* 设置input类型（默认:text） */
	private function SetType()
	{
		$this->Setuppase('m_Type', 'type', 'text');
	}
	
	/* 设置input是否禁止 */
	private function SetInputBan()
	{
		$this->Setuppase('m_InputBan', 'ban', 0);
	}
	
	/* 设置mousedown事件提示信息 */
	private function SetMousedown()
	{
		$this->Setuppase('m_Mousedown', 'mousedown', 0);
	}
	
	/* 设置input的宽度 */
	private function SetWidth()
	{
		$this->Setuppase('m_Width', 'width', '180px');
	}
	
	/* 返回拼接好的html代码（包括js代码） */
	public function GetInputTextHtmlInfo()
	{
		$Html = '<span class="'.$this->m_ClassTextName.'">'.$this->m_TextName.'</span><input type="'.$this->m_Type.'" name="'.$this->m_Name.'" placeholder="'.$this->m_Prompt.'" value="'.$this->m_Default.'" id="'.$this->m_Name.'"  class="inputthis'.$this->m_Name.' '.$this->m_ClassName.' inputcommon input_out" style="margin-right:3px;width:'.$this->m_Width.';"';
		
		if($this->m_InputBan == 1) {
			$Html .= ' disabled="disabled"/>';
		} else {
			$Html .= ' />';
		}
		
		if($this->m_Required == 1) {
			$Html .= '<span style="color:#F00;">*</span> ';
		}
		
		$Html .= '<span class="prompttype'.$this->m_Name.'" style="display:none;">'.$this->m_PromptType.'</span>';
		
		if(0 == $this->m_PromptType || 10 == $this->m_PromptType || 20 == $this->m_PromptType) {
			$Statu = 1;
		} else {
			$Statu = 0;
		}
		
		$Html .= '<span class="verifytips'.$this->m_Name.'"></span><span class="statu'.$this->m_Name.' inputtextclass" style="display:none;">'.$Statu.'</span>';
		
		$ScriptJs = '<style type="text/css">
						.input_move, .input_moves{ padding:3px; color:#333 !important; background:#FFF !important; border-style:solid; border-width:1px; outline:none; -moz-border-radius:4px; -webkit-border-radius:4px; border-radius:4px; resize:none; border:1px solid #4FACF5 !important; box-shadow:0 0 5px #4FACF5 !important; }
						.input_out { padding:3px; background:#F5F5F5; border-style:solid; border-width:1px; border-color:#ABADB3 #E2E3EA #E2E3EA #ABADB3; outline:none; -moz-border-radius:4px; -webkit-border-radius:4px; border-radius:4px; resize:none; }
					</style>
		
					<script type="text/javascript" charset="utf8">
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
					
					//验证长度方法
					function verifylength(value, lengthsmall, lengthlarge, prompttype, tipcalls, statu, inputthiscss) {
						//如果这个条件满足，则执行验证长度
						if((lengthsmall != 0) && (lengthlarge != 0) && (lengthsmall < lengthlarge) || (lengthsmall == lengthlarge)) {
							if(lengthsmall != 0 && lengthlarge != 0) {
								if(lengthsmall == lengthlarge) {
									if(value.length == lengthsmall) {
										if(prompttype == 1) {
											jsprompt(tipcalls, "'.MODEL_INPUTTEXT_OK.'", 1, inputthiscss);
											$(statu).text(1);
										} else if(prompttype == 2) {
											alert("'.MODEL_INPUTTEXT_OK.'");
											$(statu).text(1);
											$(inputthiscss).css("border", "1px solid #5EBB29");
										} else if(prompttype == 3) {
											$(statu).text(1);
											$(inputthiscss).css("border", "1px solid #5EBB29");
										}
									} else {
										if(prompttype == 1) {
											jsprompt(tipcalls, "'.MODEL_INPUTTEXT_LENGTEQUIL.'"+lengthsmall+"'.MODEL_INPUTTEXT_LENGTSTR.'", 0, inputthiscss);
											$(statu).text(0);
										} else if(prompttype == 2) {
											alert("'.MODEL_INPUTTEXT_LENGTEQUIL.'"+lengthsmall+"'.MODEL_INPUTTEXT_LENGTSTR.'");
											$(statu).text(0);
											$(inputthiscss).css("border", "1px solid #F00");
										} else if(prompttype == 3) {
											$(statu).text(0);
											$(inputthiscss).css("border", "1px solid #F00");
										}
									}
								} else {
									if(value.length < lengthsmall) {
										if(prompttype == 1) {
											jsprompt(tipcalls, "'.MODEL_INPUTTEXT_LENGTHSMALL.'"+lengthsmall+"'.MODEL_INPUTTEXT_LENGTSTR.'", 0, inputthiscss);
											$(statu).text(0);
										} else if(prompttype == 2) {
											alert("'.MODEL_INPUTTEXT_LENGTHSMALL.'"+lengthsmall+"'.MODEL_INPUTTEXT_LENGTSTR.'");
											$(statu).text(0);
											$(inputthiscss).css("border", "1px solid #F00");
										} else if(prompttype == 3) {
											$(statu).text(0);
											$(inputthiscss).css("border", "1px solid #F00");
										}
									} else if(value.length > lengthlarge) {
										if(prompttype == 1) {
											jsprompt(tipcalls, "'.MODEL_INPUTTEXT_LENGTHARGE.'"+lengthlarge+"'.MODEL_INPUTTEXT_LENGTSTR.'", 0, inputthiscss);
											$(statu).text(0);
										} else if(prompttype == 2) {
											alert("'.MODEL_INPUTTEXT_LENGTHARGE.'"+lengthlarge+"'.MODEL_INPUTTEXT_LENGTSTR.'");
											$(statu).text(0);
											$(inputthiscss).css("border", "1px solid #F00");
										} else if(prompttype == 3) {
											$(statu).text(0);
											$(inputthiscss).css("border", "1px solid #F00");
										}
									} else {
										if(prompttype == 1) {
											jsprompt(tipcalls, "'.MODEL_INPUTTEXT_OK.'", 1, inputthiscss);
											$(statu).text(1);
										} else if(prompttype == 2) {
											alert("'.MODEL_INPUTTEXT_OK.'");
											$(statu).text(1);
											$(inputthiscss).css("border", "1px solid #5EBB29");
										} else if(prompttype == 3) {
											$(statu).text(1);
											$(inputthiscss).css("border", "1px solid #5EBB29");
										}
									}
								}
							}
						}
					}
					

					//判断是否开启了 mousedown 事件提示信息
					var mousedown = "'.$this->m_Mousedown.'";
					if(mousedown != 0) {
						$("#'.$this->m_Name.'").mousedown(function() {
							var prompttype = '.$this->m_PromptType.';  //验证提示方式
							if(prompttype == 1 || prompttype == 10) {
								jsprompt(".verifytips'.$this->m_Name.'", "'.$this->m_Mousedown.'", 2, "");
							} else if(prompttype == 2 || prompttype == 20) {
								alert("'.$this->m_Mousedown.'");
							}	
						});
					}
					
					
					//验证部分
					$(document).ready(function (){
						
						//事件样式处理
						$(".input_out").mousedown(function() {
							$(this).addClass("input_move");
						}).blur(function() {
							$(this).removeClass("input_move");
						});
						
						$(".inputcommon").mouseover(function() {
							$(this).addClass("input_moves");
						}).mouseout(function() {
							$(this).removeClass("input_moves");
						});
						
						$(".inputcommon").focus(function() {
							$(this).addClass("input_moves");
						}).focusout(function() {
							$(this).removeClass("input_moves");
						});
						
						//验证代码
						$("#'.$this->m_Name.'").blur(function() {
							var value = $(this).val();
							var value = value.replace(/^\s+|\s+$/g,"");  //去除左右空格
							var prompttype = '.$this->m_PromptType.';  //验证提示方式
							var verificationtype = '.$this->m_VerificationType.';  //验证数据类型
							var lengthsmall = '.$this->m_LengthSmall.';  //验证长度（最小长度的数值）
							var lengthlarge = '.$this->m_LengthLarge.';  //验证长度（最大长度的数值）
							
							//如果大于0 就代表需要验证
							if(prompttype != 0) {
								//将获取到的值经过上面去掉左右空格后，重新放到input里面去
								$(this).val(value);
								//判断是否为空
								if(value != "") {
									//如果验证类型为0，不验证任何格式（只验证长度）
									if(verificationtype == 0) {
										if((lengthsmall != 0) && (lengthlarge != 0) && (lengthsmall < lengthlarge) || (lengthsmall == lengthlarge)) {
											if(lengthsmall != 0 && lengthlarge != 0) {
												//调用验证长度方法
												verifylength(value, lengthsmall, lengthlarge, prompttype, ".verifytips'.$this->m_Name.'", ".statu'.$this->m_Name.'", ".inputthis'.$this->m_Name.'");
											} else {
												if(prompttype == 1) {
													jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_OK.'", 1, ".inputthis'.$this->m_Name.'");
													$(".statu'.$this->m_Name.'").text(1);
												} else if(prompttype == 2) {
													alert("'.MODEL_INPUTTEXT_OK.'");
													$(".statu'.$this->m_Name.'").text(1);
													$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
												} else if(prompttype == 3) {
													$(".statu'.$this->m_Name.'").text(1);
													$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
												}
											}
										}
									
									//验证类型 ：1 验证纯数字
									} else if(verificationtype == 1) {
										//正则验证
										var telsy = new RegExp(/^[0123456789]+$/);
										if(telsy.test(value)) {
											//如果这个条件成功，就证明当前没有定义长度验证（最大值不能小于最小值）
											if((lengthsmall == 0) && (lengthlarge == 0) || (lengthlarge < lengthsmall)) {
												if(prompttype == 1) {
													jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_OK.'", 1, ".inputthis'.$this->m_Name.'");
													$(".statu'.$this->m_Name.'").text(1);
												} else if(prompttype == 2) {
													alert("'.MODEL_INPUTTEXT_OK.'");
													$(".statu'.$this->m_Name.'").text(1);
													$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
												} else if(prompttype == 3) {
													$(".statu'.$this->m_Name.'").text(1);
													$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
												}
											}
											
											//调用验证长度方法
											verifylength(value, lengthsmall, lengthlarge, prompttype, ".verifytips'.$this->m_Name.'", ".statu'.$this->m_Name.'", ".inputthis'.$this->m_Name.'");
											
										} else {
											if(prompttype == 1) {
												jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_DIGITALNO.'", 0, ".inputthis'.$this->m_Name.'");
												$(".statu'.$this->m_Name.'").text(0);
											} else if(prompttype == 2) {
												alert("'.MODEL_INPUTTEXT_DIGITALNO.'");
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											} else if(prompttype == 3) {
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											}
										}
									
									//验证类型 ：2 验证手机号码
									} else if(verificationtype == 2) {
										if(value.length == 11) {
											var telsy = new RegExp(/^(13[0123456789]{1}\d{8}$|^15[1235689]{1}\d{8}$|^18[26789]{1}\d{8})|(0[0-9]{1,3}\-?[0-9]{7,8})$/);
											if(telsy.test(value)) {
												if(prompttype == 1) {
													jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_MOBILEOK.'", 1, ".inputthis'.$this->m_Name.'");
													$(".statu'.$this->m_Name.'").text(1);
												} else if(prompttype == 2) {
													alert("'.MODEL_INPUTTEXT_MOBILEOK.'");
													$(".statu'.$this->m_Name.'").text(1);
													$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
												} else if(prompttype == 3) {
													$(".statu'.$this->m_Name.'").text(1);
													$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
												}
											} else {
												if(prompttype == 1) {
													jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_MOBILENO.'", 0, ".inputthis'.$this->m_Name.'");
													$(".statu'.$this->m_Name.'").text(0);
												} else if(prompttype == 2) {
													alert("'.MODEL_INPUTTEXT_MOBILENO.'");
													$(".statu'.$this->m_Name.'").text(0);
													$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
												} else if(prompttype == 3) {
													$(".statu'.$this->m_Name.'").text(0);
													$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
												}
											}
										} else {
											if(prompttype == 1) {
												jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_LENGTEQUIL.'11'.MODEL_INPUTTEXT_LENGTSTR.'", 0, ".inputthis'.$this->m_Name.'");
												$(".statu'.$this->m_Name.'").text(0);
											} else if(prompttype == 2) {
												alert("'.MODEL_INPUTTEXT_LENGTEQUIL.'11'.MODEL_INPUTTEXT_LENGTSTR.'");
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											} else if(prompttype == 3) {
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											}
										}
									
									//验证类型 ：3 验证座机号码
									} else if(verificationtype == 3) {
										var telsy = new RegExp(/^0\d{2,3}-?\d{6,8}\d$/);
										if(telsy.test(value)) {
											if(prompttype == 1) {
												jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_LANDLINEOK.'", 1, ".inputthis'.$this->m_Name.'");
												$(".statu'.$this->m_Name.'").text(1);
											} else if(prompttype == 2) {
												alert("'.MODEL_INPUTTEXT_LANDLINEOK.'");
												$(".statu'.$this->m_Name.'").text(1);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
											} else if(prompttype == 3) {
												$(".statu'.$this->m_Name.'").text(1);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
											}
										} else {
											if(prompttype == 1) {
												jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_LANDLINENO.'", 0, ".inputthis'.$this->m_Name.'");
												$(".statu'.$this->m_Name.'").text(0);
											} else if(prompttype == 2) {
												alert("'.MODEL_INPUTTEXT_LANDLINENO.'");
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											} else if(prompttype == 3) {
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											}
										}
										
									//验证类型 ：4 验证邮箱
									} else if(verificationtype == 4) {
										var telsy = new RegExp(/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z]+)+$/);
										if(telsy.test(value)) {
											if(prompttype == 1) {
												jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_MAILOK.'", 1, ".inputthis'.$this->m_Name.'");
												$(".statu'.$this->m_Name.'").text(1);
											} else if(prompttype == 2) {
												alert("'.MODEL_INPUTTEXT_MAILOK.'");
												$(".statu'.$this->m_Name.'").text(1);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											} else if(prompttype == 3) {
												$(".statu'.$this->m_Name.'").text(1);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											}
										} else {
											if(prompttype == 1) {
												jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_MAILNO.'", 0, ".inputthis'.$this->m_Name.'");
												$(".statu'.$this->m_Name.'").text(0);
											} else if(prompttype == 2) {
												alert("'.MODEL_INPUTTEXT_MAILNO.'");
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											} else if(prompttype == 3) {
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											}
										}
									
									//验证类型 ：5 验证URL
									} else if(verificationtype == 5) {
										var telsy = new RegExp(/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/);
										if(telsy.test(value)) {
											if(prompttype == 1) {
												jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_URLOK.'", 1, ".inputthis'.$this->m_Name.'");
												$(".statu'.$this->m_Name.'").text(1);
											} else if(prompttype == 2) {
												alert("'.MODEL_INPUTTEXT_URLOK.'");
												$(".statu'.$this->m_Name.'").text(1);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
											} else if(prompttype == 3) {
												$(".statu'.$this->m_Name.'").text(1);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
											}
										} else {
											if(prompttype == 1) {
												jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_URLNO.'", 0, ".inputthis'.$this->m_Name.'");
												$(".statu'.$this->m_Name.'").text(0);
											} else if(prompttype == 2) {
												alert("'.MODEL_INPUTTEXT_URLNO.'");
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											} else if(prompttype == 3) {
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											}
										}
									
									//验证类型 ：6 验证IP
									} else if(verificationtype == 6) {
										var telsy = new RegExp(/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/);
										if(telsy.test(value)) {
											if(prompttype == 1) {
												jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_IPOK.'", 1, ".inputthis'.$this->m_Name.'");
												$(".statu'.$this->m_Name.'").text(1);
											} else if(prompttype == 2) {
												alert("'.MODEL_INPUTTEXT_IPOK.'");
												$(".statu'.$this->m_Name.'").text(1);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
											} else if(prompttype == 3) {
												$(".statu'.$this->m_Name.'").text(1);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
											}
										} else {
											if(prompttype == 1) {
												jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_IPNO.'", 0, ".inputthis'.$this->m_Name.'");
												$(".statu'.$this->m_Name.'").text(0);
											} else if(prompttype == 2) {
												alert("'.MODEL_INPUTTEXT_IPNO.'");
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											} else if(prompttype == 3) {
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											}
										}
									//验证价格
									} else if(verificationtype == 7) {
										var telsy = new RegExp(/^[0-9]{1,10}(\.[0-9]{1,2})?$/);
										if(telsy.test(value)) {
											if(prompttype == 1) {
												jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_OK.'", 1, ".inputthis'.$this->m_Name.'");
												$(".statu'.$this->m_Name.'").text(1);
											} else if(prompttype == 2) {
												alert("'.MODEL_INPUTTEXT_OK.'");
												$(".statu'.$this->m_Name.'").text(1);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
											} else if(prompttype == 3) {
												$(".statu'.$this->m_Name.'").text(1);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #5EBB29");
											}
										} else {
											if(prompttype == 1) {
												jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_PRICENO.'", 0, ".inputthis'.$this->m_Name.'");
												$(".statu'.$this->m_Name.'").text(0);
											} else if(prompttype == 2) {
												alert("'.MODEL_INPUTTEXT_PRICENO.'");
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											} else if(prompttype == 3) {
												$(".statu'.$this->m_Name.'").text(0);
												$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
											}
										}
									}
									
								} else {
									if(prompttype == 1) {
										jsprompt(".verifytips'.$this->m_Name.'", "'.MODEL_INPUTTEXT_AIR.'", 0, ".inputthis'.$this->m_Name.'");
										$(".statu'.$this->m_Name.'").text(0);
									} else if(prompttype == 2) {
										alert("'.MODEL_INPUTTEXT_AIR.'");
										$(".statu'.$this->m_Name.'").text(0);
										$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
									} else if(prompttype == 3) {
										$(".statu'.$this->m_Name.'").text(0);
										$(".inputthis'.$this->m_Name.'").css("border", "1px solid #F00");
									}
								}
							}
						});
					});
			</script>';
			
		return $Html.$ScriptJs;
	}
	
}
?>
