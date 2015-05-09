<?php

class GridModel
{
	private $m_GridDataArray;  //原始数据(二位数组)
	private $m_FieldDataArray;  //需要显示的字段数据
	private $m_ConfigurationArray;  //配置项数据
	private $m_OperatingArray;  //操作项数据,1：跳转，2：click事件，3：双击事件
	private $m_FieldID;  //id
	private $m_Checkbox;  //是否开启复选框状态
	private $m_Name;  //name名称
	
	
	/* 构造方法 */
	public function __construct($GridDataArray = array(), $FieldDataArray = array(), $Configuration = array(), $Operating = array())
	{
		/* 初始化属性数据 */
		$this->m_GridDataArray = array();
		$this->m_FieldDataArray = array();
		$this->m_Configuration = array();
		$this->m_OperatingData = array();
		
		/* 基础数据设置 */
		$this->SetGridDataArray($GridDataArray);
		$this->SetFieldDataArray($FieldDataArray);
		$this->SetConfiguration($Configuration);
		$this->SetOperating($Operating);
		$this->SetBasisData();

	}
	
	/*
		设置原始数据
	*/
	private function SetGridDataArray($GridDataArray)
	{
		if(false == empty($GridDataArray)) {
			$this->m_GridDataArray = $GridDataArray;
		} else {
			$this->m_GridDataArray = array();
		}
	}
	
	/*
		设置需要显示的字段数据
	*/
	private function SetFieldDataArray($FieldDataArray)
	{
		if(false == empty($FieldDataArray)) {
			$this->m_FieldDataArray = $FieldDataArray;
		} else {
			$this->m_FieldDataArray = array();
		}
	}
	
	/*
		设置配置项数据
	*/
	private function SetConfiguration($Configuration)
	{
		/* 判断配置项的数据是否为空 */
		if(false == empty($Configuration)) {
			$this->m_ConfigurationArray = $Configuration;
		} else {
			$this->m_ConfigurationArray = array();
		}
	}
	
	/*
		设置配置项数据
	*/
	private function SetOperating($Operating)
	{
		/* 判断操作项的数据是否为空 */
		if(false == empty($Operating)) {
			$this->m_OperatingArray = $Operating;
		} else {
			$this->m_OperatingArray = array();
		}
	}
	
	/*
		处理判断数组中是否存在某个键名
	*/
	private function Setuppase($Property, $Key, $Content)
	{
		/* 判断 $Key 是否在数组中存在的键名 */
		if(true == array_key_exists($Key, $this->m_ConfigurationArray)) {
			$this->$Property = $this->m_ConfigurationArray["$Key"];
		} else {
			$this->$Property = $Content;
		}
	}
	
	/*
		基础数据设置
	*/
	private function SetBasisData()
	{
		$this->SetFieldID();
		$this->SetCheckbox();
		$this->SetName();
	}
	
	/*
		设置id码
	*/
	private function SetFieldID()
	{
		$this->Setuppase('m_FieldID', 'id', null);
	}
	
	/*
		设置是否开启复选框
	*/
	private function SetCheckbox()
	{
		$this->Setuppase('m_Checkbox', 'checkbox', 0);
	}
	
