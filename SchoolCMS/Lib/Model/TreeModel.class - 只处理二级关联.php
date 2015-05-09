<?php

class TreeModel
{
	private $m_CommonObj;  //公共文件对象
	private $m_TableObj;  //数据表实例化对象
	
	private $m_FileDataArray;  //配置信息最初数据
	private $m_LinkedArray;  //数据表的管理表
	private $m_FileID;  //id字段名称（默认：id）
	private $m_FilePID;  //父id字段名称（默认：pid）
	private $m_FileName;  //name字段名称（默认：name）
	private $m_FileSort;  //排序字段名称（默认：null）
	private $m_TableName;  //数据表名称
	private $m_TreeUrl;  //节点Ajax请求的url地址
	private $m_Checkbox;  //复选框开关状态
	private $m_AddNode;  //新增下级开关状态
	private $m_Operating;  //操作列开关
	private $m_Sort;  //排序列开关
	private $m_Name;  //title名称是否显示开关
	private $m_Form;  //form表单提交
	private $m_DbEditor;  //鼠标双击编辑名称开关
	
	
	/*
		构造方法
	*/
	public function __construct($FileDataArray = array(), $LinkedArray = array())
	{
		/* 实例化公共文件，使用提示方法 */
		$this->m_CommonObj = new CommonAction();
		
		/* 初始化最初数据 */
		$this->m_FileDataArray = array();
		$this->m_LinkedArray = array();
		
		/* 调用最初数据方法处理配置信息数据 */
		$this->SetFileDataArray($FileDataArray);
		$this->SetLinkedArray($LinkedArray);
		
		/* 设置基础数据 */
		$this->SetBasisData();
	}
	
	/*
		设置最初配置数据
	*/
	private function SetFileDataArray($FileDataArray)
	{
		if(false == empty($FileDataArray)) {
			$this->m_FileDataArray = $FileDataArray;
		} else {
			$this->m_FileDataArray = array();
		}
	}
	
	/*
		设置当前表的管理表数据
	*/
	private function SetLinkedArray($LinkedArray)
	{
		if(false == empty($LinkedArray)) {
			$this->m_LinkedArray = $LinkedArray;
		} else {
			$this->m_LinkedArray = array();
		}
	}
	
	/*
		处理判断数组中是否存在某个键名
	*/
	private function Setuppase($Property, $Key, $Content)
	{
		/* 判断 $Key 是否在数组中存在的键名 */
		if(true == array_key_exists($Key, $this->m_FileDataArray)) {
			$this->$Property = $this->m_FileDataArray["$Key"];
		} else {
			$this->$Property = $Content;
		}
	}
	
	/*
		基础数据设置
	*/
	private function SetBasisData()
	{
		$this->SetFileID();
		$this->SetFilePID();
		$this->SetFileName();
		$this->SetFileSort();
		$this->SetTableName();
		$this->SetAjaxUrl();
		$this->SetCheckbox();
		$this->SetAddNode();
		$this->SetOperating();
		$this->SetSort();
		$this->SetName();
		$this->SetForm();
		$this->SetDbEditor();
	}
	
	/*
		设置字段id名称
	*/
	private function SetFileID()
	{
		$this->Setuppase('m_FileID', 'fileid', 'id');
	}
	
	/*
		设置字段的父id名称
	*/
	private function SetFilePID()
	{
		$this->Setuppase('m_FilePID', 'filepid', null);
	}
	
	/*
		设置字段name名称
	*/
	private function SetFileName()
	{
		$this->Setuppase('m_FileName', 'filename', 'name');
	}
	
	/*
		设置字段排序名称
	*/
	private function SetFileSort()
	{
		$this->Setuppase('m_FileSort', 'filesort', null);
	}
	
	/*
		设置字段数据表名称
	*/
	private function SetTableName()
	{
		$this->Setuppase('m_TableName', 'tablename', null);
	}
	
	/*
		设置AjaxUrl地址
	*/
	private function SetAjaxUrl()
	{
		$this->Setuppase('m_TreeUrl', 'treeurl', null);
	}
	
	/*
		设置是否开启复选框
	*/
	private function SetCheckbox()
	{
		$this->Setuppase('m_Checkbox', 'checkbox', 0);
	}
	
