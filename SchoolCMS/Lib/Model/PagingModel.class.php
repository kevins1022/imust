<?php

class PagingModel
{
	private $m_PagingDataArray;  //接收页面提交的post或者get的一维数组条件
	private $m_Configuration;  //配置项数据
	private $m_Fraction;  //每个页面显示的条数
	private $m_Total;  //数据的总条数
	private $m_Page;  //页面传递过来的页码值
	private $m_Starting;  //查询数据的起始值
	private $m_TotalFraction;  //计算出来的总页数
	private $m_Url;  //分页使用的url地址
	private $m_PageCoent;  //是否开启页面数字分页按钮
	private $m_CustomPage;  //是否开启自定义跳转页码
	private $m_DetailData;  //是否开启详情数据


	/*
		构造方法
	*/
	public function __construct($PagingDataArray = array(), $Configuration = array())
	{
		/* 初始化属性数据 */
		$this->m_PagingDataArray = array();
		$this->m_Configuration = array();

		/* 基础数据设置 */
		$this->SetPagingDataArray($PagingDataArray);
		$this->SetConfiguration($Configuration );
		$this->SetBasisData();
	}

	/*
		设置数据
	*/
	private function SetPagingDataArray($PagingDataArray)
	{
		/* 判断配置项的数据是否为空 */
		if(false == empty($PagingDataArray)) {
			$this->m_PagingDataArray = $PagingDataArray;
		} else {
			$this->m_PagingDataArray = array();
		}
	}

	/*
		设置配置项数据
	*/
	private function SetConfiguration($Configuration)
	{
		/* 判断配置项的数据是否为空 */
		if(false == empty($Configuration)) {
			$this->m_Configuration = $Configuration;
		} else {
			$this->m_Configuration = array();
		}
	}


	/*
		处理判断数组中是否存在某个键名
	*/
	private function Setuppase($Property, $Key, $Content)
	{
		/* 判断 $Key 是否在数组中存在的键名 */
		if(true == array_key_exists($Key, $this->m_Configuration)) {
			$this->$Property = $this->m_Configuration["$Key"];
		} else {
			$this->$Property = $Content;
		}
	}

	/*
		基础数据设置
	*/
	private function SetBasisData()
	{
		$this->SetFraction();
		$this->SetTotal();
		$this->SetPage();
		$this->SetTotalFraction();
		$this->SetStarting();
		$this->SetUrl();
		$this->SetPageCoent();
		$this->SetCustomPage();
		$this->SetDetailData();
	}

	/*
		设置每页显示数据的条数
	*/
	private function SetFraction()
	{
		$this->Setuppase('m_Fraction', 'traction', 15);
	}

	/*
		设置数据的总条数
	*/
	private function SetTotal()
	{
		$this->Setuppase('m_Total', 'total', 0);
	}

	/*
		设置页面传递过来的页码值
	*/
	private function SetPage()
	{
		/* 判断 $Key 是否在数组中存在的键名 */
		if(true == array_key_exists('page', $this->m_PagingDataArray)) {
			$this->m_Page = max(1, (false == empty($this->m_PagingDataArray['page']) ? intval($this->m_PagingDataArray['page']) : 0));
		} else {
			$this->m_Page = 1;
		}
	}

	/*
		设置计算出来的总页数， 总页数 = 总条数除以每页显示的条数。
	*/
	private function SetTotalFraction()
	{
		$this->m_TotalFraction = ceil($this->m_Total/$this->m_Fraction);

		/* 当前页数大于最大页数时，将总页数的值赋值给当前页面，防止超越操作。*/
		if($this->m_Page >= $this->m_TotalFraction) {
			$this->m_Page = $this->m_TotalFraction;
		}
	}

	/*
		设置查询数据的起始值
	*/
	private function SetStarting()
	{
		$this->m_Starting = ($this->m_Page - 1) * $this->m_Fraction;
	}

	/*
		设置分页的url地址
	*/
	private function SetUrl()
	{
		$this->Setuppase('m_Url', 'url', null);
	}

	/*
		设置是否开启显示数字分页按钮
	*/
	private function SetPageCoent()
	{
		$this->Setuppase('m_PageCoent', 'pagecoent', 0);
	}
	