	/*
		设置是name名称
	*/
	private function SetName()
	{
		$this->Setuppase('m_Name', 'name', 'checkbox');
	}
	
	
	/*
		获取拼接好的html代码
	*/
	public function GetGridHtmlInfo()
	{
		
		$Html = '<style type="text/css">
			.gridtabletrtitle { height:35px; font-weight:700; background:#EAF8FD !important; text-align:center; border-bottom:2px solid #CBE9F3 !important; }
			.gridtabletdtitle { font-size:13px;color:#666;border:1px dotted #CBE9F3; }
			
			#gridmodel .gridmodeltables { height:50px;text-align:center; border-bottom:1px dotted #CBE9F3; }
			#gridmodel .gridmodeltablecontenttr:hover { background:#F4FAFF !important; }
			#gridmodel .gridmodeltablecontenttr:hover .gridtabletd{ color:#000; }
			#gridmodel .gridtabletd:hover { color:#000; }
			.gridtabletd { border:1px dotted #CBE9F3; }
			
			.gridtablea { color:#2D64B3; cursor:pointer; }
			.gridtablea:hover { color:#FF4400 !important; }

			.gridmodeltabletrcss { background:#FBEC88 !important; }
			.gridoperating { margin:0px 3px;}
			#gridmodelnodata { margin:30px; }
			.gridmodelnodatas, .gridmodelnodatass { color:#F00; font-size:28px; font-weight:700; }
			
		</style>';
		
		$Html .= '<table style="width:100%;border-collapse:collapse;border:1px solid #CBE9F3;" border="0" cellpadding="0" cellspacing="0" id="gridmodel">';
		$Html .= $this->GetFieldTitleHtml();

		if(false == empty($this->m_GridDataArray)) {
			foreach($this->m_GridDataArray as $GKey=>$GValue) {
				$Html .= '<tr class="gridmodeltables gridmodeltablecontenttr gridmodeltabletrcss'.$GValue[$this->m_FieldID].'">';
				
				/* 判断是否开启复选框 */
				if($this->m_Checkbox == 1) {
					$Html .= '<td class="gridtabletd"><input type="checkbox" name="'.$this->m_Name.'[]" value="'.$GValue[$this->m_FieldID].'" onclick="checkboxcss();" /></td>';
				}
				foreach($this->m_FieldDataArray as $FKey=>$FValue) {
					if(true == array_key_exists($FKey, $GValue)) {
						$Html .= '<td class="gridtabletd">'.$GValue[$FKey].'</td>';
					}
				}
				
				/* 定义操作类型数据 */
				if(false == empty($this->m_OperatingArray)) {
					$Operating = $this->GeyOperatingHtml($this->m_OperatingArray, $GValue[$this->m_FieldID]);
					$Html .= '<td class="gridtabletd">'.$Operating.'</td>';
				}
				

				$Html .= '</tr>';
			}
		} else {
			return $Html.'</table><div style="margin:30px; color:#F00; font-size:28px; font-weight:700;text-align:center;">✘没有数据！</div>';
		}
		$Html .= '</table>';
		
		return $Html.$this->GetScript();
	}
	
	
	/*
		处理操作的参数方法
	*/
	private function GeyOperatingHtml($Operating = array(), $ID = null)
	{
		$Html = null;
		if(false == empty($Operating)) {
			foreach($Operating as $OKey=>$OValue) {
				if(false == empty($OValue['type'])) {
					if(false == empty($OValue['class'])) {
						$GridClass = $OValue['class'];
					} else {
						$GridClass = null;
					}
					if(false == empty($OValue['ago'])) {
						$GridAgo = $OValue['ago'];
					} else {
						$GridAgo = null;
					}
					if(false == empty($OValue['after'])) {
						$GridAfter = $OValue['after'];
					} else {
						$GridAfter = null;
					}
					
					/* 拼接前面的参数 */
					$Html .= $GridAgo;
					
					if($OValue['type'] == 1) {
						if((false == empty($OValue['name'])) && (false == empty($OValue['url'])) && (false == empty($OValue['returntype']))) {
							$Html .= '<span class="gridoperating"><a href="'.$OValue['url'].'?type='.$OValue['returntype'].'&'.$this->m_FieldID.'='.$ID.'" class="gridtablea '.$GridClass.'" title="'.$OValue['name'].'">'.$OValue['name'].'</a></span>';
						} else {
							$Html .= '配置错误！';
						}
						
					} else if($OValue['type'] == 2) {
						if((false == empty($OValue['event'])) && (false == empty($OValue['name']))) {
							$Html .= '<span class="gridoperating gridtablea '.$GridClass.'" onclick="'.$OValue['event'].'('.$ID.');" title="'.$OValue['name'].'">'.$OValue['name'].'</span>';
						} else {
							$Html .= '配置错误！';
						}
					
					} else if($OValue['type'] == 3) {
						if((false == empty($OValue['event'])) && (false == empty($OValue['name']))) {
							$Html .= '<span class="gridoperating gridtablea '.$GridClass.'" ondblclick="'.$OValue['event'].'('.$ID.');" title="'.$OValue['name'].'">'.$OValue['name'].'</span>';
						} else {
							$Html .= '配置错误！';
						}
					} else {
						$Html .= '类型未找到！';
					}
					
					/* 拼接后面的参数 */
					$Html .= $GridAfter;
				}
			}
		}
		return $Html;
	}
	
	
	/*
		处理显示字段的名称
	*/
	private function GetFieldTitleHtml()
	{
		$FieldHtml = '<tr class="gridtabletrtitle">';
		
		/* 判断是否开启复选框 */
		if($this->m_Checkbox == 1) {
			$FieldHtml .= '<td class="gridtabletdtitle" width="30px"><input type="checkbox" class="gridmodelcheckboxcick" value="0"/></td>';
		}
		
		foreach($this->m_FieldDataArray as $Gvalue) {
			$FieldHtml .= '<td class="gridtabletdtitle">'.$Gvalue.'</td>';
		}
		if(false == empty($this->m_OperatingArray)) {
			$FieldHtml .= '<td class="gridtabletdtitle">操作</td>';
		}
		
		$FieldHtml .= '</tr>';
		
		return $FieldHtml;
	}
	
	/*
		js代码
	*/
	private function GetScript()
	{
		$Script = '<script type="text/javascript" charset="utf-8">
			//处理已选择的复选框加样式
			function checkboxcss() {
				//当执行这个方法的时候先初始化页面背景样式
				$("#gridmodel .gridmodeltables").removeClass("gridmodeltabletrcss");
				$("#gridmodel .gridmodeltables").addClass("gridmodeltablecontenttr");
				
				//循环查询当前已选中的复选框，获取id然后拼接去赋样式
				$("#gridmodel input[type=\'checkbox\']:checked").each(function() {
					$(".gridmodeltabletrcss"+$(this).val()).addClass("gridmodeltabletrcss");  //赋样式
					$(".gridmodeltabletrcss"+$(this).val()).removeClass("gridmodeltablecontenttr");  //赋样式
				});
			}

			$(document).ready(function() {	
				//全选与反选处理
				$(".gridmodelcheckboxcick").click(function() {
					var value = $(this).val();
					//alert(value);
					if(value == 0) {
						$("#gridmodel [type=checkbox]:checkbox").attr("checked", true);  //选择全部
						$(this).val(1);
						//$(this).text("取消");
					} else if(value == 1) {
						$("#gridmodel [type=\'checkbox\']:checkbox").removeAttr("checked");  //取消全选
						$(this).val(0);
						//$(this).text("全选");
					}
					checkboxcss();
				});
				
				//奇行设置颜色
				$("#gridmodel tr:odd").css("background","#FFF");
				$("#gridmodel tr:even").css("background","#F5F5F5");

			});
			</script>';
		
		return $Script;
	}
	
	
}