	/*
		设置是否开启新增下级操作
	*/
	private function SetAddNode()
	{
		$this->Setuppase('m_AddNode', 'addnode', 0);
	}
	
	/*
		设置是否开启操作列
	*/
	private function SetOperating()
	{
		$this->Setuppase('m_Operating', 'operating', 0);
	}
	
	/*
		设置是否开启排序列
	*/
	private function SetSort()
	{
		$this->Setuppase('m_Sort', 'sort', 0);
	}
	
	/*
		设置是否开启title名称
	*/
	private function SetName()
	{
		$this->Setuppase('m_Name', 'name', 0);
	}
	
	/*
		设置是否开启form
	*/
	private function SetForm()
	{
		$this->Setuppase('m_Form', 'form', 0);
	}
	
	/*
		设置是否开启双击编辑名称
	*/
	private function SetDbEditor()
	{
		$this->Setuppase('m_DbEditor', 'dbeditor', 0);
	}
	
	/*
		从数据库读取最终的数据进行处理，返回数据
	*/
	private function GetTreeDataArray($PID = '-0-')
	{
		$DataArray = array();
		
		/* 判断是否有排字段名称 */
		$Order = $this->m_FileID;
		if((false == empty($this->m_FileSort)) && (null != $this->m_FileSort)) $Order = $this->m_FileSort;
		
		/* 判断是否是多级操作，如果pid为空，就表示不是多级表，就没有条件 */
		if((false == empty($this->m_FilePID)) && (null != $this->m_FilePID)) {
			//$Condition[$this->m_FilePID] = array('like', "%{$PID}%");
			$DataRow = $this->m_TableObj->where("$this->m_FilePID='$PID'")->order("$Order asc")->select();
		} else {
			$DataRow = $this->m_TableObj->order("$Order asc")->select();
		}

		/* 循环处理数据 */
		foreach($DataRow as $DKey=>$DValue) {
			$NodeArray = array();
			$NodeArray[$this->m_FileID] = $DValue[$this->m_FileID];
			$NodeArray[$this->m_FileName] = $DValue[$this->m_FileName];
			
			/* 判断是否是多级表的操作 */
			if((false == empty($this->m_FilePID)) && (null != $this->m_FilePID)) {
				$NodeArray[$this->m_FilePID] = $DValue[$this->m_FilePID];
				$NodeArray['state'] = $this->SethasChild($DValue[$this->m_FileID]);
			}
			
			/* 判断是否有排字段名称 */
			if((false == empty($this->m_FileSort)) && (null != $this->m_FileSort)) {
				$NodeArray[$this->m_FileSort] = $DValue[$this->m_FileSort];
			}
			
			/* 数组合并 */
			array_push($DataArray, $NodeArray);
		}
		//echo '<pre>';print_r($DataArray);exit();
		return $DataArray;
	}
	
	/*
		判断当前节点下面是否还有字节点数据
	*/
	public function SethasChild($ID = null)
	{
		if(false == empty($ID)) {
			$Condition[$this->m_FilePID] = array('like', "%{$ID}%");
			$Rows = $this->m_TableObj->where($Condition)->select();
			return (false == empty($Rows[0]) ? 1 : 0);
		} else {
			return 0;
		}
	}
	
	/*
		数据写入 更新 删除 操作判断
	*/
	private function TreeDataInfo($TreeData)
	{
		$Status = true;
		/* 数据插入判断 */
		if(false == empty($TreeData['treemodeladdpid']) ? $AddPID = $TreeData['treemodeladdpid'] : $AddPID = array());
		if(false == empty($TreeData['treemodeladdsort']) ? $AddSort = $TreeData['treemodeladdsort'] : $AddSort = array());
		if(false == empty($TreeData['treemodeladdname']) ? $AddName = $TreeData['treemodeladdname'] : $AddName = array());
		if(false == empty($TreeData['treemodeldelete']) ? $Delete = $TreeData['treemodeldelete'] : $Delete = array());
		if(false == empty($TreeData['treemodeladdname']) || false == empty($TreeData['treemodeldelete'])) {
			$this->TreeDataAddInfo($AddPID, $AddSort, $AddName, $Delete);
			$Status = false;
		}

		if(false == empty($TreeData['type'])) {
			/* 更新名称 */
			if($TreeData['type'] == 'treemodelupdatename') {
				$this->TreeDataUpdateInfo($TreeData[$this->m_FileID], $TreeData[$this->m_FileName], $this->m_FileName);
			
			/* 更新排序 */
			} else if($TreeData['type'] == 'treemodelupdatesort') {
				$this->TreeDataUpdateInfo($TreeData[$this->m_FileID], $TreeData[$this->m_FileSort], $this->m_FileSort);
			}
			$Status = false;
		}
		
		if(true == $Status) {
			$this->m_CommonObj->SchoolCMSPrompt('没有操作项！');
		}
	}
	
