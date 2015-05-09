<?php


class CSVModel
{

	//所生成的csv文件名
	private $m_CsvFileName;

	//触发事件的id名
	private $m_CsvDivMark;

	//Csv的title名称数据
	private $m_CsvTitltDataArray;

	//Url地址
	private $m_CsvUrl;

	//跳转Url地址
	private $m_CsvJumpUrl;

	//拼接好的复选框html代码
	private $m_CsvTitltDataHtml;

	//导出的所有数据(表头加数据)
	private $m_CsvContent;


	public function __construct($TitltDataArray, $CsvDivMark = null, $CsvFileName = null, $CsvUrl = null, $JumpUrl = null)
	{
		$this->SetCsvDivMark($CsvDivMark);  //设置页面触发的click事件
		$this->SetCsvFileName($CsvFileName);  //设置导出文件名称
		$this->SetCsvUrl($CsvUrl);  //设置ajax交互地址
		$this->SetCsvJumpUrl($JumpUrl);  //跳转地址
		$this->SetTitltDataArray($TitltDataArray);  //处理title数据
	}

	//拼接titlt数据
	private function SetTitltDataArray($TitltDataArray)
	{
		$this->m_CsvTitltDataArray = $TitltDataArray;
		if(false == empty($TitltDataArray)) {
			$html = '<div id="csvwindowsinputcheckbox'.$this->m_CsvDivMark.'" style="margin:10px; height:130px;"><p style="margin-bottom:10px !important;"><label class="csvwindowslabel'.$this->m_CsvDivMark.'" ><input type="checkbox" class="csvwindowscheckboxjs'.$this->m_CsvDivMark.'" checked="checked"/><span style="font-weight:800; font-size:12px;">取消</span></label></p><span id="csvwindowsinput_checkbox'.$this->m_CsvDivMark.'">';
			foreach($TitltDataArray as $Ckey=>$Cvalue) {
				$html .= '<label class="csvwindowslabel'.$this->m_CsvDivMark.'" style="font-size:12px; vertical-align: text-top;"><input type="checkbox" name="csvcheckbox[]" value="'.$Ckey.'" checked="checked"/>'.$Cvalue.'</label>';
			}
			$html .= '</span></div>';
			$this->m_CsvTitltDataHtml = $html;
		} else {
			$this->m_CsvTitltDataHtml =  null;
		}
	}

	//获取title的html数据
	private function GetCheckboxDataHtml()
	{
		return $this->m_CsvTitltDataHtml;
	}

    //设置导出文件名
	private function SetCsvFileName($CsvFileName)
	{
		//判断用户是否自己定义了文件名
		if(null == $CsvFileName || $CsvFileName == '') {
			$this->m_CsvFileName = date('YmdHis').'.csv';  //默认文件名为当前时间组成
		}else{
			$this->m_CsvFileName = $CsvFileName.'.csv';
		}
	}

	//获取导出文件名
	private function GetFileName()
	{
		return $this->m_CsvFileName;
	}

	//设置divID号
	private function SetCsvDivMark($CsvDivMark)
	{
		//判断用户是否自己定义了divID号
		if(null == $CsvDivMark || $CsvDivMark == '') {
			$this->m_CsvDivMark = 'csvdivid';  //默认的事件id
		}else{
			$this->m_CsvDivMark = $CsvDivMark;
		}
	}

	//获取divID号
	private function GetCsvDivMark()
	{
		return $this->m_CsvDivMark;
	}

	//设置Url地址
	private function SetCsvUrl($CsvUrl)
	{
		if(false == empty($CsvUrl)) {
			$this->m_CsvUrl = $CsvUrl;
		} else {
			$this->m_CsvUrl = null;
		}
	}

	//设置跳转Url地址
	private function SetCsvJumpUrl($JumpUrl)
	{
		if(false == empty($JumpUrl)) {
			$this->m_CsvJumpUrl = $JumpUrl;
		} else {
			$this->m_CsvJumpUrl = null;
		}
	}

	//获取Url地址
	private function GetCsvUrl()
	{
		return $this->m_CsvUrl;
	}

