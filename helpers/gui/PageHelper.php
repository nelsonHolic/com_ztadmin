<?php 

/**
Usage:

*/
class PageHelper{
	
	/**
	* Initialize the page
	*/
	static function initPage($title, $subTitle){
		echo "
				<div class='page-content'>
					<div class='container-fluid'>
					
					<div class='row-fluid'>
						<div class='span12'>
									
							<h3 id='page-title' class='page-title'>
								$title				<small>$subTitle</small>
							</h3>
							<ul id='page-breadcrumb' class='breadcrumb'>		
							</ul>
						</div>
					</div>
					<div id='dashboard'>
				";		
	}

	
	/**
	* End page
	*/
	static function endPage(){
		echo "</div></div></div></div>";
	}
	
	
	
	static function footerPage(){
		echo "
			<div class='footer'>
				2013 &copy; ChronosAdmin
				<div class='span pull-right'>
					<span class='go-top'><i class='icon-angle-up'></i></span>
				</div>
			</div>
		";
	}
	
	static function separator(){
		echo "<div class='clearfix'></div>";
	}
	
	static function initRow(){
		echo "<div class='row-fluid'>";
	}
	
	static function endRow(){
		echo "</div>";
	}
	
	static function span($span){
		echo "<div class='span$span'>";
	}
	
	static function endSpan(){
		echo "</div>";
	}
	
	
	/**
	* Add the initial breadcrumb (using a home icon)
	*/
	static function addInitialBreadcrumb($name, $link){
		$data = "<li>
					<i class='icon-home'></i>
					<a href='$link'>$name</a> 
					<i class='icon-angle-right'></i>
				</li>";	
		$data = UtilHelper::toOneLine($data);
		UtilHelper::append('page-breadcrumb',$data);
	}
	
	/**
	* Add normal breadcrumb
	*/
	static function addBreadcrumb($name, $link){
		$data = "<li>
					<a href='$link'>$name</a> 
					<i class='icon-angle-right'></i>
				</li>";	
		$data = UtilHelper::toOneLine($data);
		UtilHelper::append('page-breadcrumb',$data);
	}
	
	/**
	* Add the final breadcrumb, not link is needed
	*/
	static function addFinalBreadcrumb($name){
		$data = "<li>
					<a href='#'>$name</a> 
				</li>";	
		$data = UtilHelper::toOneLine($data);
		UtilHelper::append('page-breadcrumb',$data);
	}
	
	/**
	* Clean all the breadcrumb data
	*/
	static function cleanBreadcrumb(){
		$data = "";	
		$data = UtilHelper::toOneLine($data);
		UtilHelper::clearList('page-breadcrumb');
	}
	

	
	

	
}








