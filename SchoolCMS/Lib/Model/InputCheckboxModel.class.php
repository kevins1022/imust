<?php

class InputCheckboxModel {

	//name名
	private $m_CheckboxName;
	
	//前面显示的名称
	private $m_CheckboxTextName;
	
	//拼接好的复选框代码
	private $m_CheckboxDataHtml;

	
	public function __construct($CheckboxDataArray, $CheckboxName = null, $CheckboxTextName = null, $Default = array())
	{
		$this->SetCheckboxName($CheckboxName);  //设置name名称
		$this->SetCheckboxTextName($CheckboxTextName);  //设置前面显示的名称
		$this->SetCheckboxDataArray($CheckboxDataArray, $Default);  //处理复选框数据拼接
		
	}

    //name名
	private function SetCheckboxName($CheckboxName)
	{
		if(null == $CheckboxName || $CheckboxName == '') {	
			$this->m_CheckboxName = '';
		}else{
			$this->m_CheckboxName = $CheckboxName;
		}
	}
	
	//设置前面显示的名称
	private function SetCheckboxTextName($CheckboxTextName)
	{
		if(null == $CheckboxTextName || $CheckboxTextName == '') {	
			$this->m_CheckboxTextName = '';  //默认的事件id
		}else{
			$this->m_CheckboxTextName = $CheckboxTextName;
		}
	}
	
	//拼接Checkbox数据
	private function SetCheckboxDataArray($CheckboxDataArray, $Default)
	{
		//处理默认值。如果 $Default 有数据的话，就循环着两个数组处理，是否默认选中复选框的状态
		if((false == empty($Default)) && (false == empty($CheckboxDataArray)) && is_array($Default) && is_array($CheckboxDataArray)) {
			foreach($CheckboxDataArray as $Ckey=>$Cvalue) {
				foreach($Default as $Dkey=>$Dvalue) {
					if($Cvalue['value'] == $Dvalue) {
						$Cvalue['type'] = 1;
					}
				}
				$CheckboxDataArray[$Ckey] = $Cvalue;
			}
		}

		//循环数据数组，拼接复选框html代码
		$StrHtml = '<span id="modelscheckbox_input_checkbox">';
		$Statu = array();
		if(false == empty($CheckboxDataArray)) {
			foreach($CheckboxDataArray as $Ckey=>$Cvalue) {
				$StrHtml .= '<label style="margin-right:16px;line-height:30px;"><input type="checkbox" name="'.$this->m_CheckboxName.'[]" value="'.$Cvalue['value'].'" class="'.$this->m_CheckboxName.'" ';
				$Statu[] = $Cvalue['type'];
				if(false == empty($Cvalue['type'])) {
					if($Cvalue['type'] == 12) {
						$StrHtml .= 'checked="checked" disabled="true" ';
					}
					if($Cvalue['type'] == 1) {
						$StrHtml .= 'checked="checked" ';
					}
					if($Cvalue['type'] == 2) {
						$StrHtml .= 'disabled="true" ';
					}
				}
				
				$StrHtml .= '/>'.$Cvalue['text'].'</label>';
			}
		} else {
			echo '<script>alert("数据不能为空！");</script>';
		}
		
		$StrHtml .= '</span></div>';
		
		$Status = true;
		if(false == empty($Statu)) {
			foreach($Statu as $Svalue) {
				if($Svalue != 1) {
					$Status = false;
				}
			}
		}
		
		$FrontStrHtml = '<div id="modelscheckboxdivid"><span>'.$this->m_CheckboxTextName.'</span><label style="margin-right:16px;"><input type="checkbox" class="modelscheckboxselect" ';
		
		if(true == $Status) {
			$FrontStrHtml .= 'checked="checked" /><span>取消</span></label>';
		} else {
			$FrontStrHtml .= '/><span>全选</span></label>';
		}
		$this->m_CheckboxDataHtml = $FrontStrHtml.$StrHtml;
	}
	
	
	//获取拼接好的html代码
	public function GetCheckboxDataHtml()
	{
		$html = '<script type="text/javascript" charset="gb2312">
			$(document).ready(function() {
				$("#modelscheckbox_input_checkbox input[type=\'checkbox\']").click(function() {
				var num = $("#modelscheckbox_input_checkbox input[type=\'checkbox\']").length;
				var number = $("#modelscheckbox_input_checkbox input:checked").length;
				if(num == number || number > num) {
					$(".modelscheckboxselect").attr("checked", true);
					$(".modelscheckboxselect").next().text("取消");
				} else {
					$(".modelscheckboxselect").attr("checked", false);
					$(".modelscheckboxselect").next().text("全选");
				}
			});
			
			$(".modelscheckboxselect").click(function() {
				if($(this).attr("checked") == "checked") {
					if($("#modelscheckboxdivid:input:checkbox").attr("disabled") != "disabled") {
						$("#modelscheckboxdivid input[type=\'checkbox\']").attr("checked", true);  //全部选择
					}
					$(".modelscheckboxselect").next().text("取消");
				} else {
					$("#modelscheckboxdivid input[type=\'checkbox\']").attr("checked", false);  //全部取消
					$(".modelscheckboxselect").next().text("全选");
				}
			});
			});
		</script>';
		
		return $html.$this->m_CheckboxDataHtml;
	}
	
	
}

