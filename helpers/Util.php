<?php 
class Util{

	function getIP()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	
	function processResult($result){
	
		$result = explode("|", $result);
		if(strpos($result[0], "OK") !== false ){
			$msg = $result[1];
		}
		else{			
			$msg = $result[1];
			$error = true;
		}
		$this->assignRef("msg" , $msg);
		$this->assignRef("error" , $error);
	}
}








