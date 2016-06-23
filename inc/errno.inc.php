<?php

	function errno ( $errno ) {
	
		$message = "";
		
		if ( $errno == 101 ) {
		
			$message = "Invalid Shop ID, Username or Password";

		}
		
		return $message;
		
	}