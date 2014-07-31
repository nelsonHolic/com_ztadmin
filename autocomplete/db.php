<?php
	function conectDB(){
		
		$mysqli = new mysqli("localhost","mitecnot_usersms","Sms123*","mitecnot_sms");
                                  		 
		if ( $mysqli->connect_errno ) {
			echo "Failed to connect to MySQL: " . $mysqli->connect_errno;
		}
		return $mysqli;
	}
?>
