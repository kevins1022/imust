<?php

class SelectModel {
	
	private $m_SelectDataArray;  //需要显示的数据数组(二维数组)
	private $m_Configuration;  //配置项数据
	private $m_Title;  //title名称
	private $m_TitleClass;  //title名称的class名称
	private $m_SelectID;  //字段value id
	private $m_SelectName;  //字段显示名称
	private $m_Name;  //name 名称
	private $m_Default;  //默认值
	private $m_SelectBan;  //是否禁止
	private $m_Whole;  //是否开启全部一行为首
	private $m_Width;  //宽度尺寸
	private $m_Required;  //是否开启红色星号
	
	
	/* 构造方法 */
	public function __construct($SelectDataArray = array(), $Configuration = array())
	{
		/* 初始化属性数据 */
		$this->m_SelectDataArray = array();
		$this->m_Configuration = array();
		
		/* 基础数据设置 */
		$this->SetSelectDataArray($SelectDataArray);
		$this->SetConfiguration($Configuration );
		$this->SetBasisData();
	}
	
	/* 设置数据 */
	private function SetSelectDataArray($SelectDataArray)
	{
		/* 判断配置项的数据是否为空 */
		if(false == empty($SelectDataArray)) {
			$this->m_SelectDataArray = $SelectDataArray;
		} else {
			$this->m_SelectDataArray = array();
		}
	}
	
	/* 设置配置项数据 */
	private function SetConfiguration($Configuration)
	{
		/* 判断配置项的数据是否为空 */
		if(false == empty($Configuration)) {
			$this->m_Configuration = $Configuration;
		} else {
			$this->m_Configuration = array();
		}
	}
	
	
	/* 处理判断数组中是否存在某个键名 */
	private function Setuppase($Property, $Key, $Content)
	{
		/* 判断 $Key 是否在数组中存在的键名 */
		if(true == array_key_exists($Key, $this->m_Configuration)) {
			$this->$Property = $this->m_Configuration["$Key"];
		} else {
			$this->$Property = $Content;
		}
	}
	
	/* 基础数据设置 */
	private function SetBasisData()
	{
		$this->SetTitle();
		$this->SetTitleClass();
		$this->SetSelectID();
		$this->SetSelectName();
		$this->SetName();
		$this->SetDefaul();
		$this->SetSelectBan();
		$this->SetWhole();
		$this->SetRequired();
		$this->SetWidth();
	}

	/* 设置前面显示的名称 title名称 */
	private function SetTitle()
	{
		$this->Setuppase('m_Title', 'title', null);
	}
	
	/* 设置前面显示的名称 title名称的 class名称 */
	private function SetTitleClass()
	{
		$this->Setuppase('m_TitleClass', 'titleclass', null);
	}
	
	/* 设置字段value id */
	private function SetSelectID()
	{
		$this->Setuppase('m_SelectID', 'selectid', null);
	}
	
	/* 设置字段显示名称 */
	private function SetSelectName()
	{
		$this->Setuppase('m_SelectName', 'selectname', null);
	}
	
	/* 设置name名称 */
	private function SetName()
	{
		$this->Setuppase('m_Name', 'name', null);
	}
	
	/* 设置默认值 */
	private function SetDefaul()
	{
		$this->Setuppase('m_Default', 'default', null);
	}
	
	/* 设置select是否禁止 */
	private function SetSelectBan()
	{
		$this->Setuppase('m_SelectBan', 'ban', 0);
	}
	
	/* 设置是否开启全部一行为首 */
	private function SetWhole()
	{
		$this->Setuppase('m_Whole', 'whole', null);
	}
	
	/* 设置是否开启显示红色星号） */
	private function SetRequired()
	{
		$this->Setuppase('m_Required', 'required', 0);
	}
	
	/* 设置select的宽度 */
	private function SetWidth()
	{
		$this->Setuppase('m_Width', 'width', '100px');
	}
	
	/* 返回拼接好的html代码（包括js代码） */
	public function GetSelectHtmlInfo()
	{
		$Html = null;
		$Htmls = '<span class="'.$this->m_TitleClass.'">'.$this->m_Title.'</span><select name="'.$this->m_Name.'" class="input_out selectcommon '.$this->m_Name.'" id="'.$this->m_Name.'" style="margin-right:3px;width:'.$this->m_Width.'"';
		
		if($this->m_SelectBan == 1) {
			$Html .= '<input type="hidden" name="'.$this->m_Name.'" id="'.$this->m_Name.'s" value="'.$this->m_Default.'" />'.$Htmls.' disabled="disabled" />';
		} else {
			$Html .= $Htmls.' />';
		}
		if($this->m_Whole != null) {
			$Html .= '<option value="">'.$this->m_Whole.'</option>';
		}

		if((false == empty($this->m_SelectDataArray)) && (true == is_array($this->m_SelectDataArray))) {
			foreach($this->m_SelectDataArray as $Skey=>$Svalue) {
				if((false == empty($Svalue)) && (true == is_array($Svalue))) {
					
					if($this->m_Default == $Svalue[$this->m_SelectID]) {
						$Html .= '<option value="'.$Svalue[$this->m_SelectID].'" selected="selected">'.$Svalue[$this->m_SelectName].'</option>';
					} else {
						$Html .= '<option value="'.$Svalue[$this->m_SelectID].'">'.$Svalue[$this->m_SelectName].'</option>';
					}
				}
			}
		}
		
		$Html .= '</select>';
		if($this->m_Required == 1) {
			$Html .= '<span style="color:#F00;">*</span>';
		}

		$ScriptJs = '<style type="text/css">
						.input_move, .input_moves{ padding:3px; color:#333 !important; background:#FFF !important; border-style:solid; border-width:1px; outline:none; -moz-border-radius:4px; -webkit-border-radius:4px; border-radius:4px; resize:none; border:1px solid #4FACF5 !important; box-shadow:0 0 5px #4FACF5 !important; }
						.input_out { padding:3px; background:#F5F5F5; border-style:solid; border-width:1px; border-color:#ABADB3 #E2E3EA #E2E3EA #ABADB3; outline:none; -moz-border-radius:4px; -webkit-border-radius:4px; border-radius:4px; resize:none; }
					</style>
		
					<script type="text/javascript" charset="utf8">
					$(document).ready(function (){
						
						//事件样式处理
						$(".input_out").mousedown(function() {
							$(this).addClass("input_move");
						}).blur(function() {
							$(this).removeClass("input_move");
						});
						
						$(".selectcommon").mouseover(function() {
							$(this).addClass("input_moves");
						}).mouseout(function() {
							$(this).removeClass("input_moves");
						});
						
						$(".selectcommon").focus(function() {
							$(this).addClass("input_moves");
						}).focusout(function() {
							$(this).removeClass("input_moves");
						});

					});
			</script>';
			
		return $Html.$ScriptJs;
	}
	
}