	//导出CSV数据的方法，参数，需要是二维数组，就是需要导出的数据
	public function CsvExportData($DataArray = array())
	{
		$CsvStr = '';
		//title数据处理
		if(false == empty($_REQUEST['type'])) {
			if($_REQUEST['type'] == 'csvexport') {
				if((false == empty($_REQUEST['csvcheckbox'])) && (true == is_array($_REQUEST['csvcheckbox']))) {
					$FieldArray = $_REQUEST['csvcheckbox'];
					//循环拼接title名称
					foreach($FieldArray as $Fkey=>$Fvalue) {
						//判断这个键名在数组中是否存在
						if(true == array_key_exists($Fvalue, $this->m_CsvTitltDataArray)) {
							$CsvStr .= $this->m_CsvTitltDataArray[$Fvalue].',';  //拼接title数据
						}
					}
					$CsvStr .= "\n";
				} else {
					/* 如果调整地址为空的话，就默认跳转到上一次过来的地址 */
					echo '<script>alert("导出字段不能为空！");</script>';
					if(false == empty($this->m_CsvJumpUrl)) {
						$Url = $this->m_CsvJumpUrl;
					} else {
						$Url = $_SERVER['HTTP_REFERER'];
					}
					echo '<script>window.location.href="'.$Url.'"</script>';
					exit();
				}
			}

			//需要导出的数据判断处理
			if(false == empty($DataArray)) {
				//循环拼接数据
				foreach($DataArray as $Dkey=>$Dvalue) {
					foreach($FieldArray as $Fkey=>$Fvalue) {
						//判断这个键名在数组中是否存在
						if(true == array_key_exists($Fvalue, $Dvalue)) {
							$CsvStr .= strip_tags($Dvalue[$Fvalue]).',';  //拼接CSV数据，再去除html和php标签
						}
					}
					$CsvStr .= "\n";
				}

				/* 从基本设置表读取分页参数 */
				$BasicsetupObj = M('basicsetup');
				$BasicsetupData = $BasicsetupObj->field('bdatavalue')->where('btype="modelcsv"')->select();
				if(false == empty($BasicsetupData[0]['bdatavalue'])) {
					$ModelCSVid = intval($BasicsetupData[0]['bdatavalue']);
				} else {
					$ModelCSVid = 1;
				}
				//将拼接好的数据赋给成员属性，1：不转换，2:将本身的utf-8转换成gbk编码。
				if($ModelCSVid == 1) {
					$this->m_CsvContent = $CsvStr;
				} else if($ModelCSVid == 2) {
					$this->m_CsvContent = iconv('utf-8','gbk', $CsvStr);
				}

			} else {
				/* 如果调整地址为空的话，就默认跳转到上一次过来的地址 */
				echo '<script>alert("数据数据不能为空！");</script>';
				if(false == empty($this->m_CsvJumpUrl)) {
					$Url = $this->m_CsvJumpUrl;
				} else {
					$Url = $_SERVER['HTTP_REFERER'];
				}
				echo '<script>window.location.href="'.$Url.'"</script>';
				exit();
			}

			$this->CsvExport();  //调用导出CSV文件
		}
	}

    //导出csv文件的方法
	private function CsvExport()
	{
		header('Content-type:text/csv');
		header('Content-Disposition:attachment;filename='.$this->GetFileName());
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $this->m_CsvContent;
		exit();
	}