	/*
		设置是否开启自定义跳转页码
	*/
	private function SetCustomPage()
	{
		$this->Setuppase('m_CustomPage', 'custompage', 0);
	}
	
	/*
		设置是否开启详情数据
	*/
	private function SetDetailData()
	{
		$this->Setuppase('m_DetailData', 'detaildata', 0);
	}

	/*
	获取查询数据的起始值
	*/
	public function GetStarting()
	{
		return $this->m_Starting;
	}

	/*
		获取每页显示的条数值
	*/
	public function GetFraction()
	{
		return $this->m_Fraction;
	}

	/*
		获取拼接的每页显示的数字页码
	*/
	private function GetPageCoent($PageUrl)
	{
		/* 如果page值不等于1的时候 */
		if($this->m_Page != 1) {
			/* 如果分页数值加显示的分页个数值大于当前总页码数的时候 */
			if(($this->m_Page+$this->m_PageCoent) > $this->m_TotalFraction) {
				/* 计算起始值 */
				$Pageis = $this->m_Page-$this->m_PageCoent;
				/* 计算最大数值 */
				$PageMax = $this->m_TotalFraction;

			/* 如果分页数值加显示的分页个数值不大于当前总页码数的时候 */
			} else {
				/* 计算起始值，如果当前page小于等于显示的页数时，就将起始设置为1，防止负数 */
				if($this->m_Page <= $this->m_PageCoent) {
					$Pageis = 1;
				} else {
					$Pageis = $this->m_Page-$this->m_PageCoent;
				}


				/* 计算最大数值，当前page数值加需要显示的页码个数值 */
				$PageMax = (($this->m_Page+$this->m_PageCoent));
			}

			/* 如果显示页码值大于等于总页码值时，将起始值设置为1 */
			if($this->m_PageCoent >= $this->m_TotalFraction) {
				$Pageis = 1;
			}
			
			/* 判断当前起始值是否小于或者等于0，防止起始出现负数 */
			if($Pageis <= 0) {
				$Pageis = 1;
			}

		/* 如果page等于1的时候 */
		} else {
			/* 如果显示页码值大于等于总页码值时，就将总页码值赋值给循环的最大值 */
			if($this->m_PageCoent >= $this->m_TotalFraction) {
				$PageMax = $this->m_TotalFraction;
			} else {
				$PageMax = $this->m_PageCoent+1;
			}
			$Pageis = 1;
		}

		/* 循环拼接需要显示的分页数值个数代码 */
		$PageCoent = '<li>';
		for($Pagei=$Pageis; $Pagei<=$PageMax; $Pagei++) {
			if($this->m_Page == $Pagei) {
				$PageCoent .= '<span class="pageingpagecoent pageingpagecoents">'.$Pagei.'</span>';
			} else {
				$PageCoent .= '<a href="'.$PageUrl.$Pagei.'"><span class="pageingpagecoent">'.$Pagei.'</span></a>';
			}

		}
		/* 返回拼接好的代码 */
		return $PageCoent;
	}


	/*
		获取url拼接，处理URL拼接方法
	*/
	private function GetUrlSplice()
	{
		$UrlSplice = '?';
		if(false == empty($this->m_PagingDataArray)) {
			//删除当前数组中的page数据
			unset($this->m_PagingDataArray['page']);
			foreach($this->m_PagingDataArray as $PKey=>$pValue) {
				/* 拼接普通url */
				if((false == empty($pValue)) && (false == is_array($pValue))) {
					$UrlSplice .= $PKey.'='.$pValue.'&';
				}

				/* 拼接是数组的url */
				/*if((false == empty($pValue)) && (true == is_array($pValue))) {

				}*/
			}
			//print_r($this->m_PagingDataArray);
		}
		return $UrlSplice;
	}