	/*
		数据写入 操作
	*/
	private function TreeDataAddInfo($AddPID, $AddSort, $AddName, $Delete)
	{
		if(false == empty($AddName)) {
			foreach($AddName as $AddKey=>$AddValue) {
				$AddDataArray = array();
				if(false == empty($AddValue)) {
					$AddDataArray[$this->m_FileName] = $AddValue;
					if(true == array_key_exists($AddKey, $AddPID)) {
						$AddDataArray[$this->m_FilePID] = $AddPID[$AddKey];
						if(true == empty($AddPID[$AddKey])) $AddDataArray[$this->m_FilePID] = '-0-';
					}
					if(true == array_key_exists($AddKey, $AddSort)) {
						$AddDataArray[$this->m_FileSort] = intval($AddSort[$AddKey]);
						if(false == isset($AddSort[$AddKey])) $AddDataArray[$this->m_FileSort] = 0;
					}
					$Statu = $this->m_TableObj->add($AddDataArray);
				}
			}
		}
		
		/* 判断是否有删除数据操作 */
		if(false == empty($Delete)) {
			$DeleteStatu = $this->TreeDataDeleteInfo($Delete);
		} else {
			$DeleteStatu = false;
		}
		//print_r($AddDataArray);
		/* 新增状态 */
		if(true == $Statu || true == $DeleteStatu) {
			$this->m_CommonObj->SchoolCMSPrompt("操作成功！");
		} else {
			$this->m_CommonObj->SchoolCMSPrompt('操作失败！');
		}
	}
	
	/*
		数据更新 操作
	*/
	private function TreeDataUpdateInfo($UpdateID, $UpdateData, $UpdateFile)
	{
		if(false == empty($UpdateID)) {
			if(true == empty($UpdateData)) $UpdateData = 0;
			$Statu = $this->m_TableObj->where("$this->m_FileID=$UpdateID")->setField("$UpdateFile", "$UpdateData");
			if($Statu) {
				echo 1;exit();
			} else {
				echo 0;exit();
			}
		} else {
			echo 0;exit();
		}
	}
	
	/*
		数据删除 操作
	*/
	private function TreeDataDeleteInfo($DeleteData)
	{
		if(false == empty($DeleteData)) {
			foreach($DeleteData as $DeleteValue) {
				if(false == empty($DeleteValue) && false == is_array($DeleteValue)) {
					/* 使用id去关联表删除当前关联数据 */
					if(false == empty($this->m_LinkedArray)) {
						$this->TreeDataLinkedDeleteInfo($DeleteValue);
					}

					/* 如果pid字段不为空，则使用like先删除pid相关的数据 */
					if((false == empty($this->m_FilePID)) && (null != $this->m_FilePID)) {
						$Sting = '-'.$DeleteValue.'-';
						$Condition[$this->m_FilePID] = array('like', "%{$Sting}%");
						$this->m_TableObj->where($Condition)->delete();
					}
					$Statu = $this->m_TableObj->where("$this->m_FileID=$DeleteValue")->delete();
				}
			}
		}
		return $Statu;
	}
	