	//拼接弹窗代码
	public function GetTitltDataHtml()
	{
		$strcss = '$(mydivid).wrap(\'<div id="mydragdt'.$this->m_CsvDivMark.'" style="background:#F0F0F0; border:1px solid #D3D3D3; position:absolute; top:0px; left:0px; margin-top:10%; margin-left:30%; z-index:1; box-shadow:0px 3px 20px 8px #CCC; border-radius:5px; width:500px; height:400px;"></div>\');
			$(\'#mydragdt'.$this->m_CsvDivMark.'\').prepend(\'<div style="text-align:left;width:100%;line-height:32px;"><p id="mydragtops'.$this->m_CsvDivMark.'" style="margin:0px 0px 0px 6px;cursor:move;font-weight:700;">\'+title+\'</p><div id="mydragtop'.$this->m_CsvDivMark.'" style="background:#F0F0F0;padding-right:6px;cursor:move; border-radius:5px;text-align:right;margin:-32px 0px 0px 0px;line-height:32px;"><span id="mydragclose'.$this->m_CsvDivMark.'" style="padding:1px 3px; font-size:16px; cursor:pointer; background:#EC9D7E; color:#FFF0DF; border-radius:5px;" title="关闭">×</span></div></div>\');
			$(\'#mydragdt'.$this->m_CsvDivMark.'\').wrap(\'<div id="mydrag'.$this->m_CsvDivMark.'" style="background-color:rgba(0, 0, 0, 0.06); width:100%; height:100%; position:absolute; left:0px; top:0px; display:none; z-index:999999;"></div>\');

			$(mydivid).css({"margin":"0px 6px 6px 6px", "border":"1px solid #D3D3D3", "background":"#FFF", "width":"486px", "height":"360px", "overflow":"hidden", "text-overflow":"ellipsis"});
			$(".csvwindowslabel'.$this->m_CsvDivMark.'").css({"margin-right":"16px", "cursor":"pointer", "line-height":"24px" });
			$("#mydragclose'.$this->m_CsvDivMark.'").mouseover(function() {
			$(this).css({"color":"#FFF", "background":"#EC571D"});}).mouseleave(function() {
			$(this).css({"color":"#FFF0DF", "background":"#EC9D7E"});
			});';

		$html = "<script type='text/javascript' charset='utf8'>
			function mydrag(mydivid, mysub, width, height, title) {
				/* 标签处理部分代码 */
				".$strcss."

				/* 将宽高赋给css */
				if(width > 1 || height > 1) {
					var pwidth = width-12;
					var pheight = height-40;
					$(mydivid).css({'width':pwidth, 'height':pheight});
					$('#mydragdt".$this->m_CsvDivMark."').css({'width':width, 'height':height});
				}

				/* 下面是拖拽代码 */
				$(function(){
					var bool = false;  //标识是否移动元素
					var left = 0;  //声明DIV在当前窗口的Left值
					var top = 0;  //声明DIV在当前窗口的Top值
					var w = 0;
					var h = 0;
					$('#mydragtop".$this->m_CsvDivMark.", #mydragtops".$this->m_CsvDivMark."').mousedown(function(event){
						bool = true;  //当鼠标在移动元素按下的时候将bool设定为true
						var event = event || window.event;
						left = event.offsetX || event.pageX //获取鼠标在当前窗口的相对偏移位置的Left值并赋值给left
						top = event.offsetY || event.pageY  //获取鼠在当前窗口的相对偏移位置的Top值并赋值给top
						//alert(event.offsetX);
						if(event.offsetX == undefined) {
						//alert(event.offsetX);
							w = $(this).width()/2;
							h = $(this).height()/2;
						}
						return false;  // 这里返回 false 鼠标样式就不会被改变
						//console.log(x,y);  //测试使用

					}).mouseup(function(){
						bool = false;  //当鼠标在移动元素起来的时候将bool设定为false
					});
					/* 当鼠标指针在指定的元素中移动时，就触发生 mousemove 事件。 */
					$(document).mousemove(function(event){
						//当bool为true的时候执行下面的代码
						if(true == bool) {
							if(w > 1) {
								var x = event.pageX-w;
							} else {
								var x = event.clientX-left;
							}
							//event.clientX得到鼠标相对于客户端正文区域的偏移，然后减去left即得到当前推拽元素相对于当前窗口的X值（减去鼠标刚开始拖动的时候在当前窗口的偏移X）
							if(h > 1) {
								var y = event.pageY-h;
							} else {
								var y = event.clientY-top;
							}
							//console.log(x,y);  //测试使用
							//event.clientY得到鼠标相对于客户端正文区域的偏移，然后减去top即得到当前推拽元素相对于当前窗口的Y值（减去鼠标刚开始拖动的时候在当前窗口的偏移Y）
							$('#mydragdt".$this->m_CsvDivMark."').css({'margin-left':x, 'margin-top':y});
						}
					});
				});

				//关闭弹窗
				$('#mydragclose".$this->m_CsvDivMark."').click(function() {
					$('#mydrag".$this->m_CsvDivMark."').slideUp(500);
				});

				//开启弹窗事件
				$(mysub).click(function() {
					$('#mydrag".$this->m_CsvDivMark."').fadeIn(500);
				});
			}


			//调用弹窗方法
			$(document).ready(function() {
				mydrag('#mydragcontent".$this->m_CsvDivMark."', '#".$this->GetCsvDivMark()."', 600, 250, '导出数据字段选择');

				$('.csvwindowscheckboxjs".$this->m_CsvDivMark."').click(function() {
					if($(this).attr(\"checked\") == 'checked') {
						if($('#csvwindowsinputcheckbox".$this->m_CsvDivMark.":input:checkbox').attr('disabled') != 'disabled') {
							$('#csvwindowsinputcheckbox".$this->m_CsvDivMark." input[type=\"checkbox\"]').attr('checked', true);  //全部选择
						}
						$('.csvwindowscheckboxjs".$this->m_CsvDivMark."').next().text('取消');
					} else {
						$('#csvwindowsinputcheckbox".$this->m_CsvDivMark." input[type=\"checkbox\"]').attr('checked', false);  //全部取消
						$('.csvwindowscheckboxjs".$this->m_CsvDivMark."').next().text('全选');
					}
				});
				$('#csvwindowsinput_checkbox".$this->m_CsvDivMark." input[type=\"checkbox\"]').click(function() {
					var num = $('#csvwindowsinput_checkbox".$this->m_CsvDivMark."  input[type=\"checkbox\"]').length;
					var number = $('#csvwindowsinput_checkbox".$this->m_CsvDivMark."  input:checked').length;
					if(num == number || number > num) {
						$('.csvwindowscheckboxjs".$this->m_CsvDivMark."').attr('checked', true);
					} else {
						$('.csvwindowscheckboxjs".$this->m_CsvDivMark."').attr('checked', false);
					}
				});

				//当点击导出后关闭弹窗
				$('#submitmobelcsv').click(function() {
					$('#mydrag".$this->m_CsvDivMark."').slideUp(500);
				});
			});
		</script>
		<style type='text/css'>
			#submitmobelcsv:hover { background:#71C9E7 !important; }
		</style>
		<div id='mydragcontent".$this->m_CsvDivMark."'>
			<form action='".$this->GetCsvUrl()."' method='post'>
				".$this->GetCheckboxDataHtml()."
				<input type='hidden' name='type' value='csvexport' />
				<input type='submit' id='submitmobelcsv' value='确认导出' title='确认导出' style='padding:8px; background:#B7B8B8; border-radius:5px; cursor:pointer; font-weight:bolder; font-size:16px; float:right; color:#FFF; margin-right:30px; border:0px;'/>
			</form>
		</div>";

		return $html;
	}


}
?>