	/*
		返回拼接好的html代码（包括js代码）
	*/
	public function GetPagingHtmlInfo()
	{
		$UrlSplice = $this->GetUrlSplice();

		$PageUrl = $this->m_Url.$UrlSplice.'page=';
		$PageUrls = $PageUrl.($this->m_Page-1);
		$PageUrly = $PageUrl.($this->m_Page+1);

		/* 防止自定义page大于总页数，页码加一后是否大于当前数据的总页数 */
		if($this->m_Page+1 >= $this->m_TotalFraction) {
			$PageInputValue = $this->m_TotalFraction;
		} else {
			$PageInputValue = $this->m_Page+1;
		}


		if($this->m_PageCoent > 0) {
			$PageCoent = $this->GetPageCoent($PageUrl);
		} else {
			$PageCoent = null;
		}


		/* 定义分页数据 */
		$Html = '<div id="pageingmodel"><ul id="pageing">';

		$Home = '<li><a href="'.$PageUrl.'1">首页</a></li>';
		$Previous = '<li><a href="'.$PageUrls.'">上一页</a></li>';
		$Next = '<li><a href="'.$PageUrly.'">下一页</a></li>';
		$End = '<li><a href="'.$PageUrl.$this->m_TotalFraction.'">尾页</a></li>';

		$HomeS = '<li class="pageban">首页</li>';
		$PreviousS = '<li class="pageban">上一页</li>';
		$NextS = '<li class="pageban">下一页</li>';
		$EndS = '<li class="pageban">尾页</li>';
		$InputText = '<li class="pageban" id="modelpageinput">去第 <input type="text" size="2" value="'.$PageInputValue.'" id="modelpagevalue"/><span id="modelinputsubmit" title="确认跳到当前页">确认</span></li>';


		/* 当只有一页数据的时候，就没有拼接url地址 */
		if($this->m_TotalFraction == 1) {
			$Html .= $HomeS.$PreviousS.$PageCoent.$NextS.$EndS;
		/* 当没有数据的时候，就没有拼接url地址 */
		} elseif($this->m_Page == $this->m_TotalFraction && $this->m_Total == 0) {
			$Html .= $HomeS.$PreviousS.$PageCoent.$NextS.$EndS;
		/* 当为第一页的时候 */
		} elseif($this->m_Page == 1) {
			$Html .= $HomeS.$PreviousS.$PageCoent.$Next.$End;

		/* 到尾部的时候 */
		} elseif($this->m_Page == $this->m_TotalFraction  && $this->m_TotalFraction > 1) {
			$Html .= $Home.$Previous.$PageCoent.$NextS.$EndS;

		/* 正常的时候 */
		} else {
				$Html .= $Home.$Previous.$PageCoent.$Next.$End;
		}

		if($this->m_CustomPage == 1) {
			$Html .= $InputText;
		}

		if($this->m_DetailData == 1) {
			$Html .= '<li>当前第<span class="pagepublic">'.$this->m_Page.'</span>页</li><li>共<span class="pagepublic">'.$this->m_TotalFraction.'</span>页</li><li>总有<span class="pagepublic">'.$this->m_Total.'</span>条数据</li></ul></div>';
		}

		/* css代码 */
		$Css = '<style type="text/css">
			#pageingmodel { width:100%; }
			#pageing li { float:left; margin-right:15px; }
			#pageing li a { color:#0D93BF; }
			#pageing li a:hover { color:#F00; }
			.pageban { color:#999; }
			.pageingpagecoent, .pagepublic { font-weight:bold; }
			.pageingpagecoent { padding:2px 8px; background:#C4C5C5; color:#FFF; margin:0px 5px; -moz-border-radius:4px; -webkit-border-radius:4px; border-radius:4px; font-size:13px; }
			.pageingpagecoents, .pageingpagecoent:hover { background:#099ACF; }
			#modelinputsubmit { background:#099ACF; color:#FFF; padding:2 8px; border-radius:0px 5px 5px 0px; cursor:pointer; border-right:1px solid #1A88B1; border-top:1px solid #1A88B1; border-bottom:1px solid #1A88B1; border-left:0px; }
			#modelpagevalue { background:#F5F5F5; text-align:center; height:18px; border-radius:5px 0px 0px 5px; border-left:1px solid #1A88B1; border-top:1px solid #1A88B1; border-bottom:1px solid #1A88B1; border-right:0px; }

		</style>
		<script type="text/javascript" charset="utf8">
			$(document).ready(function() {
				$("#modelinputsubmit").click(function() {
					var value = $("#modelpagevalue").val();
					location.href="'.$PageUrl.'"+value;
				});
			});

		</script>';

		return $Html.$Css;
	}

}