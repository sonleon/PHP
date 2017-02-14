<?php
/*
 * 分页类
 * 时间：2017-2-9
 * 作者sjs
 * 使用方法：
 * 只需要定义好所有的成员属性，然后调用create方法就行了
 * 下一页 上一页
 */

class Page{
	private $pagenow;//当前页面
	private $total;//总页面
	private $pagesize;//每页显示的数据条数
	private $url;//点击超链接时跳转的url地址
	private $where='';//额外的参数
	
	public function __set($p,$v){
		if(property_exists($this, $p)){
			$this->$p=$v;
		}
	}
	public function __get($p){
		if(property_exists($this, $p)){
			return $this->$p;
		}else{
			echo '属性不存在!';
			exit;
		}
	}
	//动态创建分页导航条
	public function create(){
		$where="&keyword=".$this->where;		
		//定义首页按钮
		//当前页高亮显示
		$first_active=$this->pagenow==1?'active':'';
		$url=$this->url.'?page=';
		$PAGE='<ul class="pagination">';
		if($this->pagenow >= 2){
			$last=$this->pagenow-1;
			$PAGE.=<<<HTML
			<li><a href="$url$last$where">上一页</a></li>
HTML;
		}
		
		
		//----------------------------------------------------------------------
		
		//创建中间的分页导航按钮
		$pagenum=ceil($this->total/$this->pagesize);//总共分了多少页面
		//循环创建中间的分页按钮
		for($i=$this->pagenow-3;$i<=$this->pagenow+3;$i++){
			//显示当前页的3页和后3页
			//判断是否是当前页
			$active=$this->pagenow==$i?'active':'';
			//判断是超过限制
			if($i<1||$i>$pagenum){
				continue;//跳出本次循环
			}			
		$PAGE.=<<<HTML
		<li class="$active"><a href="$url$i$where">$i</a></li>
HTML;
		}
		
		//-------------------------------------------------------------------
		
		
		
		//定义尾页按钮
		
			//$last_active=$pagenum?$pagenum:1;
		if($this->pagenow<$pagenum){
			$next=$this->pagenow+1;
			$PAGE.=<<<HTML
			<li><a href="$url$next$where">下一页</a></li>
HTML;
			
		}



	//将创建的分页导航条返回
	return $PAGE;//注意，当调用这个方法的时候必须要配合echo语句才能正常输出
		
	}

}