	/*
		关联数据删除方法
	*/
	private function TreeDataLinkedDeleteInfo($DeleteValueID)
	{
		/* 这里处理管理字段的删除 */
		if((false == empty($DeleteValueID)) && (false == is_array($DeleteValueID))) {
		
			/* 查询当前节点下的所有节点，然后再调用递归方法处理，返回最终所有的数据 */
			$DataRet = $this->TreeRecursionInfo($this->InquiryNodeLikeInfo($DeleteValueID));
			
			/* 把当前主id放到最终的数组里面去 */
			$DataRet[] = $DeleteValueID;
			
			/* 循环处关联表，实例化临时表，按照当前节点id进行删除操作 */
			foreach($this->m_LinkedArray as $LinkedKey=>$LinkedValue) {
				if(false == empty($LinkedKey) && false == empty($LinkedValue)) {
					if((true == is_array($DataRet)) && (false == empty($DataRet))) {
						foreach($DataRet as $DeleteID) {
							$TreeDeleteObj = M("$LinkedKey");
							$Statu = $TreeDeleteObj->where("$LinkedValue=$DeleteID")->delete();
						}
					}
				}
			}
		}
	}
	
	/*
		根据节点id模糊查询下面的子节点
	*/
	private function InquiryNodeLikeInfo($DeleteValue)
	{
		if((false == empty($this->m_FilePID)) && (null != $this->m_FilePID)) {
			$Sting = '-'.$DeleteValue.'-';
			$Condition[$this->m_FilePID] = array('like', "%{$Sting}%");
			$Data = $this->m_TableObj->where($Condition)->select();
			if(false == empty($Data[0])) {
				return $Data;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}
	
	/*
		处理每个节点的id
	*/
	private function TreeRecursionInfo($DataArray)
	{
		$ArrayData = array();
		if(true == is_array($DataArray) && null != $DataArray) {
			foreach($DataArray as $DataValue) {
				if(false == empty($DataValue[$this->m_FileID])) {
					$ArrayData[] = $DataValue[$this->m_FileID];
					$Data = $this->InquiryNodeLikeInfo(intval($DataValue[$this->m_FileID]));
					$this->TreeRecursionInfo($Data);
				}
			}
		}
		/* 返回当前需要删除的节点下的所有节点数据 */
		return $ArrayData;
	}
	
	
	/*
		title拼接方法
	*/
	private function GetTreeTiileHtml()
	{
		$HtmlTiile = '<div id="treemodelprompt" class="treedisplaynone"><span class="img"></span><span class="text"></span></div>';
		$HtmlTiile .= '<div style="text-align:left;width:100%;"id="treemodeldivcontent">';
		
		
		/* html代码拼接部分 */
		$HtmlTiile .= '<table class="treemodeltable treemodeltabletitle"><tr><td width="20px"></td>';
		
		/* 判断是否开启复选框 */
		if($this->m_Checkbox == 1) {
			$HtmlTiile .= '<td width="38px" class="treetablecenter">删除</td>';
		}
		
		/* 判断是否开启排序列 */
		if(($this->m_Sort == 1) && (false == empty($this->m_FileSort))) {
			$HtmlTiile .= '<td width="38px" class="treetablecenter">排序</td>';
		}
		
		/* 判断是否开启title名称显示 */
		if($this->m_Name == 1) {
			$HtmlTiile .= '<td><span>名称</span></td>';
		} else {
			$HtmlTiile .= '<td><span></span></td>';
		}
		
		
		/* 判断是否开启了新增下级 */
		if($this->m_AddNode == 1) {
			$HtmlTiile .= '<td width="100px" class="treetablecenter">新增下级</td>';
		}
		
		/* 操作删除列 */
		if($this->m_Operating == 1) {
			$HtmlTiile .= '<td width="100px" class="treetablecenter">操作</td>';
		}
		$HtmlTiile .= '</tr></table>';
		
		return $HtmlTiile;
	}
	
	
	/*
		处理最终的数据拼接html代码
	*/
	public function GetTreeDataHtml()
	{
		/* 实例化表对象 */
		$this->m_TableObj = M("$this->m_TableName");
		
		
		/* 判断是不是form表单提交 */
		if(false == empty($_REQUEST['treesubmit'])) {
			$this->TreeDataInfo($_REQUEST);exit();
		}
		
		/* 获取节点等级，然后处理后成缩进值 */
		$Cut = (false == empty($_REQUEST['treemodelcut']) ? intval($_REQUEST['treemodelcut']) : 0);
		/* 获取当前节点的总父id值 */
		$APID = (false == empty($_REQUEST['treemodelapid']) ? intval($_REQUEST['treemodelapid']) : 0);
		/* 获取查询数据的父id */
		$PID = (false == empty($_REQUEST['treemodel'.$this->m_FileID]) ? $_REQUEST['treemodel'.$this->m_FileID] : '-0-');
		//print_r($PID);
		
		/* 将pid传递进去进行数据读取处理 */
		$DataArray = $this->GetTreeDataArray($PID);
		//print_r($DataArray);
		
		/* 获取title拼接的代码 */
		$HtmlTiile = $this->GetTreeTiileHtml();
		
		/* 如果pid等于0，就表示是初始化，不需要有缩进 */
		if($PID != '-0-') {
			$Cut++; $Cut++;
		}
		
		/* 内容部分 */
		$Html = null;

		/* 喜循环处理拼接数据 */
		foreach($DataArray as $DKey=>$DValue) {
			/* 处理一级id */
			if($PID == '-0-') {
				$APIDS = $DValue[$this->m_FilePID];
				$APID = $DValue[$this->m_FileID];
			} else {
				$APIDS = '-0--'.$APID.'-';
			}
			if($DValue['state'] == 1) {
				$Html .= '<table class="treemodeltable treetableid'.$DValue[$this->m_FileID].' pid'.$DValue[$this->m_FilePID].' pid'.$APIDS.'" style="padding-left:'.$Cut.'0px;"><tr><td class="treemodelcut"><span onclick="treestretch('.$DValue[$this->m_FileID].');" class="this'.$DValue[$this->m_FileID].' treemodelcuts">[+]</span><span class="statu'.$DValue[$this->m_FileID].' treedisplaynone">0</span><span class="content'.$DValue[$this->m_FileID].' treedisplaynone">0</span><span class="cut'.$DValue[$this->m_FileID].' treedisplaynone">'.$Cut.'</span><span class="apid'.$DValue[$this->m_FileID].' treedisplaynone">'.$APID.'</span><span class="treedisplaynone node'.$DValue[$this->m_FileID].'">'.$DValue[$this->m_FilePID].'</span></td>';
			} else {
				$Html .= '<table class="treemodeltable treetableid'.$DValue[$this->m_FileID].' pid'.$DValue[$this->m_FilePID].' pid'.$APIDS.'" style="padding-left:'.$Cut.'0px;"><tr><td class="treemodelcut"><span class="treedisplaynone node'.$DValue[$this->m_FileID].'">'.$DValue[$this->m_FilePID].'</span></td>';
			}

			/* 判断是否开启复选框 */
			if($this->m_Checkbox == 1) {
				$Html .= '<td width="38px" class="treetablecenter"><input type="checkbox" class="treemodeldeleteclick" name="treemodeldelete[]" value="'.$DValue[$this->m_FileID].'" /></td>';
			}
			
			/* 判断是否开启排序列 */
			if(($this->m_Sort == 1) && (false == empty($this->m_FileSort))) {
				$Html .= '<td width="38px" class="treetablecenter"><input type="text" size="1" value="'.$DValue[$this->m_FileSort].'" class="treemodelupdatesortclick treeinputsort" title="可编辑"/><span class="treedisplaynone">'.$DValue[$this->m_FileID].'</span></td>';
			}
			
			/* 名称 */
			$Html .= '<td><span class="treemodelupdatenamedblclick" id="treemodelinputname'.$DValue[$this->m_FileID].'" title="双击可编辑">'.$DValue[$this->m_FileName].'</span><span class="treedisplaynone">'.$DValue[$this->m_FileID].'</span></td>';
			
			/* 判断是否开启了新增下级 */
			if($this->m_AddNode == 1) {
				$Html .= '<td width="100px" class="treetablecenter"><span onclick="treeaddnode('.$DValue[$this->m_FileID].');" class="treeaddclick" title="新增下级"><span class="treeaddclicks">+</span>新增下级</span></td>';
			}
			
			/* 操作删除列 */
			if($this->m_Operating == 1) {
				$Html .= '<td width="100px" class="treetablecenter"><span onclick="treemodeldeleteoperatingclick('.$DValue[$this->m_FileID].');" class="treemodelperatingcolor">删除</span></td>';
			}
			$Html .= '</tr></table>';
		}		
		
		/* 如果pid为0，就是初始化的需要一起返回js和css代码 */
		if($PID == '-0-') {
			$HtmlReturn = null;
			
			/* 判断是否开启了form表单 */
			if($this->m_Form == 1) {
			
				$Html .= '<div class="treemodelendbottom"></div><table class="treemodeltablesubmit treetableid0"><tr>';
		
				/* 判断是否开启复选框 */
				if($this->m_Checkbox == 1) {
					$Html .= '<td width="20px"><input type="checkbox" id="treemodelcheckboxclick" value="0"/></td>';
				}
				
				/* 新增顶级新增操作 */
				$Html .= '<td style="text-align:left;width:120px;"><span onclick="treeaddnode(0);" class="treeaddclick" title="新增顶级分类"><span class="treeaddclicks">+</span>新增顶级分类</span><span class="treedisplaynone node0"></span></td>';
				
				$Html .= '<td><input type="submit" class="treemodelsubmit" name="treesubmit" value="提交"></td>';
				
				$Html .= '</tr></table>';

				$HtmlReturn .= '<form action="'.$this->m_TreeUrl.'" method="post">'.$Html.'</form>';

			} else {
				$HtmlReturn .= $Html;
			}
			return $HtmlTiile.$HtmlReturn.$this->GetJavascriptCssHtml().'</div>';
		} else {
			/* 如果pid不为0 ，就代表是ajax请求读物节点下的数据 */
			echo $Html; exit();
		}
	}
	
	/*
		js和css代码
	*/
	private function GetJavascriptCssHtml()
	{
		return $Javascript = '<style type="text/css">
						.treedisplaynone { display:none; }
						.treedisplayblock { display:block; }
						.treemodelcut { width:20px; height:40px; }
						.treemodelcuts, .treedelete, .treeaddclick { font-size:12px; cursor:pointer; color:#888; }
						.treemodelcuts { color:#999; }
						.treedelete { color:#B1BDD6; margin-left:6px; }
						.treedelete:hover, .treeaddclick:hover, .treemodelperatingcolor:hover { color:#F40; }
						.treeaddclicks, .treemodeltabletitle, .treemodelsubmit, #treemodelprompt .text { font-weight:700; }
						.treeaddclicks { font-weight:700; margin-right:3px; }
						.treeinputsort { margin-right:10px; }
						.treemodeltable, .treemodeltablesubmit, .treemodelendbottom { width:100%; }
						.treemodeltable { border-bottom:1px dotted #DEEFFB; }
						.treemodeltablesubmit, .treemodeltabletitle { line-height:40px; }
						.treemodeltable:hover { background:#F5F9FD; }
						.treemodeltabletitle { background:#F7FCFD !important; }
						.treetablecenter { text-align:center; }
						.treemodelperatingcolor { color:#2D64B3; cursor:pointer; }
						.treemodelsubmit { padding:6px 12px; background:#EFEFEF; color:#555; font-size:14px; border:1px solid #C5C7CB; box-shadow:0px 1px 6px 1px #CCC; border-radius:5px; cursor:pointer; }
						.treemodelsubmit:hover { background:#E6F7FE; color:#1AA3D1; border:1px solid #4ABBE0; box-shadow:0px 1px 6px 1px #BEE7F7; }
						#treemodelcheckboxclick { margin:0px 13px 0px 33px; }
						.treemodeltablecss { background:#FBEC88 !important; }
						.treemodelendbottom { border-bottom:1px solid #DEEFFB; }
						#treemodelprompt { width:150px; height:45px; margin:10px 0px 0px 50%; position:absolute; overflow:hidden; opacity:0.5; background:#CEE9F8; border-radius:50px; padding-left:15px; text-align:center; }
						#treemodelprompt .img{ font-size:38px; float:left; }
						#treemodelprompt .text{ font-size:16px; line-height:47px; }
					</style>
		
					<script type="text/javascript" charset="utf8">
					
					/* 添加子节点方法 */
					function treeaddnode(id) {
						$(document).ready(function(){
							var nodepid = $(".node"+id).text();
							var left = parseInt($(".treetableid"+id).css("padding-left"))+22;
							var html = "<table style=\'width:100%;line-height:30px;padding-left:"+left+"px\' class=\'treeaddtable\'><tr>";
							if('.$this->m_Checkbox.' == 1) {
								html += "<td width=\'20px\'></td><td width=\'15px\'></td>";
							}
							
							if('.$this->m_Sort.' == 1) {
								html += "<td width=\'15px\'><input type=\'text\' size=\'1\' value=\'0\' name=\'treemodeladdsort[]\' class=\'treeinputsort\' /></td>";
							}
							
							html += "<td>";
							
							/* 判断是否是多级分类，如果不是就不拼接父id字段 */
							if("'.$this->m_FilePID.'" != "") {
								html += "<input type=\'hidden\' name=\'treemodeladdpid[]\' value=\'"+nodepid+"-"+id+"-\' />";
							}
							
							html += "<input type=\'text\'  name=\'treemodeladdname[]\' /><span class=\'treedelete\' title=\'删除\'>×</span></td><td></td></tr></table>";
							
							//前面：before，后面：after
							if(id == 0) {
								$(".treetableid"+id).before(html);
							} else {
								$(".treetableid"+id).after(html);
							}
						});
					}
					
					//处理已选择的复选框加样式
					function checkboxcss() {
						//当执行这个方法的时候先初始化页面背景样式
						$(".treemodeltable").removeClass("treemodeltablecss");
						
						//循环查询当前已选中的复选框，获取id然后拼接去赋样式
						$("#treemodeldivcontent input[type=\'checkbox\']:checked").each(function() {
							$(".treetableid"+$(this).val()).addClass("treemodeltablecss");  //赋样式
						});
					}
					
					/* 倒计时方法 */
					var i = 5;
					function treemodelsetintervalset() {
						i = 5;
						setInterval("treemodelsetinterval()", 1000);
					}
					function treemodelsetinterval() {  //倒计时关闭跳转窗口
						if(i == 1) {
							$(document).ready(function() {
								$("#treemodelprompt").slideUp(500);
							});
						} else {
							i--;
						}
					}
					
					/* Ajax方法 */
					function treemodelajax(data) {
						$.ajax({
							url:"'.$this->m_TreeUrl.'",
							type:"post",
							data:data,
							dataType:"json",
							success:function(data) {
								if(data == 1) {
									//alert("操作成功！");
									$("#treemodelprompt .img").html("✔");
									$("#treemodelprompt .text").html("操作成功！");
									$("#treemodelprompt .img").css("color", "#5EBB29");
									$("#treemodelprompt .text").css("color", "#5EBB29");
									$("#treemodelprompt").slideToggle(500);
									treemodelsetintervalset();
								} else {
									//alert("操作失败！");
									$("#treemodelprompt .img").html("✘");
									$("#treemodelprompt .text").html("操作失败！");
									$("#treemodelprompt .img").css("color", "#F00");
									$("#treemodelprompt .text").css("color", "#F00");
									$("#treemodelprompt").slideToggle(500);
									treemodelsetintervalset();
								}
							}
						});
					}
					
					
					$(document).ready(function(){
						/* 删除元素事件 */
						$(".treedelete").live("click", function() {
							$(this).closest("table").remove();
						});
						
						
						/* 全选与反选处理 */
						$("#treemodelcheckboxclick").click(function() {
							var value = $(this).val();
							if(value == 0) {
								/* 选择全部 */
								$("#treemodeldivcontent [type=checkbox]:checkbox").attr("checked", true);
								$(this).val(1);
							} else if(value == 1) {
								/* 取消全选 */
								$("#treemodeldivcontent [type=\'checkbox\']:checkbox").removeAttr("checked");
								$(this).val(0);
							}
							checkboxcss();
						});
						
						/* 单击复选框事件 */
						$(".treemodeldeleteclick").live("click", function() {
							checkboxcss();
						});
						
						/* 鼠标双击编辑名称 */
						if('.$this->m_DbEditor.' == 1) {
							$(".treemodelupdatenamedblclick").live("dblclick", function() {
								var value = $(this).text();
								var valueid = $(this).next().text();
								if(value == "null") value = "";
								$(this).html("<input class=\'treemodelnameinput\' value=\'"+value+"\' /><span class=\'treedisplaynone\'>"+valueid+"</span><span class=\'treedisplaynone\'>"+value+"</span>");
								$(".treemodelnameinput").focus();
							});
							/* input失去焦点触发Ajax保存数据 */
							$(".treemodelnameinput").live("blur", function() {
								/* 获取当前修改后的数据 */
								var value = $(this).val();
								
								/* 获取当前节点的父节点id值 */
								var parentsid = $(this).parents().attr("id");
								
								/* 如果修改后的名称为空,就获取上一次的名称。则发生Ajax保存 */
								if(value == "") {
									value = $(this).next().next().text();
								} else {
									/* 获取当前节点的id */
									valueid = $(this).next().text();
									//alert(valueid+" "+value);
									/* 发送Ajax保存 */
									var data = "treesubmit=AjaxUpdateName&type=treemodelupdatename&'.$this->m_FileID.'="+valueid+"&'.$this->m_FileName.'="+value;
									treemodelajax(data);
								}
								/* 页面赋值 */
								$("#"+parentsid).html(value);
							});
						}
						
						/* 单击编辑顺序 */
						$(".treemodelupdatesortclick").live("blur", function() {
							var sortvalue = $(this).val();
							var valueid = $(this).next().text();
							//alert(valueid+" "+sortvalue);
							/* 发送Ajax保存 */
							var data = "treesubmit=AjaxUpdateSort&type=treemodelupdatesort&'.$this->m_FileID.'="+valueid+"&'.$this->m_FileSort.'="+sortvalue;
							treemodelajax(data);
						});
						
						/* 点击提交判断是否有删除操作 */
						$(".treemodelsubmit").click(function() {
							var treemodelcheckbox = $("#treemodeldivcontent input:checked").length;
							if(treemodelcheckbox > 0) {
								if(confirm("是否删除？删除后将无法恢复、包括当前分类下的所有关联数据一并删除！")) {
									return true;
								} else {
									return false;
								}
							}
						});
					});
					
					/* 节点后面点击删除操作方法 */
					function treemodeldeleteoperatingclick(id) {
						$(document).ready(function(){
							if(confirm("是否删除？删除后将无法恢复、包括当前分类下的所有关联数据一并删除！")) {
								location.href="'.$this->m_TreeUrl.'?treemodeldelete[]="+id+"&treesubmit=删除";
							}
						});
					}
					
					/* 伸缩方法 */
					function treestretch(id) {
						$(document).ready(function() {
							var nodepid = $(".node"+id).text();  //获取当前节点的父id
							var statu = $(".statu"+id).text();  //获取展开折叠状态值
							var content = $(".content"+id).text();  //节点下的内容状态值
							var cut = $(".cut"+id).text();  //获取节点的等级值
							var apid = $(".apid"+id).text();  //获取一级父id
							//console.info(apid);
							if(statu == 0) {
								if(content == 0) {
									$.ajax({
										url:"'.$this->m_TreeUrl.'",
										type:"post",
										data:"treemodelcut="+cut+"&treemodelapid="+apid+"&treemodel'.$this->m_FileID.'="+nodepid+"-"+id+"-",
										dataType:"html",
										success:function(data) {
											//console.info(data);  //调试使用
											$(".treetableid"+id).after(data);
										}	
									});
									$(".content"+id).text(1);
								} else if(content == 1) {
									$(".pid"+nodepid+"-"+id+"-").removeClass("treedisplaynone");
									$(".pid"+nodepid+"-"+id+"-").addClass("treedisplayblock");
								}
								$(".this"+id).text("[-]");
								$(".statu"+id).text(1);
							} else if(statu == 1) {
								//$(".pid"+nodepid+"-"+id+"-").removeClass("treedisplayblock");
								//$(".pid"+nodepid+"-"+id+"-").addClass("treedisplaynone");
								
								/* 模糊查询class包含当前id的 */
								$("[class*=-"+id+"-]").removeClass("treedisplayblock");
								$("[class*=-"+id+"-]").addClass("treedisplaynone");
								
								$(".this"+id).text("[+]");
								$(".statu"+id).text(0);
							}
						});
					}
					</script>';
	}
	
	
	
	
	
}
?>